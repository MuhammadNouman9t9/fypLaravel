<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('cnic', 13)->nullable()->unique()->after('avatar_path');
            $table->string('study_program', 32)->nullable()->after('cnic');
            $table->text('about_me')->nullable()->after('study_program');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['cnic']);
            $table->dropColumn(['cnic', 'study_program', 'about_me']);
        });
    }
};
