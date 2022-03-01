<?php

namespace App\Http\Controllers;

use App\Models\CategoryCondition;
use App\Http\Requests\StoreCategoryConditionRequest;
use App\Http\Requests\UpdateCategoryConditionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryConditionController extends Controller
{

    public function getConditionsForCategory(Request $request)
    {
        // This method should be used to return all active conditions that exist for the requested category
        $validator = Validator::make($request->all(), [
            'category' => 'required|exists:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

//        return collect(DB::select("
//            select `condition`
//            from category_conditions
//            where category = ?
//            ", [$request->category])
//        );

        $category = DB::table('categories')
            ->where('is_active', true)
            ->where('name', $request->category)
            ->first();

        $parent_category = DB::table('categories')
            ->where('is_active', true)
            ->where('id', $category->master_category_id)
            ->pluck('name');

        // Find all available conditions that requested category can be represented as
        return DB::table('category_conditions')
            ->where('category', $parent_category)
            ->pluck('condition');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CategoryCondition::all();
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
