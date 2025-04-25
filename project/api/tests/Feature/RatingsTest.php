<?php

namespace Tests\Feature;

use App\Enums\EntityType;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RatingsTest extends TestCase {

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_add_three_ratings_count_likes_and_dislikes(): void {

        $user1 = new User([
            "username" => "jakki",
            "email" => "jakki@gmail.com"
        ]);

        $user1->save();

        $user2 = new User([
            "username" => "jakki2",
            "email" => "jakki2@gmail.com"
        ]);

        $user2->save();

        $user3 = new User([
            "username" => "jakki3",
            "email" => "jakki3@gmail.com"
        ]);

        $user3->save();


        $entity = Entity::createWithType(EntityType::POST, $user1->id);
        $entity->save();

        // rate

        $entity->ratings()->attach($user1, ["rating" => true]);
        $entity->ratings()->attach($user2, ["rating" => false]);
        $entity->ratings()->attach($user3, ["rating" => true]);

        // check ratings

        $this->assertTrue($entity->ratings()->where("user_id", $user1->id)->where("rating", true)->exists());
        $this->assertTrue($entity->ratings()->where("user_id", $user2->id)->where("rating", false)->exists());
        $this->assertTrue($entity->ratings()->where("user_id", $user3->id)->where("rating", true)->exists());

        // check if user1 has rated the entity

        $likes = $entity->ratings()->where("rating", true)->count();
        $dislikes = $entity->ratings()->where("rating", false)->count();

        $this->assertEquals(2, $likes);
        $this->assertEquals(1, $dislikes);
    }
}
