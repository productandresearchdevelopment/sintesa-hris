<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_helpdesk_organization', function (Blueprint $table) {
            $table->unsignedInteger('helpdesk_id');
            $table->unsignedInteger('organization_id');
            $table->primary(['helpdesk_id', 'organization_id']);
            $table->foreign('organization_id')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('helpdesk_id')->references('id')->on('iq_helpdesk')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_helpdesk_organization');
    }
};
