<?php

namespace App\Helpers\PenilaianMkHelpers;


use App\Models\MataKuliahModel;
use App\Models\PenilaianMkModel;
use Throwable;

/**
 * Helper untuk manajemen mata kuliah
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_matakuliah
 *
 * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class PenilaianMkHelpers 
{
    private $mataKuliah;
    private $penilaian;

    public function __construct()
    {
        $this->mataKuliah = new MataKuliahModel();
        $this->penilaian = new PenilaianMkModel();
    }

    /**
     * Mengambil data kurikulum dari tabel m_kurikulum
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nama_kurikulum'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->mataKuliah->getAll($filter, $itemPerPage, $sort);
    }

         /**
     * method untuk menginputkan nilai atau mengupdate nilai yang sudah ada
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param array 
     *
     * @return array
     */
    public function store(array $payload): array
    {
        try {
            // dd($payload['penilaian']);
            foreach ($payload['penilaian'] as $val) {
                if($val['id_penilaian'] === 0)
                {
                    $penilaian =  $this->penilaian->store($val);
                }else{
                    $penilaian =  $this->penilaian->edit($val, $val['id_penilaian'] );
                    
                }
            }
            
            return [
                'status' => true,
                'data' => $penilaian
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
  

   
}