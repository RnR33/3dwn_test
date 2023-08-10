<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SendEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $notification;
    protected $userId;
    
    public function __construct($notification, $userId)
    {
        $this->notification = $notification;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Retrieve the users to notify (excluding the notification creator)
        $usersToNotify = User::where('id', '!=', $this->userId)->get();

        // Send the notification to each user
        Notification::send($usersToNotify, new SendEmailNotification($this->notification));
    }
}
