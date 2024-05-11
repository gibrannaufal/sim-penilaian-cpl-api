<?php

namespace App\Helpers\EvaluasiCplHelpers;

use Throwable;
use App\Models\CpmkModel;
use App\Models\detailmkModel;
use App\Models\EvaluasiCplModel;
use App\Models\MataKuliahModel;
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
    public function getAll(array $filter): array
    {
        // total cpl yang didapat mahasiswa
        $totalCplDidapat  = $this->evaluasiCpl->totalCplDidapat($filter);
        
        // jumlah cpl all by mk
        $listMk = MataKuliahModel::select(
            'm_matakuliah.id_matakuliah',
            'm_matakuliah.id_kurikulum_fk',
        );
    
        if(isset($filter['kurikulum']) && !empty($filter['kurikulum']))
        {
            $listMk->where('m_matakuliah.id_kurikulum_fk', '=', $filter['kurikulum']);
        }
    
        $listMk = $listMk->orderBy('id_matakuliah', 'asc')->get();
    
        $listMkArray = [];
        foreach ($listMk as $item) {
            $listMkArray[$item->id_matakuliah] = $item->id_matakuliah;
        }

        // ambil dan hitung jumlah cpl all nya 
        $listDetailMk = detailmkModel::select(
            'm_cpl.kode_cpl',
            'm_detailmk.id_cpl_fk',
            detailmkModel::raw('SUM(m_detailmk.bobot_detailmk) AS total_cpl')
        )
        ->leftJoin('m_cpl', 'm_cpl.id_cpl', '=', 'm_detailmk.id_cpl_fk')
        ->whereIn('m_detailmk.id_mk_fk', array_keys($listMkArray));

        $listDetailMk = $listDetailMk->orderBy('id_cpl_fk', 'asc')->groupBy('m_detailmk.id_cpl_fk')->get();


        $arrListDetail  = [];

        foreach ($listDetailMk as $key => $value) {
           $arrListDetail[$value['id_cpl_fk']] = $value['total_cpl'];
            
        }

        // arr final
        foreach($totalCplDidapat as $key => $val)
        {
            if(isset($arrListDetail[$val['id_cpl_fk']]))
			{ 
                $totalCplDidapat[$key]['total_cpl_all'] = $arrListDetail[$val['id_cpl_fk']];
            }else{
                $totalCplDidapat[$key]['total_cpl_all'] = $arrListDetail[$val['id_cpl_fk']];
            }
        }

        $arrFinal = [];
        foreach($totalCplDidapat as $key => $val)
        {
            $nrp = $val['nrp'];
            $id_cpl_fk = $val['id_cpl_fk'];
        
            // Inisialisasi nilai jika belum ada
            if (!isset($arrFinal[$nrp])) {
                $arrFinal[$nrp] = [
                    'nrp' => $nrp,
                    'nama' => $val['nama'],
                    'detail' => [],
                    'total_all_cpl_didapat' => 0,
                    'total_all_cpl_lulus' => 0
                ];
            }
        
            // Set nilai total_cpl_didapat dan total_cpl_all
            $arrFinal[$nrp]['detail'][$id_cpl_fk] = [
                'total_cpl_didapat' => $val['total_cpl_didapat'],
                'total_cpl_all' => $val['total_cpl_all']
            ];
        
            // Tambahkan nilai total_cpl_didapat dan total_cpl_all
            $arrFinal[$nrp]['total_all_cpl_didapat'] += $val['total_cpl_didapat'];
            $arrFinal[$nrp]['total_all_cpl_lulus'] += $val['total_cpl_all'];
        }
        
        return[
            'listCpl' => $listDetailMk,
            'arrFinal' => array_values($arrFinal),
        ] ;
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

        return $penilaian;
    }

    /**
     * Mengambil data  rekap cpl 
     *
     * @author Muhammad Naufal Gibran naufalgibran961@gmail.com
     *
     * @param  array $filter
     * @return object
     */
    public function cplMahasiswa(array $filter): object
    {
        $totalCplDidapat  = $this->evaluasiCpl->totalCplDidapat($filter);

        $listMk = MataKuliahModel::select(
            'm_matakuliah.id_matakuliah',
            'm_matakuliah.id_kurikulum_fk',
        );
    
        if(isset($filter['kurikulum']) && !empty($filter['kurikulum']))
        {
            $listMk->where('m_matakuliah.id_kurikulum_fk', '=', $filter['kurikulum']);
        }
    
        $listMk = $listMk->orderBy('id_matakuliah', 'asc')->get();
    
        $listMkArray = [];
        foreach ($listMk as $item) {
            $listMkArray[$item->id_matakuliah] = $item->id_matakuliah;
        }

        // ambil dan hitung jumlah cpl all nya 
        $listDetailMk = detailmkModel::select(
            'm_cpl.kode_cpl',
            'm_detailmk.id_cpl_fk',
            detailmkModel::raw('SUM(m_detailmk.bobot_detailmk) AS total_cpl')
        )
        ->leftJoin('m_cpl', 'm_cpl.id_cpl', '=', 'm_detailmk.id_cpl_fk')
        ->whereIn('m_detailmk.id_mk_fk', array_keys($listMkArray));

        $listDetailMk = $listDetailMk->orderBy('id_cpl_fk', 'asc')->groupBy('m_detailmk.id_cpl_fk')->get();
        
        $arrListDetail  = [];
        $arrListKodeCpl = [];

        foreach ($listDetailMk as $key => $value) {
           $arrListDetail[$value['id_cpl_fk']] = $value['total_cpl'];
            
        }
        foreach ($listDetailMk as $key => $value) {
           $arrListKodeCpl[$value['id_cpl_fk']] = $value['kode_cpl'];
            
        }

        // arr final
        foreach($totalCplDidapat as $key => $val)
        {
            if(isset($arrListDetail[$val['id_cpl_fk']]))
			{ 
                $totalCplDidapat[$key]['total_cpl_all'] = $arrListDetail[$val['id_cpl_fk']];
            }
            if(isset($arrListKodeCpl[$val['id_cpl_fk']]))
			{ 
                $totalCplDidapat[$key]['kode_cpl'] = $arrListKodeCpl[$val['id_cpl_fk']];
            }
        }


        return $totalCplDidapat;
    }

}