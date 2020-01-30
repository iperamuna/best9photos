<?php

namespace App\Jobs;

use App\Mail\Best9Photos;
use App\Models\Photos;
use App\Services\FacebookServices;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PickBest9FacebookPhotosFromLastYear implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * @var User
     */
    private $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {

        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @param FacebookServices $facebookServices
     * @return void
     */
    public function handle(FacebookServices $facebookServices)
    {

        $photosCollections = $facebookServices->fetchFacebookPhotos($this->user);

        //Sort Array by Likes in Descending Order
        //This is my users most important 9 photos sorting criteria

        $selectedPhotosCollections = $photosCollections->sortByDesc('total_likes')->take(9);

        foreach ($selectedPhotosCollections as $photo){
            Photos::create([
                'facebook_link' => $photo['image_src'],
                'likes_count' => $photo['total_likes'],
                'user_id' => $this->user->id,
            ]);
        }

        Mail::to($this->user)->send(new Best9Photos($this->user, $selectedPhotosCollections));
    }
}
