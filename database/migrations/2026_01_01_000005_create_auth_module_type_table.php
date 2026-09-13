<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_module_type', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('group', 20)->nullable();
            $table->string('name', 50)->nullable();
            $table->string('icon', 50)->nullable();
            $table->tinyInteger('show_menu')->nullable();
            $table->tinyInteger('xurl')->nullable();
            $table->tinyInteger('xroute')->nullable();
            $table->tinyInteger('xauth')->nullable();
            $table->tinyInteger('xicon')->nullable();
            $table->tinyInteger('xdevice')->nullable();
            $table->string('description', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_module_type');
    }
};
