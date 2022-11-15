<?php

namespace App\Http\Controllers;

use App\Models\ProjectFile;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProjectFileRequest;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\File;

class ProjectFileController extends Controller
{
    final public function index()
    {
        return ProjectFile::all();
    }

    final public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'type' => 'required',
            'url' => 'required|file|mimetypes:video/mp4',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'preview' => 'file|mimetypes:video/mp4',
            'size' => 'required'
        ]);

        $data = new ProjectFile();
        if ($url = $request->url){
        $filename = 'url'.'_'.date('YmdHis').'.'.$url->getClientOriginalExtension();
        $request->url->move('assets', $filename);
        $data->url=$filename;
        }

        if ($thumbnail = $request->thumbnail){
        $filename = 'thumbnail'.'_'.date('YmdHis').'.'.$thumbnail->getClientOriginalExtension();
        $request->thumbnail->move('assets', $filename);
        $data->thumbnail=$filename;
        }

        if ($preview = $request->preview){
        $filename = 'preview'.'_'.date('YmdHis').'.'.$preview->getClientOriginalExtension();
        $request->preview->move('assets', $filename);
        $data->preview=$filename;
        }

        $data->project_id=$request->project_id;
        $data->name=$request->name;
        $data->type=$request->type;
        $data->size=$request->size;
        $data->meta=$request->meta;

        $data->save();
        return $this->respondWithSuccess(['data' => ['projectFile' => $data]], 201);
    }

    public function show($id)
    {
        return ProjectFile::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(projectFile $projectFile)
    {
        return view('projectFile.edit', compact('book'));
    }

    final public function update(Request $request, $id)
    {
        $data = ProjectFile::find($id);
        if($request->hasFile('url')){
            $request->validate([
                'url' => 'required|file|mimetypes:video/mp4',
              ]);
              $destination = 'assets/'.$data->url;
              $url = $request->file('url');
              $extension = $url->getClientOriginalExtension();
              $filename = 'url'.'_'.date('YmdHis').'.'.$extension;
              $url->move('assets', $filename);
              $data->url = $filename;
              $data->save();
        }

        if($request->hasFile('thumbnail')){
            $request->validate([
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              ]);
              $destination = 'assets/'.$data->thumbnail;
              $thumbnail = $request->file('thumbnail');
              $extension = $thumbnail->getClientOriginalExtension();
              $filename = 'thumbnail'.'_'.date('YmdHis').'.'.$extension;
              $thumbnail->move('assets', $filename);
              $data->thumbnail = $filename;
              $data->save();
        }

        if($request->hasFile('preview')){
            $request->validate([
                'preview' => 'required|file|mimetypes:video/mp4',
              ]);
              $destination = 'assets/'.$data->preview;
              $preview = $request->file('preview');
              $extension = $preview->getClientOriginalExtension();
              $filename = 'preview'.'_'.date('YmdHis').'.'.$extension;
              $preview->move('assets', $filename);
              $data->preview = $filename;
              $data->save();
        }

        $data->project_id = $request->project_id;
        $data->name = $request->name;
        $data->description = $request->description;
        $data->type = $request->type;
        $data->tags = $request->tags;
        $data->save();
        $data->update();
        return $data;
    }

    final public function destroy($id)
    {
        return ProjectFile::destroy($id);
    }
}
