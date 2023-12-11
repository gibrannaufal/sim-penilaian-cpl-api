<?php

namespace App\Helpers\CpmkHelpers;

use App\Models\CpmkModel;
use Throwable;

/**
 * Helper untuk manajemen cpmk
 * Mengambil data, menambah, mengubah, & menghapus ke tabel m_cpmk
 *
 * @author  Muhammad Naufal Gibran <naufalgibran961@gmail.com>
 */
class CpmkHelper
{
    private $cpmkModel;

    public function __construct()
    {
        $this->cpmkModel = new CpmkModel();
    }
     /**
     * Mengambil data kurikulum dari tabel m_cpmk
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
        return $this->cpmkModel->getAll($filter, $itemPerPage, $sort);
    }

     /**
     * method untuk menginput data baru ke tabel m_cpmk
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

            // $kurikulum = $this->cpmkModel->store($payload);

            // $this->insertCpl($payload['cpl'] ?? [], $kurikulum->id_kurikulum);

            // if (empty($cpmk['detail_cpmk'])) {
            //     return false;
            // }

            foreach ($payload['detail_cpmk'] as $val) {
                $val['id_kurikulum_fk'] = $payload['id_kurikulum_fk'];
                $val['id_cpl_fk'] = $payload['id_cpl_fk'];
                $cpmk =  $this->cpmkModel->store($val);
            }

            return [
                'status' => true,
                'data' => $cpmk
            ];
        } catch (Throwable $th) {

            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }
    
    /**
     * Mengambil spesifik cpmk dari tabel m_cpmk
     *
     * @author Muhammad Naufal Gibran <naufalgibran961@gmail.com>
     *
     * @param int $id id_cpmk dari tabel m_cpmk
     *
     * @return array
     */
    public function getById(int $id): array
    {
        $cpmk = $this->cpmkModel->getById($id);
        if (empty($cpmk)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $cpmk
        ];
    }

    /**
         * Delete data cpmk
         *
         * @param integer $cpmkid
         * @return void
     */
    public function delete(int $cpmkId)
    {
        try {

            $this->cpmkModel->drop($cpmkId);

            return [
                'status' => true,
                'data' => $cpmkId
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

            $this->cpmkModel->edit($payload, $payload['id_cpmk']);

            $kurikulum = $this->getById($payload['id_cpmk']);

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

}