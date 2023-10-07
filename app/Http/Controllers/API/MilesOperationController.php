<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\MilesAccount;
use App\Models\api\MilesOperation;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class MilesOperationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de operacao
    public function index()
    {
        // $milesOperations = MilesOperation::where('client_id', CLIENT_ID)->get();
        $milesOperations = MilesOperation::all();
        return response()->json(["err" => 1, 'milesOperations' => $milesOperations], 200);
    }

    //função para resgatar token 
    public function accessToken()
    {
        dd('milesOperation');
        return $this->hasMany('App\OauthAccessToken');
        
        // Auth::user()->token();
    }
 
    //funcao para buscar uma operacao especifica
    public function getMilesOperation($milesOperationId)
    {
        try {
            // $milesOperations = MilesOperation::where('client_id', CLIENT_ID)->first();
            $milesOperations = MilesOperation::where('id', $milesOperationId)->first();
            if($milesOperations!=null){    
                return response()->json(["success" => 1, 'milesOperations' => $milesOperations], 200);                       
            } else {
                return response()->json(["err" => 0, 'message'=>'Operação não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    //funcao para debitar pontos
    public function milesOperation(Request $request)
    {   
        try{ 
            // $requestData['client_id'] = CLIENT_ID;
            $requestData = $request->all(); 
            $milesAccount = MilesAccount::find($requestData['miles_account_id']);
            DB::beginTransaction(); 
            $milesAccount->miles_accounts_balance = $milesAccount->miles_accounts_balance - $requestData['value'];
            $milesAccountResponse = $milesAccount->save();
            $milesOperation = new MilesOperation;
            $milesOperation->miles_operation_amount = $requestData['value'];
            $milesOperation->miles_operation_type = 'debito';
            $milesOperation->miles_account_id = $milesAccount->id;
            $milesOperation->active = 1;
            $milesOperation->quotation = 
            $milesOperationResponse = $milesOperation->save();
            if ($milesAccountResponse && $milesOperationResponse){
            DB::commit();
                    return response()->json(["err" => 1, 'message'=>'Resgate com sucesso'], 200);
                } else {    
                    return response()->json(["err" => 0, 'message'=>'Erro no resgate'], 200);
                }
        }       
        catch(Exception $e){ 
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMilesOperationRequest $request
     * @param  \App\Models\api\MilesOperation $milesOperation
     * @return \Illuminate\Http\Response
     */

    //funcao para editar operacao
    public function update(Request $request, $milesOperationId)
    {
        try {
            // $milesOperation = MilesOperation::where('client_id', CLIENT_ID)->first();
            $milesOperation = MilesOperation::where('id', $milesOperationId)->first();
            if($milesOperation!=null){
                $response = $milesOperation->update($request->all());
                if($response == true){
                    return response()->json(["success" => 1, 'message'=>'Operação alterada com sucesso', 'milesOperation' => $milesOperation], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao alterar operação'], 200);
                }
            } else {
             return response()->json(["err" => 0, 'message'=>'Operação não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\MilesOperation $milesOperation
     * @return \Illuminate\Http\Response
     */
    
    //funcao para excluir operacao
    public function destroy($milesOperationId)
    {
        try {
            // $milesOperation = MilesOperation::where('client_id', CLIENT_ID)->first();
            $milesOperation = MilesOperation::where('id', $milesOperationId)->first();
            if($milesOperation!=null){
                $milesOperation->active = 0; $milesOperation->save();
                if($milesOperation == true){
                    return response()->json(["success" => 1, 'message'=>'Operação desativada com sucesso'], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao desativar operação'], 200);
                }
            } else {
             return response()->json(["err" => 0, 'message'=>'Operação não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }
}