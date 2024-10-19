<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\api\MilesAccount;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class MilesAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $milesAccounts = MilesAccount::all();
            return response()->json(["success" => 1, 'milesAccounts' => $milesAccounts], 200);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a specific miles account.
     *
     * @param int $milesAccountId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMilesAccount($milesAccountId)
    {
        try {
            $milesAccount = MilesAccount::find($milesAccountId);

            if ($milesAccount) {
                return response()->json(["success" => 1, 'milesAccount' => $milesAccount], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Conta não encontrada'], 404);
            }
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Check balance for a specific account.
     *
     * @param int $balanceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkBalance($balanceId)
    {
        try {
            $balance = MilesAccount::where('id', $balanceId)->get();

            if ($balance->isNotEmpty()) {
                return response()->json(["success" => 1, 'balance' => $balance], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Saldo não encontrado'], 404);
            }
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created miles account.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $milesAccount = MilesAccount::create($request->all());
            DB::commit();

            return response()->json(["success" => 1, 'message' => 'Conta gravada com sucesso', 'milesAccount' => $milesAccount], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified miles account.
     *
     * @param Request $request
     * @param int $milesAccountId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $milesAccountId)
    {
        try {
            $milesAccount = MilesAccount::find($milesAccountId);

            if ($milesAccount) {
                $milesAccount->update($request->all());
                return response()->json(["success" => 1, 'message' => 'Conta alterada com sucesso', 'milesAccount' => $milesAccount], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Conta não encontrada'], 404);
            }
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Soft delete (deactivate) the specified miles account.
     *
     * @param int $milesAccountId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($milesAccountId)
    {
        try {
            $milesAccount = MilesAccount::find($milesAccountId);

            if ($milesAccount) {
                $milesAccount->update(['active' => 0]);
                return response()->json(["success" => 1, 'message' => 'Conta desativada com sucesso'], 200);
            } else {
                return response()->json(["success" => 0, 'message' => 'Conta não encontrada'], 404);
            }
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Handle exception for all actions.
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleException(Exception $e)
    {
        return response()->json(["success" => 0, 'message' => $e->getMessage()], 500);
    }
}
