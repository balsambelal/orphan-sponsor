<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankAccountToOrphansTable extends Migration
{
   
   public function up()
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->string('bank_account')->nullable()->after('guardian_id')->comment('رقم الحساب البنكي لليتيم');
    });
}

public function down()
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->dropColumn('bank_account');
    });
}

}
