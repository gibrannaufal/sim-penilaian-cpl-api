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

    private function updateCpl(array $cpl, int $kurikulumId)
    {
        if (empty($cpl)) {
            return false;
        }
        foreach ($cpl as $val) {
           
            $this->cplModel->edit($val, $val['id']);
            
        }
    }
  

   
}
