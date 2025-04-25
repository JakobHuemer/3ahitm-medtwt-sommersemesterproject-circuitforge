<?php

namespace Tests\Feature;

use App\Enums\EntityType;
use App\Models\Entity;
use App\Models\Post;
use App\Models\User;
use App\Models\Version;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostVersionTest extends TestCase {

    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_assign_two_versions_to_post(): void {
        // Post:

        $user = new User([
            "username" => "asdf",
            "email" => "asdf",
        ]);

        $user->save();

        $post = Post::createPost($user->id,
            "This is the title",
            "and this is the body of the post");

        $post->save();

        $version1 = new Version([
            "version" => "1.21.4",
            "type" => "release",
            "released" => 5432345
        ]);

        $version1->save();

        $version2 = new Version([
            "version" => "1.21.5-pre3",
            "type" => "snapshot",
            "released" => 34523454,
        ]);

        $version2->save();

        $post->versions()->attach("1.21.4");
        $post->versions()->attach("1.21.5-pre3");

        echo json_encode($post->versions()->get(), JSON_PRETTY_PRINT);

        // test if $post has both versions?
//        $this->assertTrue($post->versions()->where("version_id", $version1->version)->exists());
//        $this->assertTrue($post->versions()->where("version", $version2->version)->exists());

    }
}
