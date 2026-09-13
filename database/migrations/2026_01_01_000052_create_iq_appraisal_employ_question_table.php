<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_employ_question', function (Blueprint $table) {
            $table->char('appraisal_employ_id', 36);
            $table->unsignedInteger('question_id');
            $table->string('system_info', 255)->nullable();
            $table->unsignedInteger('evaluator1_value')->nullable();
            $table->float('evaluator1_point')->nullable();
            $table->float('evaluator1_weight')->nullable();
            $table->string('evaluator1_note', 255)->nullable();
            $table->string('evaluator2_value', 255)->nullable();
            $table->string('evaluator2_point', 255)->nullable();
            $table->double('evaluator2_weight')->nullable();
            $table->string('evaluator2_note', 255)->nullable();
            $table->primary(['appraisal_employ_id', 'question_id']);
            $table->foreign('appraisal_employ_id')->references('id')->on('iq_appraisal_employ')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('question_id')->references('id')->on('iq_appraisal_question')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_employ_question');
    }
};
