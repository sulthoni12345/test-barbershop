<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceApiController extends Controller
{
    // GET /api/services
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Service::all()
        ]);
    }

    // POST /api/services
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric'
        ]);

        $service = Service::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Service berhasil ditambahkan',
            'data' => $service
        ], 201);
    }

    // GET /api/services/{id}
    public function show($id)
    {
        return response()->json(Service::findOrFail($id));
    }

    // PUT /api/services/{id}
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        $service->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Service berhasil diupdate',
            'data' => $service
        ]);
    }

    // DELETE /api/services/{id}
    public function destroy($id)
    {
        Service::destroy($id);

        return response()->json([
            'status' => true,
            'message' => 'Service berhasil dihapus'
        ]);
    }
}