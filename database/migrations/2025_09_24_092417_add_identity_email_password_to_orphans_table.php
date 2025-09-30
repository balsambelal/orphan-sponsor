<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIdentityEmailPasswordToOrphansTable extends Migration
{
public function up()
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->string('identity_number')->nullable()->after('name');
        $table->string('email')->nullable()->after('identity_number');
    });
}


public function down()
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->dropColumn(['identity_number','email']);
    });
}

}
