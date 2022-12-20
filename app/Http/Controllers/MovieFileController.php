<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\MovieFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class MovieFileController extends Controller
{
    // public function uploadmovie(Request $request, Movie $movie){
    //     $request->validate([
    //         'name' => ['required', 'string'],
    //         'decription' => ['required', 'string'],
    //         'url'=> 'mimetypes:video/avi,video/mpeg,video/quicktime,video/mp4,|required|max:102400',
    //         // 'thumbnail' => 'mimes:jpeg,jpg,png,gif|required|max:10000',
    //         'size' => 'nullable',
    //         'preview' => 'nullable',
    //         'meta' => 'nullable',
    //     ]);
    //     // a folder movies_folder will be created inside the s3 bucket that we will specify in the .env file
    //      $base_location = 'movies_folder';
    //      $id = $request->id;
    //      $movie_id = $movie->id;
    //     // Handle File Upload
    //     if($request->hasFile('url')) {                       
    //         $moviePath = $request->file('url')->store($base_location, 's3');
    //      } else {
    //         return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    //     }
        
    //     //We save new path
    //     $movies = new MovieFile();
    //     $movies->movie_id = $movie->id;
    //     $movies->name = $request->name;
    //     $movies->decription = $request->decription;
    //     $movies->url = $moviePath;
    //     $movies->thumbnail = $moviePath;
    //     $movies->size = $request->size;
    //     $movies->duration = $request->duration;
    //     $movies->meta = $request->meta;
    //     $movies->processed_at = Carbon::now();
    
    //     $movies->save();
       
    //     return response()->json(['success' => true, 'message' => 'Movies successfully uploaded', 'movies' =>$movies], 200);
    // }

    public function uploadmovie(Request $request, Movie $movie)
    {
        $file = $request->url;
        // dd($file);
        $movie_location = "movies";
        $thumbnail_location = "thumbnails";
        $aws = env('AWS_ROUTE');
        $movieFile = $movie->episodes()->create($request->only(['name', 'decription']));
        
        $path = $file->storeAs($movie->id, "$movieFile->id.{$file->extension()}", 'processing');

        $movie_id = $movie->id;
        $file_id = $movieFile->id;
        // $destination = "$movie_id/$file_id/";
        $thumbnails = [];
        $media = FFMpeg::fromDisk("processing")->open($path);
        $duration = $media->getDurationInSeconds();
        $rounds = [round($duration * 0.1), round($duration * 0.25), round($duration * 0.5), round($duration * .75), round($duration * .90)];

        foreach ($rounds as $second => $key) {
            $thumbnail = "$thumbnail_location/$file_id/thumbnail_{$key}.png";
            $thumbnails[] = "$thumbnail";
            $media = $media->getFrameFromSeconds($second)->export()->toDisk("s3")->save($thumbnail);
        }
        $filename = "$movie_location/$file_id.{$file->extension()}";
        $movieFile->update([
            'thumbnail' => "$aws/$thumbnails[0]",
            'duration' => $duration,
            "url" => "$aws/$filename",
        ]);

        $media = $media->export()
        //     ->addFormat($lowBitrate, function ($media) {
        //         $media->addFilter('scale=640:480');
        //     })
        //     ->addFormat($midBitrate, function ($media) {
        //         $media->scale(960, 720);
        //     })
        //     ->addFormat($highBitrate, function ($media) {
        //         $media->addFilter(function ($filters, $in, $out) {
        //             $filters->custom($in, 'scale=1920:1200', $out); // $in, $parameters, $out
        //         });
        //     })
        //     ->addFormat($superBitrate, function ($media) {
        //         $media->addLegacyFilter(function ($filters) {
        //             $filters->resize(new Dimension(2560, 1920));
        //         });
        //     })
            // ->onProgress(function ($percentage) use ($movieFile) {
            //     // UPDATE file process progress
            //     $movieFile->update(['progress' => $percentage]);
            // })
            ->toDisk('s3')
            ->save($filename);
        $size = 0;

//        if (File::exists()) {

//        }
        // foreach (File::allFiles(storage_path("processed/videos/{$destination}")) as $file) {
            // $file_size += $file->getSize();
        //      }
        // UPDATE file processed at and filesize
        $movieFile->update(['processed_at' => now()]);
        //remove $media created files
        $media->cleanupTemporaryFiles();
        
        // Delete file used for processing
        Storage::disk("processing")->delete($path);

        return response()->json(['success' => true, 'message' => 'Movies successfully uploaded', 'file' =>$movieFile], 200);
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
