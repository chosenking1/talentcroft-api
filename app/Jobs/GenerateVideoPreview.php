<?php

namespace App\Jobs;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateVideoPreview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    public function handle()
    {



        FFMPEG::getThumbnails($filename, 'thumbnails', 5);

        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->video->disk)->open($this->video->path);
            // call the 'exportForHLS' method and specify the disk to which we want to export...


        // update the database so we know the convertion is done!
        $this->video->update(['converted_for_streaming_at' => now()]);
    }
}
