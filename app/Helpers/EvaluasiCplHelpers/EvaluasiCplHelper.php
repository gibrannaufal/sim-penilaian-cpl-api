<?php

namespace App\Helpers\EvaluasiCplHelpers;

use App\Models\cplModel;
use Throwable;
use App\Models\CpmkModel;
use App\Models\detailmkModel;
use App\Models\EvaluasiCplModel;
use App\Models\KurikulumModel;
use App\Models\MataKuliahModel;
use App\Models\PenilaianMkModel;
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
        try {
            /**
             * Mengambil data list cpl untuk thead 
             *
             */

            //ambil kurikulum
            $kurikulum  = KurikulumModel::select(
                'm_kurikulum.id_kurikulum',
                'm_kurikulum.nama_kurikulum',
            )->where('m_kurikulum.status', '=', 'diterima');

            if (isset($filter['kurikulum']) && !empty($filter['kurikulum'])) {
                $kurikulum->where('m_kurikulum.id_kurikulum', '=', $filter['kurikulum']);
            }

            $kurikulum = $kurikulum->orderBy('id_kurikulum', 'desc')->first();

            if (!$kurikulum) {
                throw new \Exception('Kurikulum tidak ditemukan.');
            }

            // ambil matakuliah di kurikulum tertentu
            $listMk = MataKuliahModel::select(
                'm_matakuliah.id_matakuliah',
                'm_matakuliah.id_kurikulum_fk',
            )
            ->where('m_matakuliah.id_kurikulum_fk', '=', $kurikulum['id_kurikulum']);

            $listMk = $listMk->orderBy('id_matakuliah', 'asc')->get();

            if ($listMk->isEmpty()) {
                throw new \Exception('Tidak ada mata kuliah yang ditemukan untuk kurikulum ini.');
            }

            // cari detail mk yang memiliki id_mk_fk diatas 
            $arrListMk  = [];
            foreach ($listMk as $key => $value) {
                $arrListMk[$value['id_matakuliah']] = $value['id_matakuliah'];
            }

            $listCpl = detailmkModel::select(
                'm_detailmk.id_cpl_fk',
                detailmkModel::raw('SUM(m_detailmk.bobot_detailmk) AS total_cpl')
            )
            ->whereIn('m_detailmk.id_mk_fk', array_values($arrListMk));

            $listCpl = $listCpl->orderBy('id_cpl_fk', 'asc')->groupBy('m_detailmk.id_cpl_fk')->get();
            
            if ($listCpl->isEmpty()) {
                throw new \Exception('Tidak ada cpl yang ditemukan untuk kurikulum ini.');
            }

            // ambil nama cpl nya

            $arrListCpl  = [];
            foreach ($listCpl as $key => $value) {
                $arrListCpl[$value['id_cpl_fk']] = $value['total_cpl'];
            }

            $listKodeCpl = cplModel::select(
                'm_cpl.kode_cpl',
                'm_cpl.id_cpl',
            )
            ->whereIn('m_cpl.id_cpl', array_keys($arrListCpl));

            $listKodeCpl = $listKodeCpl->orderBy('id_cpl', 'asc')->get();

            $listHeader = [];
            $totaL_cpl_all = 0;
            $totaL_capaian_all = 0;
            
            foreach ($listKodeCpl as $key => $value) {
                if(isset($arrListCpl[$value['id_cpl']]))
                {
                    $listHeader[] = [
                        'kode_cpl' => $value['kode_cpl'],
                        'total_skor' =>  $arrListCpl[$value['id_cpl']],
                        'capaian' => 100,
                    ];
                }
                $totaL_cpl_all += $listHeader[$key]['total_skor'];
                $totaL_capaian_all += $listHeader[$key]['capaian'];
            }
            
             /**
             * end
             */
            
              /**
             * Mengambil data list nilai mahasiswa 
             *
             */

             $listPenilaian = DB::table('t_totalnilai_cpmk')
             ->select(
                't_totalnilai_cpmk.nrp',
                't_totalnilai_cpmk.nama',
                't_totalnilai_cpmk.total_nilai',
                'm_cpl.kode_cpl',
                'm_cpl.id_cpl',
            )
            ->whereIn('m_cpl.id_cpl', array_keys($arrListCpl))
            ->join('m_detailmk', 'm_detailmk.id_detailmk', '=', 't_totalnilai_cpmk.id_detailmk_fk')
            ->join('m_cpl', 'm_cpl.id_cpl', '=', 'm_detailmk.id_cpl_fk');

            $listPenilaian = $listPenilaian->orderBy('nrp', 'asc')->get();
            
            if ($listPenilaian->isEmpty()) {
                throw new \Exception('Tidak ada nilai yang ditemukan untuk kurikulum ini.');
            }


            // pengambilan total nilai pada listHeader index kode_cpl
            $arrTotalNilai  = [];
            foreach ($listHeader as $key => $value) {
                $arrTotalNilai[$value['kode_cpl']] = $value['total_skor'];
            }

            $arrMahasiswa = [];
            // $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl] += $value->total_nilai;

            // penggabungan nrp dengan total_nilai_cpl
            foreach ($listPenilaian as $value) {
                
                if (isset($arrMahasiswa[$value->nrp])) {
                    if (isset($arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl])) {
                        $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl]['total_skor'] += $value->total_nilai;
                        if(isset($arrTotalNilai[$value->kode_cpl]))
                        {
                            // $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl]['capaian_cpl'] += ($value->total_nilai / $arrTotalNilai[$value->kode_cpl]) * 100;
                            $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl]['capaian_cpl'] += round(($value->total_nilai / $arrTotalNilai[$value->kode_cpl]) * 100, 2);

                        }
                    } else {
                        $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl]['total_skor'] =  $value->total_nilai ;
                        if(isset($arrTotalNilai[$value->kode_cpl]))
                        {
                            // $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl]['capaian_cpl'] =  ($value->total_nilai / $arrTotalNilai[$value->kode_cpl]) * 100;
                            $arrMahasiswa[$value->nrp]['detail'][$value->kode_cpl]['capaian_cpl'] =  round(($value->total_nilai / $arrTotalNilai[$value->kode_cpl]) * 100, 2);
                        }
                    }
                } else {
                    $arrMahasiswa[$value->nrp] = [
                        'nama' => $value->nama,
                        'nrp' => $value->nrp,
                        'detail' => [
                            $value->kode_cpl => [
                                'total_skor' => $value->total_nilai,
                                // 'capaian_cpl' => (isset($arrTotalNilai[$value->kode_cpl])) ? ($value->total_nilai / $arrTotalNilai[$value->kode_cpl]) * 100 : null
                                'capaian_cpl' => (isset($arrTotalNilai[$value->kode_cpl])) ? round(($value->total_nilai / $arrTotalNilai[$value->kode_cpl]) * 100, 2) : null
                            ]
                        ],
                        'totalSkorAll' => 0,
                        'totalCapaianAll' => 0,
                    ];
                }

            }

            foreach ($arrMahasiswa as &$mahasiswa) {
                $mahasiswa['totalSkorAll'] = number_format(array_sum(array_column($mahasiswa['detail'], 'total_skor')), 2, '.', '');
                $mahasiswa['totalCapaianAll'] = number_format(array_sum(array_column($mahasiswa['detail'], 'capaian_cpl')), 2, '.', '');
            }
        
            // rubah menjadi array bukan object
          
            return [
                'listHeader' => $listHeader,
                'listNilai' => array_values($arrMahasiswa),
                'total_cpl_all' => $totaL_cpl_all,
                'total_capaian_all' => $totaL_capaian_all,

            ];
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
            ];
        }
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

        if (isset($filter['kurikulum']) && !empty($filter['kurikulum'])) {
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
        foreach ($totalCplDidapat as $key => $val) {
            if (isset($arrListDetail[$val['id_cpl_fk']])) {
                $totalCplDidapat[$key]['total_cpl_all'] = $arrListDetail[$val['id_cpl_fk']];
            }
            if (isset($arrListKodeCpl[$val['id_cpl_fk']])) {
                $totalCplDidapat[$key]['kode_cpl'] = $arrListKodeCpl[$val['id_cpl_fk']];
            }
        }


        return $totalCplDidapat;
    }
}
