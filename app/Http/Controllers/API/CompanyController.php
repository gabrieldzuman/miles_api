<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Exception;

class CompanyController extends Controller
{
    /**
     * Display a listing of companies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $companies = Company::all();

            return response()->json([
                'success' => true,
                'companies' => $companies
            ], 200);
        } catch (Exception $e) {
            Log::error('Error fetching companies: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching companies.'
            ], 500);
        }
    }

    /**
     * Retrieve an access token (dummy example).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accessToken()
    {
        try {
            $token = 'sample_token';

            return response()->json([
                'success' => true,
                'token' => $token
            ], 200);
        } catch (Exception $e) {
            Log::error('Error fetching access token: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching access token.'
            ], 500);
        }
    }

    /**
     * Display the specified company by ID.
     *
     * @param int $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($companyId)
    {
        try {
            $company = Company::find($companyId);

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'company' => $company
            ], 200);
        } catch (Exception $e) {
            Log::error('Error fetching company: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error fetching company.'
            ], 500);
        }
    }

    /**
     * Store a newly created company.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $company = Company::create($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Company created successfully.',
                'company' => $company
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating company: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating company.'
            ], 500);
        }
    }

    /**
     * Update the specified company.
     *
     * @param Request $request
     * @param int $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $companyId)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $company = Company::find($companyId);

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found.'
                ], 404);
            }

            $company->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully.',
                'company' => $company
            ], 200);
        } catch (Exception $e) {
            Log::error('Error updating company: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error updating company.'
            ], 500);
        }
    }

    /**
     * Soft delete the specified company.
     *
     * @param int $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($companyId)
    {
        try {
            $company = Company::find($companyId);

            if (!$company) {
                return response()->json([
                    'success' => false,
                    'message' => 'Company not found.'
                ], 404);
            }

            $company->update(['active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Company deactivated successfully.'
            ], 200);
        } catch (Exception $e) {
            Log::error('Error deactivating company: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error deactivating company.'
            ], 500);
        }
    }
}
