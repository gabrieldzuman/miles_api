<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\api\MilesSupplier;
use Illuminate\Http\Request;
use Exception;

class MilesSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de fornecedores
    public function index()
    {
        // $milesSuppliers = MilesSupplier::where('client_id', CLIENT_ID)->get();
        $milesSuppliers = MilesSupplier::all();
        return response()->json(["success" => 1, 'milesSuppliers' => $milesSuppliers], 200);
    }

    //função para resgatar token 
    public function accessToken()
    {
        dd('milesSupplier');
        return $this->hasMany('App\OauthAccessToken');
        
        // Auth::user()->token();
    }

    //funcao para buscar um fornecedor especifico 
    public function getMilesSupplier($milesSupplierId)
    {
        try {
            // $milesSuppliers = MilesSupplier::where('client_id', CLIENT_ID)->first();
            $milesSuppliers = MilesSupplier::where('id', $milesSupplierId)->first();
            if($milesSuppliers!=null){
                return response()->json(["success" => 1, 'milesSuppliers' => $milesSuppliers], 200);
            } else {
                return response()->json(["err" => 0, 'message'=>'Fornecedor não encontrado'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMilesSupplierRequest $request
     * @return \Illuminate\Http\Response
     */

    //funcao para gravar fornecedor 
    public function store(Request $request)
    {
        try{ 
            // $requestData['client_id'] = CLIENT_ID;
            $milesSuppliers = MilesSupplier::Create($request->all()); 
        return response()->json(["success" => 1, 'message'=>'Fornecedor gravado', "milesSupplier" => $milesSuppliers], 200);
        } catch(Exception $e){ 
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200); 
        }    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMilesSupplierRequest $request
     * @param  \App\Models\api\MilesSupplier $milesSupplier
     * @return \Illuminate\Http\Response
     */ 

    //funcao para editar fornecedor
    public function update(Request $request, $milesSupplierId)
    {
        try {
            // $milesSupplier = MilesSupplier::where('client_id', CLIENT_ID)->first();
            $milesSupplier = MilesSupplier::where('id', $milesSupplierId)->first();
            if($milesSupplier!=null){
                $response = $milesSupplier->update($request->all());
                if($response == true){
                    return response()->json(['success' => 1, 'message'=>'Fornecedor alterado com sucesso', 'milesSupplier' => $milesSupplier], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao alterar fornecedor'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Fornecedor não encontrado'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\MilesSupplier $milesSupplier
     * @return \Illuminate\Http\Response
     */
    
    //funcao para excluir fornecedor
    public function destroy($milesSupplierId)
    {   
        try {
            // $milesSupplier = MilesSupplier::where('client_id', CLIENT_ID)->first();
            $milesSupplier = MilesSupplier::where('id', $milesSupplierId)->first();
            if($milesSupplier!=null){
                $milesSupplier->active = false; $milesSupplier->save();
                if($milesSupplier == true){
                    return response()->json(["sucess" => 1, 'message'=>'Fornecedor desativado com sucesso'], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao desativar fornecedor'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Fornecedor não encontrado'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }
}