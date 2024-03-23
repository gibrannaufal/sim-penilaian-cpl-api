<?php

namespace App\Helpers\EvaluasiCplHelpers;

use Throwable;
use App\Models\CpmkModel;
use App\Models\EvaluasiCplModel;
use Illuminate\Support\Facades\DB;

/**
 * Helper untuk manajemen Evaluasi CPL 
 * Mengambil data dari tabel m_penilaian
 *
 * @author  Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class EvaluasiCplHelper
{
    private $evaluasiCpl;

    public function __construct()
    {
        $this->evaluasiCpl = new EvaluasiCplModel();
    }
     /**
     * Mengambil data  mahasiswa dari tabel m_penilaian
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nama_mahasiswa'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->evaluasiCpl->getAll($filter, $itemPerPage, $sort);
    }


     /**
     * Mengambil data  rekap nilai dari tabel m_penilaian
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nama_mahasiswa'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function rekapNilai(array $filter): object
    {
        $penilaian  = $this->evaluasiCpl->rekapNilai($filter);

        $matakuliah  = DB::table('m_matakuliah')->select('*')->get();

        $cpl  = DB::table('m_cpl')->select('*')->get();

        $array = [];

        foreach ($cpmk as $cpmks) {
            // Memastikan array untuk setiap id_cpl_fk sudah ada
            if (!isset($arrayCpmk[$cpmks->id_cpl_fk])) {
                $arrayCpmk[$cpmks->id_cpl_fk] = [];
            }   
            // Menambahkan data cpmk ke array sesuai dengan id_cpl_fk-nya
            $arrayCpmk[$cpmks->id_cpl_fk][] = $cpmks;
        }

        foreach($cpl as $key => $cpls)
        {
            if(isset($arrayCpmk[$cpls->id_cpl]))
			{  
                $cpl[$key]->cpmk = array_values($arrayCpmk[$cpls->id_cpl]);
            }

        }


        return $matakuliah;
    }

}