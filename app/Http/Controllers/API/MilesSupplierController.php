<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\api\MilesSupplier;
use Illuminate\Http\Request;
use Exception;

class MilesSupplierController extends Controller
{
    /**
     * Exibe uma lista de fornecedores.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $milesSuppliers = MilesSupplier::all();
            return response()->json(["success" => true, 'milesSuppliers' => $milesSuppliers], 200);
        } catch (Exception $e) {
            return response()->json(["success" => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Função para resgatar o token (Exemplo).
     */
    public function accessToken()
    {
        return $this->hasMany('App\OauthAccessToken');
    }

    /**
     * Retorna um fornecedor específico pelo ID.
     *
     * @param int $milesSupplierId
     * @return \Illuminate\Http\Response
     */
    public function getMilesSupplier($milesSupplierId)
    {
        try {
            $milesSupplier = MilesSupplier::find($milesSupplierId);
            if ($milesSupplier) {
                return response()->json(["success" => true, 'milesSupplier' => $milesSupplier], 200);
            } else {
                return response()->json(["success" => false, 'message' => 'Fornecedor não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Armazena um novo fornecedor.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $milesSupplier = MilesSupplier::create($request->all());
            return response()->json(["success" => true, 'message' => 'Fornecedor gravado com sucesso', "milesSupplier" => $milesSupplier], 201);
        } catch (Exception $e) {
            return response()->json(["success" => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Atualiza as informações de um fornecedor.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $milesSupplierId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $milesSupplierId)
    {
        try {
            $milesSupplier = MilesSupplier::find($milesSupplierId);
            if ($milesSupplier) {
                $milesSupplier->update($request->all());
                return response()->json(["success" => true, 'message' => 'Fornecedor alterado com sucesso', 'milesSupplier' => $milesSupplier], 200);
            } else {
                return response()->json(["success" => false, 'message' => 'Fornecedor não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Desativa um fornecedor.
     *
     * @param int $milesSupplierId
     * @return \Illuminate\Http\Response
     */
    public function destroy($milesSupplierId)
    {
        try {
            $milesSupplier = MilesSupplier::find($milesSupplierId);
            if ($milesSupplier) {
                $milesSupplier->active = false;
                $milesSupplier->save();
                return response()->json(["success" => true, 'message' => 'Fornecedor desativado com sucesso'], 200);
            } else {
                return response()->json(["success" => false, 'message' => 'Fornecedor não encontrado'], 404);
            }
        } catch (Exception $e) {
            return response()->json(["success" => false, 'message' => $e->getMessage()], 500);
        }
    }
}
