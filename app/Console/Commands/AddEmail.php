<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\WaitList;
use App\Services\SendPulseService;
use Illuminate\Console\Command;

class AddEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $users = WaitList::get();
        foreach ($users as $user){
            $mail = (new SendPulseService())->addContact($user);
//            $user->sendWelcomeMail();
        }

        return 0;
    }


}
