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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nuptk', 30)->nullable()->unique();
            $table->string('nip', 30)->nullable()->unique();
            $table->string('nik', 30)->unique();
            $table->string('gender', 20);
            $table->string('birth_place', 100);
            $table->date('birth_date');
            $table->string('employment_status', 100);
            $table->string('religion', 50);
            $table->text('address');
            $table->string('phone', 30);
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
