<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('representative')->nullable();
            $table->string('tax_code')->nullable();
            $table->longText('address')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->string('manager')->nullable();
            $table->string('tel')->nullable();
            $table->string('province')->nullable();
            $table->string('field')->nullable();
            $table->string('position')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
