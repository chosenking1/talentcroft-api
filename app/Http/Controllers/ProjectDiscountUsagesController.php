<?php

namespace App\Http\Controllers;

use App\Models\ProjectDiscountUsage;
use App\Http\Requests\ProjectDiscountUsagesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectDiscountUsagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProjectDiscountUsage::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProjectDiscountUsagesRequest $request)
    {
        $data = $request->validate(['project_id' => 'required', 'user_id' => 'required']);
        ProjectDiscountUsage::create($request->all());
        return $this->respondWithSuccess(['data' => ['projectDiscountUsages' => $data]], 201);
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
        return ProjectDiscountUsage::find($id);
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
        $data = ProjectDiscountUsage::find($id);
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
        return ProjectDiscountUsage::destroy($id);
    }
}
