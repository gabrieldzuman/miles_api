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
     */
    public function index()
    {
        try {
            $companies = Company::all();

            return response()->json([
                'success' => true,
                'companies' => $companies
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching companies: {$e->getMessage()}");

            return $this->errorResponse('Error fetching companies.');
        }
    }

    /**
     * Retrieve an access token (dummy example).
     */
    public function accessToken()
    {
        try {
            $token = 'sample_token';

            return response()->json([
                'success' => true,
                'token' => $token
            ]);
        } catch (Exception $e) {
            Log::error("Error fetching access token: {$e->getMessage()}");

            return $this->errorResponse('Error fetching access token.');
        }
    }

    /**
     * Display the specified company by ID.
     */
    public function show(int $companyId)
    {
        $company = $this->findCompany($companyId);
        if (!$company) {
            return $this->notFoundResponse();
        }

        return response()->json([
            'success' => true,
            'company' => $company,
        ]);
    }

    /**
     * Store a newly created company.
     */
    public function store(Request $request)
    {
        $validation = $this->validateCompany($request->all());
        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->errors());
        }

        DB::beginTransaction();
        try {
            $company = Company::create($request->only(['name', 'address', 'email']));
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Company created successfully.',
                'company' => $company,
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error creating company: {$e->getMessage()}");

            return $this->errorResponse('Error creating company.');
        }
    }

    /**
     * Update the specified company.
     */
    public function update(Request $request, int $companyId)
    {
        $validation = $this->validateCompany($request->all());
        if ($validation->fails()) {
            return $this->validationErrorResponse($validation->errors());
        }

        $company = $this->findCompany($companyId);
        if (!$company) {
            return $this->notFoundResponse();
        }

        try {
            $company->update($request->only(['name', 'address', 'email']));

            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully.',
                'company' => $company,
            ]);
        } catch (Exception $e) {
            Log::error("Error updating company: {$e->getMessage()}");

            return $this->errorResponse('Error updating company.');
        }
    }

    /**
     * Soft delete (deactivate) the specified company.
     */
    public function destroy(int $companyId)
    {
        $company = $this->findCompany($companyId);
        if (!$company) {
            return $this->notFoundResponse();
        }

        try {
            $company->update(['active' => false]);

            return response()->json([
                'success' => true,
                'message' => 'Company deactivated successfully.',
            ]);
        } catch (Exception $e) {
            Log::error("Error deactivating company: {$e->getMessage()}");

            return $this->errorResponse('Error deactivating company.');
        }
    }

    /**
     * Validate company data.
     */
    protected function validateCompany(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
        ]);
    }

    /**
     * Find a company by ID.
     */
    protected function findCompany(int $companyId): ?Company
    {
        return Company::find($companyId);
    }

    /**
     * Return a not found response.
     */
    protected function notFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Company not found.'
        ], 404);
    }

    /**
     * Return a validation error response.
     */
    protected function validationErrorResponse($errors)
    {
        return response()->json([
            'success' => false,
            'errors' => $errors
        ], 422);
    }

    /**
     * Return a generic error response.
     */
    protected function errorResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 500);
    }
}
