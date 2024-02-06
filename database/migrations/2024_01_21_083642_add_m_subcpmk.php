<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMSubcpmk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_subcpmk', function (Blueprint $table) {
            $table->increments('id_subcpmk');

            $table->string('kode_subcpmk', 200)->nullable();
            $table->string('nama_subcpmk', 200)->nullable();
            $table->string('indikator_pencapaian', 200)->nullable();

            // metode_pembelajaran
            $table->integer('diskusi_kelompok')->default(0);
            $table->integer('simulasi')->default(0);
            $table->integer('studi_kasus')->default(0);
            $table->integer('kaloboratif')->default(0);
            $table->integer('kooperatif')->default(0);
            $table->integer('berbasis_proyek')->default(0);
            $table->integer('berbasis_masalah')->default(0);

            // teknik_penilaian
            $table->integer('partisipasi')->default(0);
            $table->integer('tugas')->default(0);
            $table->integer('presentasi')->default(0);
            $table->integer('tes_tulis')->default(0);
            $table->integer('tes_lisan')->default(0);
            $table->integer('tugas_kelompok')->default(0);

            // instrumen penilaian
            $table->string('instrumen_penilaian', 500)->nullable();

            // pertemuan
            $table->string('pertemuan', 200)->nullable();


            $table->unsignedBigInteger('id_mk_fk')->nullable();
            $table->unsignedBigInteger('id_detailmk_fk')->nullable();

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
        Schema::dropIfExists('m_subcpmk');
    }
}
