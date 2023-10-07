<?php

namespace App\Http\Controllers\Api;
 
use App\Http\Controllers\Controller;
use App\Models\api\MilesConversion;
use Illuminate\Http\Request;
use Exception;

class MilesConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de conversao
    public function index()
    {
        // $milesConversion = MilesConversion::where('client_id', CLIENT_ID)->get();
        $milesConversions = MilesConversion::all();
        return response()->json(["success" => 1, 'milesConversions' => $milesConversions], 200);
    }

    //funcao para buscar uma conversao especifica 
    public function getMilesQuotation($milesQuotationId)
    {
        try {
            // $milesConversion = MilesConversion::where('client_id', CLIENT_ID)->first();
            $milesConversions = MilesConversion::where('id', $milesQuotationId)->first();
            if($milesConversions!=null){
                return response()->json(["success" => 1, 'quotation' => $milesConversions], 200);
            } else {
                return response()->json(["err" => 0, 'message'=>'Cotação não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMilesConversionRequest $request
     * @return \Illuminate\Http\Response
     */

    //funcao para gravar conversao
    public function store(Request $request)
    {
        try{ 
            // $requestData['client_id'] = CLIENT_ID;
            $milesConversions = MilesConversion::Create($request->all()); 
        return response()->json(["success" => 1, 'message'=>'Conversao gravada', 'milesConversion' => $milesConversions], 200);
    } catch(Exception $e){ 
        return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
    } 
}

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMilesConversionRequest $request
     * @param  \App\Models\api\MilesConversion $milesConversion
     * @return \Illuminate\Http\Response
     */

    //funcao para editar conversao
    public function update(Request $request, $milesConversionId)
    {
        try {
            // $milesConversion = MilesConversion::where('client_id', CLIENT_ID)->first();
            $milesConversion = MilesConversion::where('id', $milesConversionId)->first();
            if($milesConversion!=null){
                $response = $milesConversion->update($request->all());
                if($response == true){
                    return response()->json(["success" => 1, 'message'=>'Conversão alterada com sucesso', 'milesConversion' => $milesConversion], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao alterar conversão'], 200);
                }
            } else {
                    return response()->json(["err" => 0, 'message'=>'Conversão não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\MilesConversion $milesConversion
     * @return \Illuminate\Http\Response
     */
    
    //funcao para excluir conversao
    public function destroy($milesConversionId)
    {
        try {
            // $milesConversion = MilesConversion::where('client_id', CLIENT_ID)->first();
            $milesConversion = MilesConversion::where('id', $milesConversionId)->first();
            if($milesConversion!=null){
                $milesConversion->active = 0; $milesConversion->save();
                if($milesConversion == true){
                    return response()->json(["success" => 1, 'message'=>'Conversão desativada com sucesso'], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao desativar conversão'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Conversão não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }
}