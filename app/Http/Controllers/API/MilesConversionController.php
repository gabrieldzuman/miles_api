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
     */
    public function index()
    {
        try {
            $milesConversions = MilesConversion::all();
            return $this->successResponse(['milesConversions' => $milesConversions]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Retrieve a specific miles conversion record.
     */
    public function getMilesQuotation($milesQuotationId)
    {
        try {
            $milesConversion = MilesConversion::find($milesQuotationId);
            if (!$milesConversion) {
                return $this->notFoundResponse('Cotação não encontrada');
            }
            return $this->successResponse(['quotation' => $milesConversion]);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Store a newly created miles conversion.
     */
    public function store(Request $request)
    {
        try {
            $milesConversion = MilesConversion::create($request->all());
            return $this->successResponse(
                ['milesConversion' => $milesConversion],
                'Conversão gravada com sucesso',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Update a specific miles conversion.
     */
    public function update(Request $request, $milesConversionId)
    {
        try {
            $milesConversion = MilesConversion::find($milesConversionId);
            if (!$milesConversion) {
                return $this->notFoundResponse('Conversão não encontrada');
            }

            $milesConversion->update($request->all());

            return $this->successResponse(
                ['milesConversion' => $milesConversion],
                'Conversão atualizada com sucesso'
            );
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Deactivate a specific miles conversion.
     */
    public function destroy($milesConversionId)
    {
        try {
            $milesConversion = MilesConversion::find($milesConversionId);
            if (!$milesConversion) {
                return $this->notFoundResponse('Conversão não encontrada');
            }

            $milesConversion->active = 0;
            $milesConversion->save();

            return $this->successResponse([], 'Conversão desativada com sucesso');
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    /**
     * Return a successful JSON response.
     */
    private function successResponse(array $data = [], string $message = '', int $statusCode = 200)
    {
        $response = ['success' => true];
        if ($message) {
            $response['message'] = $message;
        }
        return response()->json(array_merge($response, $data), $statusCode);
    }

    /**
     * Return a not found JSON response.
     */
    private function notFoundResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 404);
    }

    /**
     * Return an error JSON response.
     */
    private function errorResponse(Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
