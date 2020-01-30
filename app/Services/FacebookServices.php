<?php


namespace App\Services;


use App\User;
use Carbon\Carbon;
use Facebook\Facebook;
use Illuminate\Support\Collection;

class FacebookServices
{
    private $facebook;
    private $fbPhotos = [];

    public function __construct()
    {
        $this->facebook = resolve(Facebook::class);
    }

    /**
     * Fetch Facebook photos from facebook and returns based on parameters
     *
     * @param User $user
     * @return Collection
     */
    public function fetchFacebookPhotos(User $user): Collection
    {
        $lastYear = Carbon::now()->subYear();
        $since = $lastYear->startOfYear()->unix();
        $until = $lastYear->endOfYear()->unix();

        $uri = "/me/photos?fields=source,likes.limit(0).summary(total_count)&since={$since}&until={$until}&offset=1&limit=10";
        $response = $this->facebook->get($uri, $user->facebook_token);

        $this->setPhotosFromResponse($response);

        return collect($this->fbPhotos);
    }

    /**
     * Fetch Facebook photos from facebook From Response
     *
     * @param $response
     * @return void
     */
    private function setPhotosFromResponse($response):void {

        $responseBody = json_decode($response->getBody());
        $photos = collect($responseBody->data);
        $aPhotos = $photos->map(function ($photo) {
            return [
                'image_src' => $photo->source,
                'total_likes' => $photo->likes->summary->total_count
            ];
        });
        $this->fbPhotos = $aPhotos;
        $responseEdge = $response->getGraphEdge();
        $this->setPhotosFromEdge($responseEdge);

    }

    /**
     * Fetch Facebook photos from facebook From Response
     *
     * @param $responseEdge
     * @return void
     */
    private function setPhotosFromEdge($responseEdge):void {

        $response = $this->facebook->next($responseEdge);

        if(!is_null($response)){

            foreach ($response as $photo) {
                $aPhoto = $photo->asArray();
                $aLike = $photo['likes']->getMetaData();
                $this->fbPhotos[] = [
                    'image_src' => $aPhoto['source'],
                    'total_likes' => $aLike['summary']['total_count']
                ];
            }

            $this->setPhotosFromEdge($response);

        }

    }
}
