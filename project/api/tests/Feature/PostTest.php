<?php

namespace Tests\Feature;

use App\Enums\EntityType;
use App\Models\Entity;
use App\Models\Post;
use App\Models\User;
use Database\Factories\PostFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PostTest extends TestCase {

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_create_post(): void {


        // delete the user by username = jakki

        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";


        echo json_encode(Entity::all());


        echo "\n";
        echo "\n";
        echo "\n";
        echo "\n";

        $user = User::where("username", "jakki")->first();
        if ($user) {
            $user->delete();
        }

        $user = new User([
            "username" => "jakki",
            "email" => "jakki@gmail.com",
            "password" => Hash::make("deimom")
        ]);

        $user->save();

        $entity = new Entity([
            "entity_type" => EntityType::POST,
            "author_id" => $user->id
        ]);

        $entity->save();

        echo json_encode(Entity::all());

        $post = new Post([
            "id" => $entity->id,
            "title" => "wow",
            "content" => "This is some content"
        ]);

        $post->save();

        echo json_encode($entity);

        echo "\n";
        echo "\n";
        echo "---------\n";

//        echo json_encode($post->author()->get());
//        echo json_encode($post->author_id);

        echo $post->toJson(JSON_PRETTY_PRINT);

        $this->assertTrue(true);

    }
}
