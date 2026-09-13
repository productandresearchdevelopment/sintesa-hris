<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_helpdesk_answer_mention', function (Blueprint $table) {
            $table->char('user_id', 36);
            $table->unsignedInteger('answer_id');
            $table->primary(['user_id', 'answer_id']);
            $table->foreign('user_id')->references('id')->on('auth_user')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('answer_id')->references('id')->on('iq_helpdesk_answer')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_helpdesk_answer_mention');
    }
};
