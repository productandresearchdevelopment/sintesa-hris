<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_question_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_question_category');
    }
};
