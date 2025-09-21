<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_details', function (Blueprint $table) {
            $table->json('enabled_modules')->nullable()->after('date_format');
        });
    }

    public function down(): void
    {
        Schema::table('school_details', function (Blueprint $table) {
            $table->dropColumn('enabled_modules');
        });
    }
};

