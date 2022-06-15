<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{

    /**
     * Add Images to Item Object and store them.
     * @param Request $request
     * @param User $user
     * @return Builder|JsonResponse|Model|object|null
     */
    public function addItemImage(Request $request, Item $item)
    {
        $validator = Validator::make($request->all(), [
//            'image' => 'required',
//            'image.*' => 'required|mimes:jpeg,png,jpg|max:2048'
            'image' => 'required|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $image = $request->image;

        // later todo: get image array from frontend instead of calling api for each

//        foreach ($request->image as $image) {

        // Format the name for image being stored
        $originalName = explode(
            ".",
            preg_replace("/[^A-Za-z0-9.!?]/", '', $image->getClientOriginalName()), 2)[0];
        $time = now()->getTimestamp();
        $extension = $image->getClientOriginalExtension();
        $filename = "{$originalName}-{$time}.{$extension}";

        // Store the image in specified folder
        $dest_path = 'storage/item_images/' . $filename;
        $image->storeAs('/item_images', $filename, ['disk' => 'public']);

        Image::create([
            'image' => $dest_path,
            'item_id' => $item->id
        ]);

//        }

        return $item;
    }

    public function index()
    {
        return Item::all();
    }

    public function getActive()
    {
        return Item::query()->where('is_active', true)->get();
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
     * @param \App\Http\Requests\StoreItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateItemRequest $request
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Item $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }
}
