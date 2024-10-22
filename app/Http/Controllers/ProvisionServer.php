<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProvisionServer extends Controller
{
    /**
     * Handle the incoming request to provision a server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        try {
            $this->applyRateLimiting($request, 'provision-server');
            $this->logMessage('Provision server request received.', [
                'user_id' => $request->user()->id ?? 'guest',
                'request_data' => $request->all()
            ]);
            $provisioningResult = $this->provisionServerLogic($request);
            $this->logMessage('Server provisioned successfully.', [
                'user_id' => $request->user()->id ?? 'guest',
                'provisioning_result' => $provisioningResult
            ]);
            return $this->successResponse($provisioningResult, 'Server provisioned successfully.');

        } catch (\Exception $e) {
            Log::error('Server provisioning failed.', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id ?? 'guest',
            ]);
            return $this->errorResponse('An error occurred while provisioning the server.', 500);
        }
    }

    /**
     * Simulate the server provisioning logic.
     * Replace this method with actual server provisioning logic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function provisionServerLogic(Request $request): array
    {
        return [
            'server_id' => 'srv-123456',
            'status' => 'provisioned',
            'message' => 'Server has been successfully provisioned.'
        ];
    }
}
