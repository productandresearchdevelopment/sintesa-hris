<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_employ_summary', function (Blueprint $table) {
            $table->char('appraisal_employ_id', 36);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('evaluator1_weight')->nullable();
            $table->float('evaluator1_point')->nullable();
            $table->unsignedInteger('evaluator1_grade')->nullable();
            $table->unsignedInteger('evaluator2_weight')->nullable();
            $table->float('evaluator2_point')->nullable();
            $table->unsignedInteger('evaluator2_grade')->nullable();
            $table->primary(['appraisal_employ_id', 'category_id']);
            $table->foreign('category_id')->references('id')->on('iq_appraisal_question_category')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('appraisal_employ_id')->references('id')->on('iq_appraisal_employ')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_employ_summary');
    }
};
