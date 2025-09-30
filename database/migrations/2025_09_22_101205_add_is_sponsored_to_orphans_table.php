<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSponsoredToOrphansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up(): void
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->boolean('is_sponsored')->default(0);
    });
}

public function down(): void
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->dropColumn('is_sponsored');
    });
}


}
