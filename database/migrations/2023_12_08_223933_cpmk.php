<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Cpmk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_cpmk', function (Blueprint $table) {
            $table->increments('id_cpmk');
            
            $table->unsignedBigInteger('id_kurikulum_fk')->nullable();
            $table->unsignedBigInteger('id_cpl_fk')->nullable();
            
            $table->string('kode_cpmk', 20)->nullable();
            $table->string('deskripsi_cpmk', 20)->nullable();

            $table->timestamps();
            $table->softDeletes(); // Generate deleted_at
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
            $table->integer('deleted_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_cpmk');
    }
}
