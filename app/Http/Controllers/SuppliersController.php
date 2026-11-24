<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuppliersController extends Controller
{
    public function index(Request $request)
    {
        $suppliers = Supplier::orderBy('id', 'DESC')->paginate(10);
        return ApiResponse::send($request, $suppliers, 200, 'suppliers', 'supplier');
    }

    public function show(Request $request, $id)
    {
        $supplier = Supplier::with('materials')->find($id);
        if (!$supplier) {
            return ApiResponse::send($request, [
                'success' => false,
                'status' => 404,
                'message' => 'Supplier not found'
            ], 404, 'error');
        }
        return ApiResponse::send($request, $supplier, 200, 'supplier');
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => 'required|string|max:30|unique:suppliers,code',
            'company_name' => 'required|string|max:120',
            'contact_person' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:suppliers,email',
            'phone' => 'required|string|max:25',
            'address' => 'required|string',
            'city' => 'required|string|max:60',
            'province' => 'required|string|max:60',
            'npwp' => 'nullable|string|max:30',
            'status' => 'required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ApiResponse::send($request, $validator->errors()->toArray(), 400, 'errors', 'error');
        }

        $supplier = Supplier::create($request->all());
        return ApiResponse::send($request, $supplier, 201, 'supplier');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return ApiResponse::send($request, [
                'success' => false,
                'status' => 404,
                'message' => 'Supplier not found'
            ], 404, 'error');
        }

        $rules = [
            'code' => 'sometimes|required|string|max:30|unique:suppliers,code,' . $id,
            'company_name' => 'sometimes|required|string|max:120',
            'contact_person' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100|unique:suppliers,email,' . $id,
            'phone' => 'sometimes|required|string|max:25',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:60',
            'province' => 'sometimes|required|string|max:60',
            'npwp' => 'nullable|string|max:30',
            'status' => 'sometimes|required|in:active,inactive',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ApiResponse::send($request, $validator->errors()->toArray(), 400, 'errors', 'error');
        }

        $supplier->fill($request->all());
        $supplier->save();

        return ApiResponse::send($request, $supplier, 200, 'supplier');
    }

    public function destroy(Request $request, $id)
    {
        $supplier = Supplier::find($id);
        if (!$supplier) {
            return ApiResponse::send($request, [
                'success' => false,
                'status' => 404,
                'message' => 'Supplier not found'
            ], 404, 'error');
        }

        $supplier->delete();
        return ApiResponse::send($request, [
            'message' => 'deleted successfully',
            'supplier_id' => (int) $id
        ], 200, 'result');
    }
}
