<?php

namespace App\Http\Controllers;

use App\Models\MovieFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    public function uploadmovie(Request $request){
        $request->validate([
            'movie_id'=> 'required',
            'url'=> 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,|required|max:102400',
            // 'size'=>'required|max:102400'
            // 'duration'=>'required',
        ]);

        // a folder movies_folder will be created inside the s3 bucket that we will specify in the .env file
         $base_location = 'movies_folder';

        // Handle File Upload
        if($request->hasFile('movies')) {                       
            $moviePath = $request->file('movies')->store($base_location, 's3');
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
    
        //We save new path
        $movies = new MovieFile();
        $movies->movie_id = $request->movie_id;
        $movies->url = $moviePath;
        $movies->thumbnail = $moviePath;
        $movies->size = $request->size;
        $movies->duration = $request->duration;
        $movies->meta = $request->meta;
        $movies->processed_at = Carbon::now();
    
        $movies->save();
       
        return response()->json(['success' => true, 'message' => 'Movies successfully uploaded', 'movies' =>$movies], 200);
    }

     public function destroy($id)
    {
        $movie = MovieFile::findorfail($id);

        if(empty($movie)){
            return response()->json(['success' => false, 'message' => 'Movie not found'], 404);
        }

        //We remove existing movie
        if(!empty($movie)){
            Storage::disk('s3')->delete($movie->path);
            $movie->delete();
            return response()->json(['success' => true, 'message' => 'Movie deleted'], 200);
        }

        return response()->json(['success' => false, 'message' => 'Unable to delete movie. Please try again later.'], 400);
    }
}
