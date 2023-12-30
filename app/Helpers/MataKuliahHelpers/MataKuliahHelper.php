<?php

namespace App\Helpers\MataKuliahHelpers;

use App\Models\cplModel;
use App\Models\detailmkModel;
use App\Models\MataKuliahModel;
use Throwable;

/**
 * Helper untuk manajemen mata kuliah
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_matakuliah
 *
 * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class MataKuliahHelper 
{
    private $mataKuliah;
    private $mkDetail;

    public function __construct()
    {
        $this->mataKuliah = new MataKuliahModel();
        $this->mkDetail = new detailmkModel();
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
     * method untuk menginput data baru ke tabel m_matakuliah
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

            $mk = $this->mataKuliah->store($payload);

            $this->insertDetail($payload['mk_detail'] ?? [], $mk->id_matakuliah);

            return [
                'status' => true,
                'data' => $mk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

     /**
     * Mengambil spesifik kurikulum dari tabel m_kurikulum
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param int $id id_kurikulum dari tabel m_kurikulum
     *
     * @return array
     */
    public function getById(int $id): array
    {
        $mk = $this->mataKuliah->getById($id);
        if (empty($mk)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $mk
        ];
    }

      /**
     * Delete data kurikulum & cpl
     *
     * @param integer $cpmkId
     * @return void
     */
    public function delete(int $mkId)
    {
        try {

            $this->mataKuliah->drop($mkId);

            $this->mkDetail->dropByMkId($mkId);

            return [
                'status' => true,
                'data' => $mkId
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah data Kurikulum di table m_kurikulum
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     * @param array $payload
     *                       $payload['name'] = string
     *
     * @return array
     */
    public function update(array $payload): array
    {
        try {

            $this->mataKuliah->edit($payload, $payload['id_matakuliah']);

            $payload['id_mk_fk'] =  $payload['id_matakuliah'];
            $this->updateMk($payload['mk_detail'] ?? [], $payload['id_mk_fk']);
            
            // dd($payload['mk_detail_deleted']);
            $this->deleteMkDetail($payload['mk_detail_deleted'] ?? []);

            $mk = $this->getById($payload['id_matakuliah']);
            
            return [
                'status' => true,
                'data' => $mk['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

      /**
     * method untuk delete  data pada child table yaitu cpl di table m_cpl
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     *
     * @return array
     */
    private function deleteMkDetail(array $mkDetail)
    {
        if (empty($mkDetail)) {
            return false;
        }
        
        foreach ($mkDetail as $val) {
            $this->mkDetail->drop($val['id_detailmk']);
        }
    }

    /**
     * method untuk menambahkan  data pada child table yaitu cpl di table m_cpl
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     *
     * @return array
     */
    private function insertDetail(array $detail, int $mkId)
    {
        if (empty($detail)) {
            return false;
        }
        foreach ($detail as $val) {
            $val['id_mk_fk'] = $mkId;
            $this->mkDetail->store($val);
        }
    }

    /**
     * method untuk update  data pada child table yaitu cpl di table m_Matakuliah
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     *
     * @return array
     */
    private function updateMk(array $mkDetail, int $idMk)
    {
        if (empty($mkDetail)) {
            return false;
        }
        // dd($mkDetail);
        foreach ($mkDetail as $val) {
            if(isset($val['id_detailmk']))
            {
                $this->mkDetail->edit($val, $val['id_detailmk']);
            }else{
                $val['id_mk_fk'] = $idMk;
                $this->mkDetail->store($val);
            }
            
        }
    }
  

   
}