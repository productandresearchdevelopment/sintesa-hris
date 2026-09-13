<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_global_data', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('alias', 20)->nullable();
            $table->string('color', 6)->nullable();
            $table->longText('property')->nullable();
            $table->string('description', 255)->nullable();
            $table->index('group');
            $table->index('name');
            $table->index('alias');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_global_data');
    }
};
