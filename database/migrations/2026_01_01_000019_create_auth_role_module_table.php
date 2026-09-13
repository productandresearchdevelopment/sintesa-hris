<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_role_module', function (Blueprint $table) {
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('module_id');
            $table->primary(['role_id', 'module_id']);
            $table->foreign('module_id')->references('id')->on('auth_module')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('role_id')->references('id')->on('auth_role')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_role_module');
    }
};
