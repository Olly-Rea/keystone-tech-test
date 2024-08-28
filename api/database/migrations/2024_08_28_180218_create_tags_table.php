<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tags table
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        // Pivot table for bookmark tags many-to-many relationship
        Schema::create('bookmark_tags', function (Blueprint $table) {
            $table->foreignId('bookmark_id')->references('id')->on('bookmarks');
            $table->foreignId('tag_id')->references('id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
            $table->unique(['bookmark_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('bookmark_tags');
    }
};
