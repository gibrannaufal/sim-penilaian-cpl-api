<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class EvaluasiCplModel extends Model
{
    use  HasFactory;

    /**
     * deklarasi tabel m_kurikulum
     *
     * @var string
    */
    protected $table = 't_totalnilai_cpmk';

     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_totalnilai';

    public function totalCplDidapat(array $filter): object
    {
        $penilaian = $this->query()
        ->select(
            't_totalnilai_cpmk.nrp', 
            't_totalnilai_cpmk.nama', 
            'm_detailmk.id_cpl_fk',
            DB::raw('SUM(m_detailmk.bobot_detailmk) AS total_cpl_didapat') 

        )
        ->leftJoin('m_detailmk', 't_totalnilai_cpmk.id_detailmk_fk', '=', 'm_detailmk.id_detailmk');
        
        if (!empty($filter['nama_mahasiswa'])) {
            $penilaian->where('nama', 'LIKE', '%'.$filter['nama_mahasiswa'].'%');
        }
        if (!empty($filter['nrp'])) {
            $penilaian->where('nrp', 'LIKE', '%'.$filter['nrp'].'%');
        }

        return $penilaian->groupBy('t_totalnilai_cpmk.nrp','m_detailmk.id_cpl_fk')->get();
    }

    public function rekapNilai(array $filter): object
    {
        $penilaian = $this->query()
        ->select(
            't_totalnilai_cpmk.id_mk_fk', 
            't_totalnilai_cpmk.id_detailmk_fk', 
            't_totalnilai_cpmk.nrp', 
            't_totalnilai_cpmk.nama', 
            'm_matakuliah.nama_matakuliah' , 
            'm_cpmk.deskripsi_cpmk', 
            'm_cpl.deskripsi_cpl',
            DB::raw('SUM(t_totalnilai_cpmk.total_nilai) AS total_nilai') 
            )
        ->leftJoin('m_matakuliah', 'm_matakuliah.id_matakuliah', '=', 't_totalnilai_cpmk.id_mk_fk')
        ->leftJoin('m_detailmk', 't_totalnilai_cpmk.id_detailmk_fk', '=', 'm_detailmk.id_detailmk')
        ->leftJoin('m_cpl', 'm_cpl.id_cpl', '=', 'm_detailmk.id_cpl_fk')
        ->leftJoin('m_cpmk', 'm_cpmk.id_cpmk', '=', 'm_detailmk.id_cpmk_fk')
        ->where('nrp', $filter['nrp'])
        ->groupBy(
            't_totalnilai_cpmk.id_mk_fk'
        ) 
        ->get();
        
        return $penilaian;
    
    }

}
