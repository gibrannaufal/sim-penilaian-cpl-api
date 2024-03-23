<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class EvaluasiCplModel extends Model
{
    use  HasFactory;

    /**
     * deklarasi tabel m_kurikulum
     *
     * @var string
    */
    protected $table = 'm_penilaian';

     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_penilaian';

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $penilaian = $this->query()->selectRaw('nrp, nama, prodi, MAX(id_penilaian) as id_penilaian')->groupBy('nrp', 'nama', 'prodi');

        // dd($penilaian);
        
        if (!empty($filter['nama_mahasiswa'])) {
            $penilaian->where('nama', 'LIKE', '%'.$filter['nama_mahasiswa'].'%');
        }

        $sort = $sort ?: 'id_penilaian DESC';
        $penilaian->orderByRaw($sort);
        
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $penilaian->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function rekapNilai(array $filter): object
    {
        $penilaian = $this->where('m_penilaian.nrp', '=', $filter['nrp'])
        ->select('m_penilaian.*')
        ->get();
        
        return $penilaian;
    
    }

}
