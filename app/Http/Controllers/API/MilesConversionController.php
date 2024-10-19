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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $milesConversions = MilesConversion::all();
            return response()->json([
                "success" => true, 
                "milesConversions" => $milesConversions
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "success" => false, 
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve a specific miles conversion record.
     *
     * @param int $milesQuotationId
     * @return \Illuminate\Http\Response
     */
    public function getMilesQuotation($milesQuotationId)
    {
        try {
            $milesConversion = MilesConversion::find($milesQuotationId);
            if ($milesConversion) {
                return response()->json([
                    "success" => true, 
                    "quotation" => $milesConversion
                ], 200);
            }
            return response()->json([
                "success" => false, 
                "message" => "Cotação não encontrada"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "success" => false, 
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created miles conversion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $milesConversion = MilesConversion::create($request->all());
            return response()->json([
                "success" => true, 
                "message" => "Conversão gravada com sucesso", 
                "milesConversion" => $milesConversion
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                "success" => false, 
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific miles conversion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $milesConversionId
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $milesConversionId)
    {
        try {
            $milesConversion = MilesConversion::find($milesConversionId);
            if ($milesConversion) {
                $milesConversion->update($request->all());
                return response()->json([
                    "success" => true, 
                    "message" => "Conversão atualizada com sucesso", 
                    "milesConversion" => $milesConversion
                ], 200);
            }
            return response()->json([
                "success" => false, 
                "message" => "Conversão não encontrada"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "success" => false, 
                "message" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Deactivate a specific miles conversion.
     *
     * @param  int $milesConversionId
     * @return \Illuminate\Http\Response
     */
    public function destroy($milesConversionId)
    {
        try {
            $milesConversion = MilesConversion::find($milesConversionId);
            if ($milesConversion) {
                $milesConversion->active = 0;
                $milesConversion->save();
                return response()->json([
                    "success" => true, 
                    "message" => "Conversão desativada com sucesso"
                ], 200);
            }
            return response()->json([
                "success" => false, 
                "message" => "Conversão não encontrada"
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                "success" => false, 
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
