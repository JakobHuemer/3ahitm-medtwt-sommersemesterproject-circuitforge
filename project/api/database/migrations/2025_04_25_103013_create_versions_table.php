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
            $table->id();

            $table->string("version")->unique("unique_version");
            $table->enum("type", VersionType::values());
            $table->timestamp("released")
                ->nullable()
                ->default(null);

        });

        Schema::create("version_post", function (Blueprint $table) {
            $table->foreignId("version_id")
                ->references("id")
                ->on("versions")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId("post_id")
                ->references("id")
                ->on("posts")
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // PKs

            $table->primary(["post_id", "version_id"]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('versions');
    }
};
