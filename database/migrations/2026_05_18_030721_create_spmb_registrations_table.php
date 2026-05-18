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
        Schema::create('spmb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 30)->unique();
            $table->string('class_level', 10);
            $table->string('name');
            $table->string('nis', 30)->nullable();
            $table->string('nisn', 30)->nullable();
            $table->string('birth_place', 100);
            $table->date('birth_date');
            $table->string('nik', 30)->unique();
            $table->string('gender', 20);
            $table->string('religion', 50);
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('father_occupation')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('father_phone', 30)->nullable();
            $table->string('mother_phone', 30)->nullable();
            $table->text('address')->nullable();
            $table->text('notes')->nullable();
            $table->string('birth_certificate_path');
            $table->string('family_card_path');
            $table->string('student_photo_path');
            $table->string('kindergarten_certificate_path')->nullable();
            $table->string('status', 30)->default('submitted');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index(['class_level', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spmb_registrations');
    }
};
