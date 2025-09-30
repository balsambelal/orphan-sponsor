<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::create('orphans', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->date('birthdate');
        $table->enum('gender',['ذكر','أنثى']);
        $table->string('address');
        $table->text('notes')->nullable();
        $table->boolean('is_sponsored')->default(0);   // حالة الكفالة
        $table->string('child_image')->nullable();
        $table->string('birth_certificate')->nullable();
        $table->string('death_certificate')->nullable();
        $table->string('guardian_id')->nullable();
        $table->string('custody_document')->nullable();
        $table->timestamps();
    });
}


    public function down()
{
    Schema::table('orphans', function (Blueprint $table) {
        $table->integer('age')->nullable()->after('name');
        $table->renameColumn('notes', 'details');
        $table->dropColumn('birthdate');
    });
}
};
