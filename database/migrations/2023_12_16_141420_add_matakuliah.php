<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMatakuliah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_matakuliah', function (Blueprint $table) {
            $table->increments('id_matakuliah');
            
            $table->unsignedBigInteger('id_kurikulum_fk')->nullable();

            $table->string('nama_matakuliah', 200)->nullable();
            $table->string('kode_matakuliah', 200)->nullable();
            $table->string('deskripsi', 200)->nullable();
            $table->string('sks', 200)->nullable();
            $table->integer('bobot')->default(0);
            $table->string('semester', 200)->nullable();
            $table->string('bobot_kajian', 200)->nullable();

            $table->timestamps();
            $table->softDeletes(); 

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
        Schema::dropIfExists('m_matakuliah');
    }
}
