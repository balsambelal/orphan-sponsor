<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();

            // العمود المرتبط باليتيم
            $table->foreignId('orphan_id')
                  ->constrained('orphans')
                  ->onDelete('cascade'); // لو حذف يتيم، تحذف الكفالات المرتبطة

            // العمود المرتبط بالكفيل
            $table->foreignId('sponsor_id')
                  ->constrained('sponsors')
                  ->onDelete('cascade'); // لو حذف كفيل، تحذف الكفالات المرتبطة

            $table->date('start_date');
            $table->string('type')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('active');              
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sponsorships');
    }
};
