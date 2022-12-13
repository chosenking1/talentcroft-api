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
            'url'=> 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,|required|max:102400',
        //  validate other fields
        ]);

        // a folder movies_folder will be created inside the s3 bucket that we will specify in the .env file
         $base_location = 'movies_folder';
        // Handle File Upload
        if($request->hasFile('url')) {                       
            $moviePath = $request->file('url')->store($base_location, 's3');
            return response()->json(['success' => true, 'message' => 'Movies successfully uploaded', 'moviePath' =>$moviePath], 200);
          
        } else {
            return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
        }
   

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
