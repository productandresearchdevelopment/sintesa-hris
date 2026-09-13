<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('filename', 255)->nullable();
            $table->string('category', 50)->nullable();
            $table->string('path', 255)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('mime', 150)->nullable();
            $table->string('extension', 10)->nullable();
            $table->double('size')->nullable();
            $table->string('filename_origin', 255)->nullable();
            $table->string('watermark', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uploads');
    }
};
