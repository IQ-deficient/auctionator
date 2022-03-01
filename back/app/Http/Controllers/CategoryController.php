<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    // todo: Comments for everything
    public function getParentCategories()
    {
        return DB::table('categories')
            ->where('is_active', true)
            ->where('master_category_id', null)
            ->get();
    }

    public function getChildCategoriesAndConditions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|exists:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $parent = DB::table('categories')
            ->where('is_active', true)
            ->where('name', $request->category)
            ->first();

        $child_categories = DB::table('categories')
            ->where('is_active', true)
            ->where('master_category_id', $parent->id)
            ->get();

        $conditions = DB::table('category_conditions')
            ->where('category', $parent->name)
            ->pluck('condition');

        return response(['categories' => $child_categories, 'conditions' => $conditions]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Category::all();
    }

    public function getActive()
    {
        return Category::where('is_active', true)->get();
    }

    public function getSubCategories()
    {
        return Category::where('is_active', true)->whereNotNull('master_category_id')->get();
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
     * @param \App\Http\Requests\StoreCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateCategoryRequest $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
}
