<?php

namespace App\Mail;

use App\Models\Photos;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Best9Photos extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $photos;
    /**
     * Create a new message instance.
     * @param User $user
     * @param $photos
     * @return void
     */
    public function __construct(User $user, $photos)
    {
        $this->user = $user;
        $this->photos = $photos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('noreply@best9photos.com', 'Best 9 Photos')->view('emails.best9photos');
    }
}
