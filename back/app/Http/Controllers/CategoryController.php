<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    /**
     * Fetch all active parent (core/master) categories.
     * @return Collection
     */
    public function getParentCategories()
    {
        return DB::table('categories')
            ->where('is_active', true)
            ->where('master_category_id', null)
            ->get();
    }

    /**
     * Fetch all active sub-categories (child) and conditions for given master category.
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function getChildCategoriesAndConditions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|exists:categories,name'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // todo if given category is not a Mater Category return error

        // Get an object of master category based on input category name
        $parent = DB::table('categories')
            ->where('is_active', true)
            ->where('name', $request->category)
            ->first();

        // Get all active child categories that are bound to select parent
        $child_categories = DB::table('categories')
            ->where('is_active', true)
            ->where('master_category_id', $parent->id)
            ->get();

        // Finally, get all conditions for same core category from many-to-many table
        $conditions = DB::table('category_conditions')
            ->where('category', $parent->name)
            ->pluck('condition');

        return response(['categories' => $child_categories, 'conditions' => $conditions]);
    }

    /**
     * Get and format all categories to display in Menu.
     * @return Collection
     */
    public function getMenuCategories()
    {
        // First, drag out all master (parent) categories
        $parents = DB::table('categories')
            ->where('is_active', true)
            ->where('master_category_id', null)
            ->get();

        // Go through each object in masters array and add reference to an array of children for that core category
        foreach ($parents as $parent) {
            $children = DB::table('categories')
                ->where('is_active', true)
                ->where('master_category_id', $parent->id)
                ->get();
            $parent->children = $children;
        }
        return $parents;
    }

    /**
     * Display a listing of the resource.
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Category::all();
    }

    /**
     * Display a listing of the resource only for active entities.
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getActive()
    {
        return Category::query()
            ->where('is_active', true)
            ->get();
    }

    public function getSubCategories()
    {
        return Category::query()
            ->where('is_active', true)
            ->whereNotNull('master_category_id')
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     * @param \App\Models\Category $category
     * @return Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param \App\Models\Category $category
     * @return Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param \App\Models\Category $category
     * @return Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
