<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\api\MilesAccount;
use Illuminate\Http\Request;
use Exception;
 
class MilesAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de contas
    public function index()
    {
        $milesAccounts = MilesAccount::get();
        // $milesAccounts = MilesAccount::where('client_id', CLIENT_ID)->get();
        return response()->json(["success" => 1, 'milesAccounts' => $milesAccounts], 200);
    }

    //função para resgatar token 
    public function accessToken()
    {
        dd(CLIENT_ID);
        return $this->hasMany('App\OauthAccessToken');
        
        // Auth::user()->token();
    }

    //funcao para buscar uma conta especifica 
    public function getMilesAccount($milesAccountId)
    {
        try {
            // $milesAccounts = MilesAccount::where('client_id', CLIENT_ID)->first();
            $milesAccounts = MilesAccount::where('id', $milesAccountId)->first();
            if($milesAccounts!=null){
                return response()->json(["success" => 1, 'milesAccounts' => $milesAccounts], 200);
            } else {
                return response()->json(["err" => 0, 'message'=>'Conta não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }               

        //funcao para buscar saldo especifico
        public function checkBalance($balanceId)
        {
            try {
                // $requestData['client_id'] = CLIENT_ID;
                $balance = MilesAccount::where('id', $balanceId)->get();
                if (count($balance) > 0){
                    return $balance;
                } else {
                    return response()->json(["success" => 1, 'message'=>'Saldo não encontrado'], 200);
                }
            } catch (\Exception $e) {
                return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
            }
        }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMilesAccountRequest $request
     * @return \Illuminate\Http\Response
     */

    //funcao para gravar uma conta
    public function store(Request $request)
    {
        try{ 
            // $requestData['client_id'] = CLIENT_ID;
            $milesAccounts = MilesAccount::Create($request->all()); 
        return response()->json(["success" => 1, 'message'=>'Conta gravada', 'milesAccount' => $milesAccounts], 200);
        } catch(Exception $e){     
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMilesAccountRequest $request
     * @param  \App\Models\api\MilesAccount $milesAccount
     * @return \Illuminate\Http\Response
     */

    //funcao para editar conta
    public function update(Request $request, $milesAccountId)
    {
        try {
            // $milesAccounts = MilesAccount::where('client_id', CLIENT_ID)->first();
            $milesAccount = MilesAccount::where('id', $milesAccountId)->first();
            if($milesAccount!=null){
                $response = $milesAccount->update($request->all());
                if($response == true){
                    return response()->json(["success" => 1, 'message'=>'Conta alterada com sucesso', 'milesAccount' => $milesAccount], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao alterar conta'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Conta não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\MilesAccount $milesAccount
     * @return \Illuminate\Http\Response
     */
    
    //funcao para excluir conta
    public function destroy($milesAccountId)
    {
        try {
            // $milesAccounts = MilesAccount::where('client_id', CLIENT_ID)->first();
            $milesAccount = MilesAccount::where('id', $milesAccountId)->first();
            if($milesAccount!=null){
                $milesAccount->active = 0; $milesAccount->save();
                if($milesAccount == true){
                    return response()->json(["success" => 1, 'message'=>'Conta desativada com sucesso'], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao desativar conta'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Conta não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }
}