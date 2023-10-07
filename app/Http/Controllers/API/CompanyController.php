<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\api\Company;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Api\LogAPI;
use App\Models\V1\LogError;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //funcao para buscar lista de companhias
    public function index()
    { 
        try {
            $companies = Company::get();
            // $companies = Company::where('client_id', CLIENT_ID)->get();
            return response()->json(["success" => 1, 'companies' => $companies], 200);
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }       

    //função para resgatar token 
    public function accessToken()
    {
		try {
			// 	$logStatus = 'fail';
			// 	LogAPI::create([
			// 		'log_api_user_id' =>CLIENT_ID,
			// 		'log_api_class' => end($this->className),
			// 		'log_api_action' => 'searchAll',
			// 		'log_api_request' => REQUEST_PAYLOAD,
			// 		'log_api_response' => json_encode($return),
			// 		'log_api_route' => ROUTE_API,
			// 		'log_api_status' => $logStatus,
			// 		'log_api_ip' => ORIGIN_IP
			// 	]);
			// 	return $return;
			// } catch (\Exception $e) {
            // return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);

            if (empty($allServices)) {
                DB::rollback();
                $return = ['sucess' => false, 'message' => 'error(04), resource not available', 'code' => 404];
                $statusLog = 'fail';
                LogError::create([
                    'erro_user_id' => CLIENT_ID,
                    'erro_ip' => ORIGIN_IP,
                    'erro_message' => $return['message'],
                    'erro_code' => $return['code'],
                    'erro_file' => __FILE__,
                    'erro_line' => __LINE__,
                    'erro_route' => ROUTE_API,
                ]);
            } else {
                $return = ['sucess' => true, 'model' => $allServices];
                $statusLog = 'sucess';
            }
            LogAPI::create([
                'log_api_user_id' =>CLIENT_ID,
                'log_api_class' => end($this->className),
                'log_api_action' => 'createPurchase',
                'log_api_request' => REQUEST_PAYLOAD,
                'log_api_response' => json_encode($return),
                'log_api_route' => ROUTE_API,
                'log_api_status' => $statusLog,
                'log_api_ip' => ORIGIN_IP
            ]);
            return $return;
        } catch (\Exception $e) {
        return $this->handlerException($e);
    }
}

    //funcao para buscar uma companhia especifica
    public function getCompany($companyId)
    {   
        try {            
            // $companies = Company::where('client_id', CLIENT_ID)->first();
            $companies = Company::where('id', $companyId)->first();
            if($companies!=null){
                return response()->json(["success" => 1, 'companies' => $companies], 200);
            } else {
                return response()->json(["err" => 0, 'message'=>'Companhia não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }       

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompanyRequest $request
     * @return \Illuminate\Http\Response
     */

    //funcao para gravar companhia
    public function store(Request $request)
    {     
        try{ 
            // $requestData['client_id'] = CLIENT_ID;
            $requestData = $request->all();
            $companies = Company::Create($requestData); 
            return response()->json(["success" => 1, 'message'=>'Companhia gravada', 'company' => $companies], 200);        
        } catch(Exception $e){ 
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompanyRequest $request
     * @param  \App\Models\api\Company $company
     * @return \Illuminate\Http\Response
     */
     
    //funcao para editar companhia
    public function update(Request $request, $companyId)
    {
        try {
            // $company = Company::where('client_id', CLIENT_ID)->first();
            $company = Company::where('id', $companyId)->first();
            if($company!=null){
                $response = $company->update($request->all());
                if($response == true){
                    $company = $this->getCompany($companyId);
                    $company = (json_decode($company->getContent())->companies);
                    return response()->json(["success" => 1 , 'message'=>'Companhia alterada com sucesso', 'company' => $company], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao alterar companhia'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Companhia não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\api\Company $company
     * @return \Illuminate\Http\Response
     */

    //funcao para excluir companhia
    public function destroy($companyId)
    {   
        try {
            // $company = Company::where('client_id', CLIENT_ID)->first();
            $company = Company::where('id', $companyId)->first();
            if($company!=null){
                $company->active = 0; 
                $company->save();
                if($company == true){
                    return response()->json(["success" => 1, 'message'=>'Companhia desativada com sucesso'], 200);
                } else {
                    return response()->json(["err" => 0, 'message'=>'Erro ao desativar companhia'], 200);
                }
            } else {
                return response()->json(["err" => 0, 'message'=>'Companhia não encontrada'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(["err" => 0, 'message' => $e->getMessage()], 200);
        }
    }
}