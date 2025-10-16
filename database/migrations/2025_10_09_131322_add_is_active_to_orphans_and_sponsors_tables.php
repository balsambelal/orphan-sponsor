<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // إضافة العمود لجدول الأيتام
        Schema::table('orphans', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('updated_at')
                  ->comment('يحدد إذا كان الحساب مفعل أم لا');
        });

        // إضافة العمود لجدول الكفّالين
        Schema::table('sponsors', function (Blueprint $table) {
            $table->boolean('is_active')->default(false)->after('updated_at')
                  ->comment('يحدد إذا كان الحساب مفعل أم لا');
        });
    }

    public function down(): void
    {
        Schema::table('orphans', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });

        Schema::table('sponsors', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
