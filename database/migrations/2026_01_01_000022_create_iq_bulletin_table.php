<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_bulletin', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable();
            $table->unsignedInteger('category_id')->nullable();
            $table->char('cover_image_id', 36)->nullable();
            $table->string('description', 255)->nullable();
            $table->longText('content')->nullable();
            $table->tinyInteger('is_pinned')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('category_id');
            $table->index('cover_image_id');
            $table->foreign('cover_image_id')->references('id')->on('uploads')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('category_id')->references('id')->on('iq_bulletin_category')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_bulletin');
    }
};
