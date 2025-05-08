<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();

            $table->string("tag", 30);

            $table->timestamps();
        });

        Schema::create("hashtag_entity", function (Blueprint $table) {
            $table->foreignId("hashtag_id")
                ->references("id")
                ->on("hashtags")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId("entity_id")
                ->references("id")
                ->on("entities")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->primary(["entity_id", "hashtag_id",]);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('hashtags');
        Schema::dropIfExists('hashtag_entities');
    }
};
