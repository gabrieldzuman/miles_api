<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\MilesSupplier;
use Illuminate\Http\Request;
use Exception;

class MilesSupplierController extends Controller
{
    public function index()
    {
        try {
            $milesSuppliers = MilesSupplier::all();
            return $this->successResponse(['milesSuppliers' => $milesSuppliers]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    // Essa função parece fora do contexto do controller e está incorreta aqui
    // public function accessToken()
    // {
    //     return $this->hasMany('App\OauthAccessToken');
    // }

    public function getMilesSupplier($milesSupplierId)
    {
        try {
            $milesSupplier = MilesSupplier::find($milesSupplierId);
            if (!$milesSupplier) {
                return $this->notFoundResponse('Fornecedor não encontrado');
            }
            return $this->successResponse(['milesSupplier' => $milesSupplier]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $milesSupplier = MilesSupplier::create($request->all());
            return $this->successResponse(['milesSupplier' => $milesSupplier], 'Fornecedor gravado com sucesso', 201);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(Request $request, $milesSupplierId)
    {
        try {
            $milesSupplier = MilesSupplier::find($milesSupplierId);
            if (!$milesSupplier) {
                return $this->notFoundResponse('Fornecedor não encontrado');
            }

            $milesSupplier->update($request->all());

            return $this->successResponse(['milesSupplier' => $milesSupplier], 'Fornecedor alterado com sucesso');
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function destroy($milesSupplierId)
    {
        try {
            $milesSupplier = MilesSupplier::find($milesSupplierId);
            if (!$milesSupplier) {
                return $this->notFoundResponse('Fornecedor não encontrado');
            }

            $milesSupplier->active = false;
            $milesSupplier->save();

            return $this->successResponse([], 'Fornecedor desativado com sucesso');
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    private function successResponse(array $data = [], string $message = '', int $statusCode = 200)
    {
        $response = ['success' => true];
        if ($message) {
            $response['message'] = $message;
        }
        return response()->json(array_merge($response, $data), $statusCode);
    }

    private function notFoundResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    private function errorResponse(Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
