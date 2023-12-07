<?php

namespace App\Helpers\KurikulumHelpers;

use App\Models\cplModel;
use App\Models\KurikulumModel;
use Throwable;

/**
 * Helper untuk manajemen kurikulum
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_kurikulum
 *
 * @author Wahyu Agung <wahyuagung26@gmail.com>
 */
class KurikulumHelper 
{
    private $kurikulumModel;
    private $cplModel;

    public function __construct()
    {
        $this->kurikulumModel = new KurikulumModel();
        $this->cplModel  = new cplModel();
    }

    /**
     * Mengambil data kurikulum dari tabel m_kurikulum
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
        return $this->kurikulumModel->getAll($filter, $itemPerPage, $sort);
    }

    /**
     * method untuk menginput data baru ke tabel m_kurikulum
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

            $kurikulum = $this->kurikulumModel->store($payload);

            $this->insertCpl($payload['cpl'] ?? [], $kurikulum->id_kurikulum);

            return [
                'status' => true,
                'data' => $kurikulum
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

     /**
     * Mengambil spesifik product dari tabel m_kurikulum
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param int $id id_kurikulum dari tabel m_kurikulum
     *
     * @return array
     */
    public function getById(int $id): array
    {
        $kurikulum = $this->kurikulumModel->getById($id);
        if (empty($kurikulum)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $kurikulum
        ];
    }

      /**
     * Delete data product & product detail
     *
     * @param integer $productId
     * @return void
     */
    public function delete(int $kurikulumId)
    {
        try {

            $this->kurikulumModel->drop($kurikulumId);

            $this->cplModel->dropByKurikulumId($kurikulumId);

            return [
                'status' => true,
                'data' => $kurikulumId
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    /**
     * method untuk mengubah data product di table m_kurikulum
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

            // dd($payload['cpl']);
            $this->kurikulumModel->edit($payload, $payload['id_kurikulum']);

            $this->updateCpl($payload['cpl'] ?? [], $payload['id_kurikulum']);
            
            $this->deleteCpl($payload['cpl_deleted'] ?? []);

            $kurikulum = $this->getById($payload['id_kurikulum']);

            return [
                'status' => true,
                'data' => $kurikulum['data']
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
    private function deleteCpl(array $kurikulum)
    {
        if (empty($kurikulum)) {
            return false;
        }
        
        foreach ($kurikulum as $val) {
            $this->cplModel->drop($val['id_cpl']);
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
    private function insertCpl(array $cpl, int $kurikulumId)
    {
        if (empty($cpl)) {
            return false;
        }
        foreach ($cpl as $val) {
            $val['id_kurikulum_fk'] = $kurikulumId;
            $this->cplModel->store($val);
        }
    }

    /**
     * method untuk update  data pada child table yaitu cpl di table m_cpl
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@email.com>
     *
     *
     * @return array
     */
    private function updateCpl(array $cpl, int $kurikulumId)
    {
        if (empty($cpl)) {
            return false;
        }

        foreach ($cpl as $val) {
            if(isset($val['id_cpl']))
            {
                $this->cplModel->edit($val, $val['id_cpl']);
            }else{
                $val['id_kurikulum_fk'] = $kurikulumId;
                $this->cplModel->store($val);
            }
            
        }
    }
  

   
}
