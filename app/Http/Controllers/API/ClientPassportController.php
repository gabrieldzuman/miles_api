<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Validation\ValidationException;

class ClientPassportController extends Controller
{
    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected ClientRepository $clients;

    /**
     * The validation factory implementation.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected Factory $validation;

    /**
     * Create a client controller instance.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Illuminate\Contracts\Validation\Factory  $validation
     */
    public function __construct(
        ClientRepository $clients,
        Factory $validation
    ) {
        $this->clients = $clients;
        $this->validation = $validation;
    }

    /**
     * Store a new client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validateClient($request);
            
            $client = $this->clients->create(
                null, 
                $request->input('name'),
                '', 
                '', 
                false, 
                false, 
                (bool) $request->input('confidential', true)
            );

            $client->makeVisible('secret');

            return response()->json([
                'success' => true,
                'client_id' => $client->id,
                'client_name' => $client->name,
                'client_secret' => $client->secret,
            ], 201); 

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors(),
            ], 422); 
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar o client',
                'error' => $e->getMessage(),
            ], 500); 
        }
    }

    /**
     * Validate the client creation request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateClient(Request $request): void
    {
        $this->validation->make($request->all(), [
            'name' => 'required|string|max:191|unique:oauth_clients,name',
            'confidential' => 'boolean',
        ], [
            'name.unique' => __('Este nome de cliente já existe.'),
        ])->validate();
    }
}
