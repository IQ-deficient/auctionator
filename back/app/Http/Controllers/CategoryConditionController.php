<?php

namespace App\Http\Controllers;

use App\Models\CategoryCondition;
use App\Http\Requests\StoreCategoryConditionRequest;
use App\Http\Requests\UpdateCategoryConditionRequest;
use Illuminate\Http\Request;

class CategoryConditionController extends Controller
{

    public function getConditionsForCategory(Request $request)
    {
        // This method should be used to return all active conditions that exist for the requested category from this mt
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param \App\Http\Requests\StoreCategoryConditionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryConditionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\CategoryCondition $categoryCondition
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryCondition $categoryCondition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\CategoryCondition $categoryCondition
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryCondition $categoryCondition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryConditionRequest $request
     * @param \App\Models\CategoryCondition $categoryCondition
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryConditionRequest $request, CategoryCondition $categoryCondition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\CategoryCondition $categoryCondition
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryCondition $categoryCondition)
    {
        //
    }
}
