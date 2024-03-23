<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class PenilaianMkModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    
    /**
     * deklarasi tabel m_matakuliah
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

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $fillable = [
        'id_detailmk_fk',
        'id_mk_fk',
        'id_subcpmk_fk',
        'nrp',
        'nama',
        'prodi',
        'partisipasi',
        'tugas',
        'presentasi',
        'tes_tulis',
        'tes_lisan',
        'tugas_kelompok',
        'total_nilai'
    ];
    
    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }
    
    public function getById(int $id)
    {
        return $this->find($id);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function getTotalNilai(array $payload)
    {
        $total_nilai = $this->where('id_mk_fk', $payload['id_mk_fk'])
                   ->where('id_detailmk_fk', $payload['id_detailmk_fk'])
                   ->selectRaw('ROUND(SUM(total_nilai), 2) as total_nilai, nrp, nama')
                   ->groupBy('nrp', 'nama')
                   ->get();

        return $total_nilai;

    }

}
