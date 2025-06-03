<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\MilesAccount;
use App\Models\Api\MilesOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class MilesOperationController extends Controller
{
    public function index()
    {
        $milesOperations = MilesOperation::all();
        return $this->successResponse(['milesOperations' => $milesOperations]);
    }

    public function getMilesOperation($milesOperationId)
    {
        try {
            $milesOperation = MilesOperation::find($milesOperationId);
            if (!$milesOperation) {
                return $this->notFoundResponse('Operação não encontrada');
            }
            return $this->successResponse(['milesOperation' => $milesOperation]);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function milesOperation(Request $request)
    {
        $request->validate([
            'miles_account_id' => 'required|exists:miles_accounts,id',
            'value' => 'required|numeric|min:0',
        ]);

        try {
            $milesAccount = MilesAccount::find($request->miles_account_id);

            DB::beginTransaction();

            $milesAccount->miles_accounts_balance -= $request->value;
            $milesAccount->save();

            $milesOperation = new MilesOperation();
            $milesOperation->miles_operation_amount = $request->value;
            $milesOperation->miles_operation_type = 'debito';
            $milesOperation->miles_account_id = $milesAccount->id;
            $milesOperation->active = 1;
            $milesOperation->save();

            DB::commit();

            return $this->successResponse([], 'Resgate realizado com sucesso');
        } catch (ValidationException $e) {
            return response()->json([
                "success" => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    public function update(Request $request, $milesOperationId)
    {
        try {
            $milesOperation = MilesOperation::find($milesOperationId);

            if (!$milesOperation) {
                return $this->notFoundResponse('Operação não encontrada');
            }

            $milesOperation->update($request->all());

            return $this->successResponse(
                ['milesOperation' => $milesOperation],
                'Operação atualizada com sucesso'
            );
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy($milesOperationId)
    {
        try {
            $milesOperation = MilesOperation::find($milesOperationId);

            if (!$milesOperation) {
                return $this->notFoundResponse('Operação não encontrada');
            }

            $milesOperation->active = 0;
            $milesOperation->save();

            return $this->successResponse([], 'Operação desativada com sucesso');
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Return a successful JSON response.
     */
    private function successResponse(array $data = [], string $message = '', int $statusCode = 200)
    {
        $response = ['success' => true];
        if ($message) {
            $response['message'] = $message;
        }
        return response()->json(array_merge($response, $data), $statusCode);
    }

    /**
     * Return a not found JSON response.
     */
    private function notFoundResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    /**
     * Return an error JSON response.
     */
    private function errorResponse(\Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
