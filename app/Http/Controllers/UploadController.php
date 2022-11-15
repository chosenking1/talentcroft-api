<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class UploadController extends Controller
{
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


//     public function uploadSubmit(Request $request)
// {
//     $this->validate($request, ['name' => 'required','photos'=>'required',]);
//     if($request->hasFile('photos'))
//         {
//         $allowedfileExtension=['pdf','jpg','png','docx'];
//         $files = $request->file('photos');
//         foreach($files as $file){
//         $filename = $file->getClientOriginalName();
//         $extension = $file->getClientOriginalExtension();
//         $check=in_array($extension,$allowedfileExtension);
//         dd($check);
//         if($check){
//         $items= Project::create($request->all());
//         foreach ($request->photos as $photo) {
//             $filename = $photo->store('photos');
//             Project::create(['item_id' => $items->id,'filename' => $filename]);
//         }
//         echo "Upload Successfully";
//     }
//     else{
//     echo '<div class="alert alert-warning"><strong>Warning!</strong> Sorry Only Upload png , jpg , doc</div>';
//     }
//     }
//     }
//     }

}
