<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_appraisal_period_organization', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('period_id');
            $table->unsignedInteger('organization_id');
            $table->unsignedInteger('template_id')->nullable();
            $table->index('period_id');
            $table->index('organization_id');
            $table->foreign('period_id')->references('id')->on('iq_appraisal_period')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('organization_id')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_appraisal_period_organization');
    }
};
