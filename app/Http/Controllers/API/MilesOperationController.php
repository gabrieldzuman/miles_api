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
        return response()->json(["success" => 1, 'milesOperations' => $milesOperations], 200);
    }

    public function getMilesOperation($milesOperationId)
    {
        try {
            $milesOperation = MilesOperation::find($milesOperationId);
            if ($milesOperation) {
                return response()->json(["success" => 1, 'milesOperation' => $milesOperation], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Operação não encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => 0, 'message' => $e->getMessage()], 500);
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

            $milesOperation = new MilesOperation;
            $milesOperation->miles_operation_amount = $request->value;
            $milesOperation->miles_operation_type = 'debito';
            $milesOperation->miles_account_id = $milesAccount->id;
            $milesOperation->active = 1;
            $milesOperation->save();

            DB::commit();

            return response()->json(["success" => 1, 'message' => 'Resgate realizado com sucesso'], 200);
        } catch (ValidationException $e) {
            return response()->json(["success" => 0, 'message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["success" => 0, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $milesOperationId)
    {
        try {
            $milesOperation = MilesOperation::find($milesOperationId);

            if ($milesOperation) {
                $milesOperation->update($request->all());
                return response()->json(["success" => 1, 'message' => 'Operação atualizada com sucesso', 'milesOperation' => $milesOperation], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Operação não encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => 0, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($milesOperationId)
    {
        try {
            $milesOperation = MilesOperation::find($milesOperationId);

            if ($milesOperation) {
                $milesOperation->active = 0;
                $milesOperation->save();
                return response()->json(["success" => 1, 'message' => 'Operação desativada com sucesso'], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Operação não encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(["success" => 0, 'message' => $e->getMessage()], 500);
        }
    }
}
