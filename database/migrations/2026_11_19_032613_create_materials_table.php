<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaterialsTable extends Migration
{
    public function up()
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('supplier_id')->index();
            $table->string('sku', 40)->unique();
            $table->string('name', 120);
            $table->string('category', 80); // contoh: Semen, Besi, Cat, Keramik
            $table->string('unit', 30);     // contoh: Sak, Batang, Kaleng, Dus
            $table->decimal('price', 15, 2);
            $table->integer('stock')->default(0);
            $table->timestamps();

            $table->foreign('supplier_id')
                  ->references('id')
                  ->on('suppliers')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('materials');
    }
}
