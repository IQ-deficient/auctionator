<?php

namespace App\Http\Controllers;

use App\Models\Condition;
use App\Http\Requests\StoreConditionRequest;
use App\Http\Requests\UpdateConditionRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class ConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Collection|Condition[]
     */
    public function index()
    {
        return Condition::all();
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
     *
     * @param  \App\Http\Requests\StoreConditionRequest  $request
     * @return Response
     */
    public function store(StoreConditionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Condition  $condition
     * @return Response
     */
    public function show(Condition $condition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Condition  $condition
     * @return Response
     */
    public function edit(Condition $condition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateConditionRequest  $request
     * @param  \App\Models\Condition  $condition
     * @return Response
     */
    public function update(UpdateConditionRequest $request, Condition $condition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Condition  $condition
     * @return Response
     */
    public function destroy(Condition $condition)
    {
        //
    }
}
