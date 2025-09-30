<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sponsorships', function (Blueprint $table) {
            if (!Schema::hasColumn('sponsorships','orphan_id')) {
                $table->unsignedBigInteger('orphan_id');
            }
            if (!Schema::hasColumn('sponsorships','sponsor_id')) {
                $table->unsignedBigInteger('sponsor_id');
            }
            if (!Schema::hasColumn('sponsorships','amount')) {
                $table->decimal('amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('sponsorships','account_no')) {
                $table->string('account_no')->nullable();
            }
            if (!Schema::hasColumn('sponsorships','start_date')) {
                $table->date('start_date')->nullable(); // nullable مؤقتًا لتفادي الأخطاء
            }
            if (!Schema::hasColumn('sponsorships','end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('sponsorships','status')) {
                $table->string('status')->default('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sponsorships', function (Blueprint $table) {
            $table->dropColumn([
                'orphan_id', 'sponsor_id', 'amount', 'account_no',
                'start_date', 'end_date', 'status'
            ]);
        });
    }
};
