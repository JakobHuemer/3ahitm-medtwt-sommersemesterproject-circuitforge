<?php

namespace App\Http\Controllers;

use App\Enums\Rating;
use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller {

    // get
    public function get(Entity $entity) {
        $rating = $entity->getRating();
        $userRating = null;

        if (Auth::check()) {
            $userRating = $entity->ratings()
                ->where("id", Auth::id())
                ->first()
                ->pivot->rating ?? 0;
        }

        return [
            "rating" => $rating,
            "userRating" => $userRating,
        ];
    }

    // requires auth
    public function set(Entity $entity, Rating $rating) {
        $entity->rate($rating);

        return $this->get($entity);
    }

    public function unrate(Entity $entity) {
        $entity->unrate();
    }

}
