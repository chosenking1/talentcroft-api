<?php

namespace App\Http\Controllers;

use App\Models\ProjectDiscount;
use App\Http\Requests\ProjectDiscountRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectDiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectDiscount::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProjectDiscountRequest $request)
    {
        $data = $request->validate(['project_id' => 'required', 'target' => 'required', 'value' => 'required', 'start_date' => 'required', 'end_date' => 'required']);
        ProjectDiscount::create($request->all());
        return $this->respondWithSuccess(['data' => ['projectDiscount' => $data]], 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return ProjectDiscount::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = ProjectDiscount::find($id);
        $data->update($request->all());
        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return ProjectDiscount::destroy($id);
    }
}
