<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            $table->string("path");
            $table->foreignId("post_id")
                ->nullable()
                ->references("id")
                ->on("posts")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string("name");
            $table->string("filetype");
            $table->string("asset_type");

            $table->bigInteger("downloads")->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('assets');
    }
};
