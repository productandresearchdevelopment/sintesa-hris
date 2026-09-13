<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_helpdesk_answer_file', function (Blueprint $table) {
            $table->unsignedInteger('helpdesk_answer_id');
            $table->char('upload_id', 36);
            $table->primary(['helpdesk_answer_id', 'upload_id']);
            $table->foreign('helpdesk_answer_id')->references('id')->on('iq_helpdesk_answer')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('upload_id')->references('id')->on('uploads')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_helpdesk_answer_file');
    }
};
