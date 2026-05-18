<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_registrations', function (Blueprint $table): void {
            $table->string('registration_number')->nullable()->unique()->after('id');
            $table->string('name')->nullable()->after('registration_number');
            $table->string('birth_place')->nullable()->after('name');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('nik', 32)->nullable()->after('birth_date');
            $table->string('family_card_number', 32)->nullable()->after('nik');
            $table->string('gender', 20)->nullable()->after('family_card_number');
            $table->string('religion', 50)->nullable()->after('gender');
            $table->string('father_name')->nullable()->after('religion');
            $table->string('mother_name')->nullable()->after('father_name');
            $table->string('father_occupation')->nullable()->after('mother_name');
            $table->string('mother_occupation')->nullable()->after('father_occupation');
            $table->string('father_phone', 30)->nullable()->after('mother_occupation');
            $table->string('mother_phone', 30)->nullable()->after('father_phone');
            $table->text('address')->nullable()->after('mother_phone');
            $table->text('notes')->nullable()->after('address');
            $table->string('birth_certificate_path')->nullable()->after('notes');
            $table->string('family_card_path')->nullable()->after('birth_certificate_path');
            $table->string('student_photo_path')->nullable()->after('family_card_path');
            $table->string('kindergarten_certificate_path')->nullable()->after('student_photo_path');
            $table->string('status', 30)->default('submitted')->after('kindergarten_certificate_path');
            $table->timestamp('submitted_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_registrations', function (Blueprint $table): void {
            $table->dropColumn([
                'registration_number',
                'name',
                'birth_place',
                'birth_date',
                'nik',
                'family_card_number',
                'gender',
                'religion',
                'father_name',
                'mother_name',
                'father_occupation',
                'mother_occupation',
                'father_phone',
                'mother_phone',
                'address',
                'notes',
                'birth_certificate_path',
                'family_card_path',
                'student_photo_path',
                'kindergarten_certificate_path',
                'status',
                'submitted_at',
            ]);
        });
    }
};
