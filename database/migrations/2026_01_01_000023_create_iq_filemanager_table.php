<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_filemanager', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->string('path', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->unsignedInteger('level')->nullable();
            $table->unsignedInteger('sort')->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->char('file_id', 36)->nullable();
            $table->string('tag', 255)->nullable();
            $table->string('extension', 30)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('path_file', 255)->nullable();
            $table->string('type_file', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('deleted_by');
            $table->index('parent_id');
            $table->index('file_id');
            $table->foreign('parent_id')->references('id')->on('iq_filemanager')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('file_id')->references('id')->on('uploads')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_filemanager');
    }
};
