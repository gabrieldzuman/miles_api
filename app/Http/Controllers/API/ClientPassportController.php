<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Passport\ClientRepository;
use Illuminate\Contracts\Validation\Factory;

class ClientPassportController extends Controller
{
    /**
     * The client repository instance.
     *
     * @var \Laravel\Passport\ClientRepository
     */
    protected $clients;

    /**
     * The validation factory implementation.
     *
     * @var \Illuminate\Contracts\Validation\Factory
     */
    protected $validation;

    /**
     * Create a client controller instance.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @param  \Illuminate\Contracts\Validation\Factory  $validation
     * @return void
     */
    public function __construct(
        ClientRepository $clients,
        Factory $validation,
    ) {
        $this->clients = $clients;
        $this->validation = $validation;
    }

    /**
     * Store a new client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Passport\Client|array
     */
    public function store(Request $request)
    {
        $this->validation->make($request->all(), [
            'name' => 'required|max:191|unique:oauth_clients,name',
            'confidential' => 'boolean',
        ],[
            'name.unique' => "Este nome de client jÃ¡ existe"
        ])->validate();

        $client = $this->clients->create(
            null,
            $request->name,
            false,
            false,
            false,
            false,
            (bool) $request->input('confidential', true)
        );

        $response = $client->makeVisible('secret');
        return response(['client_id' => $response['id'], 'client_name' => $response['name'], 'client_secret' => $response['secret']]);
    }
}