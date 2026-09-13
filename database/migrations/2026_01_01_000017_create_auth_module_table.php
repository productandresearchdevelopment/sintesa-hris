<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_module', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('type_id')->nullable();
            $table->unsignedInteger('parent')->nullable();
            $table->string('path', 255)->nullable();
            $table->string('param', 255)->nullable();
            $table->string('text', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->string('route', 100)->nullable();
            $table->string('url', 255)->nullable();
            $table->unsignedInteger('sort')->nullable();
            $table->tinyInteger('is_active')->nullable();
            $table->tinyInteger('is_locked')->nullable();
            $table->string('auth', 100)->nullable();
            $table->tinyInteger('device')->nullable();
            $table->string('description', 255)->nullable();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unique('auth');
            $table->index('type_id');
            $table->index('parent');
            $table->index('created_by');
            $table->index('updated_by');
            $table->index('route');
            $table->foreign('type_id')->references('id')->on('auth_module_type')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent')->references('id')->on('auth_module')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_module');
    }
};
