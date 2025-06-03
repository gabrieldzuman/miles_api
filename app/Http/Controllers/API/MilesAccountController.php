<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\MilesAccount;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MilesAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $milesAccounts = MilesAccount::all();
            return response()->json(['success' => 1, 'milesAccounts' => $milesAccounts], 200);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Retrieve a specific miles account.
     */
    public function show($id)
    {
        try {
            $milesAccount = $this->findMilesAccountOrFail($id);
            return response()->json(['success' => 1, 'milesAccount' => $milesAccount], 200);
        } catch (Exception $e) {
            return $this->handleNotFoundException($e);
        }
    }

    /**
     * Check balance for a specific account.
     */
    public function checkBalance($id)
    {
        try {
            $balance = MilesAccount::find($id);

            if (!$balance) {
                return response()->json(['success' => 0, 'message' => 'Saldo não encontrado'], 404);
            }

            return response()->json(['success' => 1, 'balance' => $balance->balance ?? null], 200);
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Store a newly created miles account.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Defina as regras conforme seu modelo, por exemplo:
            'user_id' => 'required|integer|exists:users,id',
            'balance' => 'required|numeric|min:0',
            'active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()], 422);
        }

        try {
            DB::beginTransaction();

            $milesAccount = MilesAccount::create($request->only(['user_id', 'balance', 'active']));

            DB::commit();

            return response()->json(['success' => 1, 'message' => 'Conta gravada com sucesso', 'milesAccount' => $milesAccount], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    /**
     * Update the specified miles account.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // Regras para atualização, por exemplo:
            'balance' => 'sometimes|numeric|min:0',
            'active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => 0, 'errors' => $validator->errors()], 422);
        }

        try {
            $milesAccount = $this->findMilesAccountOrFail($id);

            $milesAccount->update($request->only(['balance', 'active']));

            return response()->json(['success' => 1, 'message' => 'Conta alterada com sucesso', 'milesAccount' => $milesAccount], 200);
        } catch (Exception $e) {
            return $this->handleNotFoundException($e);
        }
    }

    /**
     * Soft delete (deactivate) the specified miles account.
     */
    public function destroy($id)
    {
        try {
            $milesAccount = $this->findMilesAccountOrFail($id);

            $milesAccount->update(['active' => 0]);

            return response()->json(['success' => 1, 'message' => 'Conta desativada com sucesso'], 200);
        } catch (Exception $e) {
            return $this->handleNotFoundException($e);
        }
    }

    /**
     * Helper method to find MilesAccount or throw Exception.
     */
    private function findMilesAccountOrFail($id)
    {
        $milesAccount = MilesAccount::find($id);

        if (!$milesAccount) {
            throw new Exception('Conta não encontrada', 404);
        }

        return $milesAccount;
    }

    /**
     * Handle not found exceptions.
     */
    private function handleNotFoundException(Exception $e)
    {
        if ($e->getCode() === 404) {
            return response()->json(['success' => 0, 'message' => $e->getMessage()], 404);
        }

        return $this->handleException($e);
    }

    /**
     * Handle general exceptions.
     */
    private function handleException(Exception $e)
    {
        return response()->json(['success' => 0, 'message' => $e->getMessage()], 500);
    }
}
