<?php

namespace App\Http\Controllers;

use App\Models\Gender;
use App\Http\Requests\StoreGenderRequest;
use App\Http\Requests\UpdateGenderRequest;
use Illuminate\Database\Eloquent\Collection;

class GenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Gender[]
     */
    public function index()
    {
        return Gender::all();
    }

    public function getActive()
    {
        return Gender::query()->where('is_active', true)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGenderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenderRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function show(Gender $gender)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function edit(Gender $gender)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGenderRequest  $request
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenderRequest $request, Gender $gender)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gender  $gender
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gender $gender)
    {
        //
    }
}
