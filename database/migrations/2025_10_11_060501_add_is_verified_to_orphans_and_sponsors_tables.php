<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إضافة عمود is_verified إلى جدول الأيتام
        Schema::table('orphans', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('is_active');
        });

        // إضافة عمود is_verified إلى جدول الكفلاء
        Schema::table('sponsors', function (Blueprint $table) {
            $table->boolean('is_verified')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('orphans', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });

        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn('is_verified');
        });
    }
};

