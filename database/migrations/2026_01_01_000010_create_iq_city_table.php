<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_city', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();
            $table->string('province', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->double('lat')->nullable();
            $table->double('long')->nullable();
            $table->index('province');
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_city');
    }
};
