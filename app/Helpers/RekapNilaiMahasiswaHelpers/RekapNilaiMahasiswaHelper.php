<?php

namespace App\Helpers\RekapNilaiMahasiswaHelpers;

use Throwable;
use App\Models\CpmkModel;
use App\Models\EvaluasiCplModel;
use App\Models\RekapNilaiMahasiswaModel;
use Illuminate\Support\Facades\DB;

/**
 * Helper untuk manajemen rekap nilai mahasiswa
 * Mengambil data dari tabel m_penilaian
 *
 * @author  Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class RekapNilaiMahasiswaHelper
{
    private $rekapNilai;

    public function __construct()
    {
        $this->rekapNilai = new RekapNilaiMahasiswaModel();
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
        return $this->rekapNilai->getAll($filter, $itemPerPage, $sort);
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
        $penilaian  = $this->rekapNilai->rekapNilai($filter);

        return $penilaian;
    }

    /**
     * get by nrp 
     * Mengambil data  rekap nilai dari tabel m_penilaian  menggunakan paginasi
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
    public function rekapNilaiMahasiswa(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $penilaian  = $this->rekapNilai->rekapNilaiMahasiswa($filter, $itemPerPage, $sort);

        return $penilaian;
    }

}