<?php

namespace App\Helpers\SubCpmkHelpers;

use App\Models\MataKuliahModel;
use App\Models\SubCpmkModel;
use Throwable;

/**
 * Helper untuk manajemen sub cpmk
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_subcpmk
 *
 * @author  Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class SubCpmkHelper
{
    private $SubCpmkModel;
    private $MatakuliahModel;

    public function __construct()
    {
        $this->SubCpmkModel = new SubCpmkModel();
        $this->MatakuliahModel = new MataKuliahModel();
    }
     /**
     * Mengambil data sub cpmk dari tabel m_subcpmk
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nama_kurikulum'] = string
     * $filter['periode'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->SubCpmkModel->getAll($filter, $itemPerPage, $sort);
    }
    /**
     * Mengambil data mk dari tabel m_matakuliah
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * $filter['nama_kurikulum'] = string
     * $filter['periode'] = string
     * @param integer $itemPerPage jumlah data yang tampil dalam 1 halaman, kosongi jika ingin menampilkan semua data
     * @param string $sort nama kolom untuk melakukan sorting mysql beserta tipenya DESC / ASC
     *
     * @return object
     */
    public function getAllMk(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        return $this->MatakuliahModel->getAllMk($filter, $itemPerPage, $sort);
    }

     /**
     * method untuk menginput , update dan delete ke tabel m_subcpmk
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param array 
     *
     * @return array
     */
    public function create(array $payload): array
    {
        try {

            if(isset($payload['delete_subcpmk']) && $payload['delete_subcpmk'] !== 0)
            {
                foreach ($payload['delete_subcpmk'] as $val) {
                    $subCpmk = $this->SubCpmkModel->drop($val['id_subcpmk']);
                }

            }

            foreach ($payload['detail_subcpmk'] as $val) {
                $val['id_mk_fk'] = $payload['id_mk_fk'];
                $val['id_detailmk_fk'] = $payload['id_detailmk_fk'];
                if($val['id_subcpmk'] === 0)
                {
                    $subCpmk =  $this->SubCpmkModel->store($val);
                }else{
                    $subCpmk =  $this->SubCpmkModel->edit($val, $val['id_subcpmk'] );
                    
                }
            }
            
            return [
                'status' => true,
                'data' => $subCpmk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
         * method untuk merubah availability
         *
         * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
         *
         * @param array 
         *
         * @return array
     */
    public function submit(array $payload): array
    {
        try {
            $subCpmk  =  $this->SubCpmkModel->submit($payload);
            
            return [
                'status' => true,
                'data' => $subCpmk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
         * method untuk merubah status berkas / status_penilaian sub_cpmk
         *
         * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
         *
         * @param array 
         *
         * @return array
     */
    public function updateStatus(array $payload): array
    {
        try {
            $subCpmk  =  $this->SubCpmkModel->updateStatus($payload);
            
            return [
                'status' => true,
                'data' => $subCpmk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
         * method untuk merubah status penilaian menjadi diterima
         *
         * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
         *
         * @param array 
         *
         * @return array
     */
    public function diterima(array $payload): array
    {
        try {
            $subCpmk  =  $this->SubCpmkModel->diterima($payload);
            
            return [
                'status' => true,
                'data' => $subCpmk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
         * method untuk merubah status penilaian menjadi ditolak
         *
         * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
         *
         * @param array 
         *
         * @return array
     */
    public function ditolak(array $payload): array
    {
        try {
            $subCpmk  =  $this->SubCpmkModel->ditolak($payload);
            
            return [
                'status' => true,
                'data' => $subCpmk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }


    
 
}