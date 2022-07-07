<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    public function index()
    {
        return Warehouse::all();
    }

    public function getActive()
    {
        return Warehouse::query()->where('is_active', true)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Builder|JsonResponse|Model|object|null
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $warehouse = Warehouse::create([
            'name' => $request->name,
            'address' => $request->address
        ]);

        return Warehouse::query()->where('id', $warehouse->id)->first();
    }

    /**
     * Display the specified resource.
     *
     * @param Warehouse $warehouse
     * @return Warehouse
     */
    public function show(Warehouse $warehouse)
    {
        return $warehouse;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Warehouse $warehouse
     * @return Response
     */
    public function edit(Warehouse $warehouse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Warehouse $warehouse
     * @return Builder|Model|JsonResponse|object|null
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:64',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $warehouse->update([
            'name' => $request->name,
            'address' => $request->address
        ]);

        return Warehouse::query()->where('id', $warehouse->id)->first();
    }

    /**
     * Alter activity status for the specified resource in storage.
     * @return Builder|Model|object|null
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->update([
            'is_active' => !$warehouse->is_active,
        ]);

        return Warehouse::query()->where('id', $warehouse->id)->first();
    }
}
