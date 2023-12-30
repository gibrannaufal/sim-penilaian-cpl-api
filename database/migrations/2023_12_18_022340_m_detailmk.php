<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MDetailmk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_detailmk', function (Blueprint $table) {
            $table->increments('id_detailmk');

            $table->unsignedBigInteger('id_mk_fk')->nullable();
            $table->unsignedBigInteger('id_cpl_fk')->nullable();
            $table->unsignedBigInteger('id_cpmk_fk')->nullable();

            $table->string('indikator_pencapaian', 200)->nullable();
            $table->integer('bobot_detailmk')->default(0);

            $table->timestamps();
            $table->softDeletes(); 

            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_detailmk');
    }
}
