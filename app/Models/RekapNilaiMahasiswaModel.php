<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RekapNilaiMahasiswaModel extends Model
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

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $penilaian = $this->query()
        ->selectRaw('nrp, nama, ROUND(SUM(t_totalnilai_cpmk.total_nilai), 2) AS total_nilai')
        ->groupBy('nrp', 'nama')->where('id_mk_fk', '=', $filter['id_mk_fk']);

        // dd($penilaian);
        
        if (!empty($filter['nama_mahasiswa'])) {
            $penilaian->where('nama', 'LIKE', '%'.$filter['nama_mahasiswa'].'%');
        }

        $sort = $sort ?: 'id_totalnilai DESC';
        $penilaian->orderByRaw($sort);
        
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $penilaian->paginate($itemPerPage)->appends('sort', $sort);
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

    // dengan paginasi untuk fitur rekap nilai mahasiswa 
    public function rekapNilaiMahasiswa(array $filter,  int $itemPerPage = 0, string $sort = ''): object
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
        ) ;
        
        if (!empty($filter['nama_matakuliah'])) {
            $penilaian->where('m_matakuliah.nama_matakuliah', 'LIKE', '%'.$filter['nama_matakuliah'].'%');
        }

        $sort = $sort ?: 'id_totalnilai DESC';
        $penilaian->orderByRaw($sort);
        
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $penilaian->paginate($itemPerPage)->appends('sort', $sort);

        // return $penilaian;
    
    }
}
