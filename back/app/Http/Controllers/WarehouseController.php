<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

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

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'address' => $request->address,
            'updated_at' => Carbon::now()
        ]);

        return Warehouse::where('id', $warehouse->id)->first();
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->update([
            'is_active' => !$warehouse->is_active,
        ]);

        return Warehouse::where('id', $warehouse->id)->first();
    }
}
