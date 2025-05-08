<?php

namespace Tests\Feature;

use App\Enums\EntityType;
use App\Models\Entity;
use App\Models\Hashtag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HashtagTest extends TestCase {

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_add_hashtag_to_entity(): void {

        $user = new User([
            "username" => "jakki",
            "email" => "jakki@gmail.com"
        ]);

        $user->save();

        $entity = Entity::createWithType(EntityType::POST, $user->id);

        $entity->save();

        $hashtag1 = new Hashtag([
            "tag" => "thisIsAHashTag"
        ]);

        $hashtag1->save();

        $hashtag2 = new Hashtag([
            "tag" => "thisisanotherverylonghashtag",
        ]);

        $hashtag2->save();

        $entity->hashtags()->attach($hashtag1);
        $entity->hashtags()->attach($hashtag2);

        // check if $entity has both hashtags

        $this->assertTrue($entity->hashtags()->where("tag", $hashtag1->tag)->exists());
        $this->assertTrue($entity->hashtags()->where("tag", $hashtag2->tag)->exists());


    }
}
