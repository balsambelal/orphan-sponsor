<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    \Schema::table('orphans', function ($table) {
        $table->boolean('is_active')->default(true)->change();
    });

    \Schema::table('sponsors', function ($table) {
        $table->boolean('is_active')->default(true)->change();
    });
}

public function down()
{
    \Schema::table('orphans', function ($table) {
        $table->boolean('is_active')->default(false)->change();
    });

    \Schema::table('sponsors', function ($table) {
        $table->boolean('is_active')->default(false)->change();
    });
}

};
