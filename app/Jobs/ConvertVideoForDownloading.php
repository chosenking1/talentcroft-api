<?php

namespace App\Jobs;

use App\Models\ProjectFile;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertVideoForDownloading implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $video;

    /**
     * Create a new job instance.
     *
     * @param ProjectFile $video
     */
    public function __construct(ProjectFile $video)
    {
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // create a video format...
        $lowBitrateFormat = (new X264)->setKiloBitrate(500);

        // open the uploaded video from the right disk...
        FFMpeg::fromDisk($this->video->disk)->open($this->video->path)->addFilter(function ($filters) {
            // add the 'resize' filter...
            $filters->resize(new Dimension(960, 540));
        })
            // call the 'export' method...
            ->export()

            // tell the MediaExporter to which disk and in which format we want to export...
            ->toDisk('downloadable_videos')
            ->inFormat($lowBitrateFormat)

            // call the 'save' method with a filename...
            ->save($this->video->id . '.mp4');

        // update the database so we know the convertion is done!
        $this->video->update(['converted_for_downloading_at' => now()]);
    }
}
