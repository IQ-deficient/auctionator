<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Requests\StoreStatusRequest;
use App\Http\Requests\UpdateStatusRequest;
use Illuminate\Database\Eloquent\Collection;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Collection|Status[]
     */
    public function index()
    {
        $statuses = Status::all();
        $statuses[] = new Status([
            'status' => "Inactive",
        ]);
        return $statuses;
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
     * @param \App\Http\Requests\StoreStatusRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateStatusRequest $request
     * @param \App\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateStatusRequest $request, Status $status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Status $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        //
    }
}
