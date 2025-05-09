<?php

use App\Enums\VersionType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('versions', function (Blueprint $table) {

            $table->string("version");
            $table->primary("version");

            $table->enum("type", VersionType::values());
            $table->boolean("is_latest")
                ->nullable()
                ->default(null);

            $table->timestamp("released")
                ->nullable()
                ->default(null);

        });

        Schema::create("version_post", function (Blueprint $table) {
            $table->string("version_id");
            $table->primary("version_id")
                ->references("version")
                ->on("versions")
                ->noActionOnDelete()
                ->noActionOnUpdate();

            $table->foreignId("post_id")
                ->references("id")
                ->on("posts")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->boolean("incompatible")
                ->default(false);

            // PKs

            $table->primary(["post_id", "version_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('version_post');
        Schema::dropIfExists('versions');
    }
};
