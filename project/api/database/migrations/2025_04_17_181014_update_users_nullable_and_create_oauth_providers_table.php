<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table("users", function (Blueprint $table) {
            $table->string("username")->nullable()->change();
            $table->string("password")->nullable()->change();
        });

        Schema::create("oauth_providers", function (Blueprint $table) {
            $table->id();
            $table->string("user_id");

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string("email");

            $table->string("token");
            $table->string("refresh_token")->nullable();


            $table->string("provider")->index();
            $table->timestamps();

            // make the combination of provider and email unique
            // not individually
            $table->unique(["provider", "email"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table("users", function (Blueprint $table) {
            $table->string("username")->nullable(false)->change();
            $table->string("password")->nullable(false)->change();
        });

        Schema::dropIfExists("oauth_providers");
    }
};
