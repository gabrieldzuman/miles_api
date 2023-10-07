<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\api\Purchase;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de compras
    public function index()
    {
        $purchases = Purchase::all();
        return response()->json(["purchases" => $purchases], 200);
    }

    //funcao para buscar uma compra especifica 
    public function getPurchase($purchaseId)
    {
        try {
            $purchases = Purchase::where('id', $purchaseId)->first();
            if($purchases!=null){
                return $purchases;
            } else {
                return response()->json(['message'=>'Compra não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return($e->getMessage());
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePurchaseRequest $request
     * @return \Illuminate\Http\Response
     */

    //funcao para gravar compra
    public function store(Request $request)
    {
        try { 
            $purchases = Purchase::Create($request->all()); 
            return response()->json(['message'=>'Compra gravada', "purchase" => $purchases], 200);
    }      
        catch(Exception $err){ 

            return response()->json(['message'=>$err->getMessage(),'Erro'=>1], 200);  
        }  
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\api\Purchase $purchase
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
     * @param  \App\Http\Requests\UpdatePurchaseRequest $request
     * @param  \App\Models\api\Purchase $purchase
     * @return \Illuminate\Http\Response
     */

    //funcao para editar compra
    public function update(Request $request, $purchaseId)
    {
        try {
            $purchase = Purchase::where('id', $purchaseId)->first();
            if($purchase!=null){
                $response = $purchase->update($request->all());
                if($response == true){
                    return response()->json(['message'=>'Compra alterada com sucesso'], 200);
                } else {
                    return response()->json(['message'=>'Erro ao alterar compra'], 200);
                }
            } else {
                return response()->json(['message'=>'Compra não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    
    //funcao para excluir compra
    public function destroy($purchaseId)
    {
        try {
            $purchase = Purchase::where('id', $purchaseId)->first();
            if($purchase!=null){
                $response = $purchase->delete();
                if($response == true){
                    return response()->json(['message'=>'Compra deletada com sucesso'], 200);
                } else {
                    return response()->json(['message'=>'Erro ao deletar compra'], 200);
                }
            } else {
                return response()->json(['message'=>'Compra não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return($e->getMessage());
        }
    }
}