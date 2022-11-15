<?php

namespace App\Traits;

use App\Listeners\NotifyUser;
use App\Mail\MailSender;
use App\Notifications\AppNotifier;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

trait UseStorage
{

    private function folderName()
    {
        return "creatives/{$this->id}/";
    }


    public function storeFile($filename, $file)
    {
        $folder = $this->folderName();
        Storage::disk('s3')->put($folder . $filename, $file);
    }

    public function getFile($filename)
    {
        $folder = $this->folderName();
        return Storage::disk('s3')->get($folder . $filename);
    }

}
