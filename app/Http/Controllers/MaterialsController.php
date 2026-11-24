<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::with('supplier')->orderBy('id', 'DESC');

        // Filter berdasarkan supplier_id (main table) jika dikirimkan
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->query('supplier_id'));
        }

        $materials = $query->paginate(10);
        return ApiResponse::send($request, $materials, 200, 'materials', 'material');
    }

    public function show(Request $request, $id)
    {
        $material = Material::with('supplier')->find($id);
        if (!$material) {
            return ApiResponse::send($request, [
                'success' => false,
                'status' => 404,
                'message' => 'Material not found'
            ], 404, 'error');
        }
        return ApiResponse::send($request, $material, 200, 'material');
    }

    public function store(Request $request)
    {
        $rules = [
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'sku' => 'required|string|max:40|unique:materials,sku',
            'name' => 'required|string|max:120',
            'category' => 'required|string|max:80',
            'unit' => 'required|string|max:30',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ApiResponse::send($request, $validator->errors()->toArray(), 400, 'errors', 'error');
        }

        $material = Material::create($request->all());
        return ApiResponse::send($request, $material, 201, 'material');
    }

    public function update(Request $request, $id)
    {
        $material = Material::find($id);
        if (!$material) {
            return ApiResponse::send($request, [
                'success' => false,
                'status' => 404,
                'message' => 'Material not found'
            ], 404, 'error');
        }

        $rules = [
            'supplier_id' => 'sometimes|required|integer|exists:suppliers,id',
            'sku' => 'sometimes|required|string|max:40|unique:materials,sku,' . $id,
            'name' => 'sometimes|required|string|max:120',
            'category' => 'sometimes|required|string|max:80',
            'unit' => 'sometimes|required|string|max:30',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ApiResponse::send($request, $validator->errors()->toArray(), 400, 'errors', 'error');
        }

        $material->fill($request->all());
        $material->save();

        return ApiResponse::send($request, $material, 200, 'material');
    }

    public function destroy(Request $request, $id)
    {
        $material = Material::find($id);
        if (!$material) {
            return ApiResponse::send($request, [
                'success' => false,
                'status' => 404,
                'message' => 'Material not found'
            ], 404, 'error');
        }

        $material->delete();
        return ApiResponse::send($request, [
            'message' => 'deleted successfully',
            'material_id' => (int) $id
        ], 200, 'result');
    }
}
