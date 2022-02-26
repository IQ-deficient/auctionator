<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use Carbon\Carbon;
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
        return Warehouse::where('is_active', true)->get();
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
     * @return mixed
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

        return Warehouse::where('id', $warehouse->id)->first();
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Warehouse $warehouse
     * @return Response
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Warehouse $warehouse
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
     * @return mixed
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
            'address' => $request->address,
            'updated_at' => Carbon::now()
        ]);

        return Warehouse::where('id', $warehouse->id)->first();
    }

    /**
     * Alter activity status for the specified resource in storage.
     * @return mixed
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->update([
            'is_active' => !$warehouse->is_active,
        ]);

        return Warehouse::where('id', $warehouse->id)->first();
    }
}
