<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Validation\ValidationException;

class ClientPassportController extends Controller
{
    protected ClientRepository $clients;
    protected ValidationFactory $validation;

    public function __construct(ClientRepository $clients, ValidationFactory $validation)
    {
        $this->clients = $clients;
        $this->validation = $validation;
    }

    /**
     * Armazena um novo client.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $this->validateClient($request);

            $client = $this->clients->create(
                userId: null,
                name: $request->input('name'),
                redirect: '',
                personalAccessClient: false,
                passwordClient: false,
                revoked: false,
                confidential: (bool) $request->input('confidential', true)
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
     * Valida a requisição de criação do client.
     *
     * @throws ValidationException
     */
    protected function validateClient(Request $request): void
    {
        $this->validation->make(
            $request->all(),
            [
                'name' => 'required|string|max:191|unique:oauth_clients,name',
                'confidential' => 'boolean',
            ],
            [
                'name.unique' => __('Este nome de cliente já existe.'),
            ]
        )->validate();
    }
}
