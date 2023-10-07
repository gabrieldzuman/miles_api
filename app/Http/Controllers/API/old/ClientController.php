<?php
        
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\api\Client;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de clientes
    public function index()
    {
        $clients = Client::all();
        return response()->json(["clients" => $clients], 200);
    }

    //funcao para buscar um client especifico 
    public function getClient($clientId)
    {

        try {
            $clients = Client::where('id', $clientId)->first();
            if($clients!=null){
                return $clients;
            } else {
                return response()->json(['message'=>'Cliente não encontrado'], 200);
            }
        } catch (\Exception $e) {
            return($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClientRequest $request
     * @return \Illuminate\Http\Response
     */

    //funcao para gravar cliente
    public function store(Request $request)
    {
        try{ 
            $clients = Client::Create($request->all()); 
            return response()->json(['message'=>'Cliente gravado', "client" => $clients], 200);
    }          
        catch(Exception $err){ 
            return response()->json(['message'=>$err->getMessage(),'Erro'=>1], 200);
        }   
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\api\Client $client
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        return response('user.profile', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClientRequest $request
     * @param  \App\Models\api\Client $client
     * @return \Illuminate\Http\Response
     */

    //funcao para editar cliente
    public function update(Request $request, $clientId)
    {
        try {
            $client = Client::where('id', $clientId)->first();
            if($client!=null){
                $response = $client->update($request->all());
                if($response == true){
                    return response()->json(['message'=>'Cliente alterado com sucesso', "client" => $client], 200);
                } else {
                    return response()->json(['message'=>'Erro ao alterar cliente'], 200);
                }
            } else {
                return response()->json(['message'=>'Cliente não encontrado'], 200);
            }
        } catch (\Exception $e) {
            return($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\Client $client
     * @return \Illuminate\Http\Response
     */
    
    //funcao para excluir cliente
    public function destroy($clientId)
    {
        try {
            $client = Client::where('id', $clientId)->first();
            if($client!=null){
                $client->active = false; $client->save();
                if($client == true){
                    return response()->json(['message'=>'Cliente desativado com sucesso'], 200);
                } else {
                    return response()->json(['message'=>'Erro ao desativar cliente'], 200);
                }
            } else {
                return response()->json(['message'=>'Cliente não encontrado'], 200);
            }
        } catch (\Exception $e) {
            return($e->getMessage());
        }
    }
}