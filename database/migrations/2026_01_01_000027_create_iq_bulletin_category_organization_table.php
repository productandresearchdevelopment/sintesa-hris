<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iq_bulletin_category_organization', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('organization_id');
            $table->primary(['category_id', 'organization_id']);
            $table->foreign('category_id')->references('id')->on('iq_bulletin_category')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('organization_id')->references('id')->on('iq_org')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('iq_bulletin_category_organization');
    }
};
