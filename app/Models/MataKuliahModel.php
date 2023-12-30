<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class MataKuliahModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    
    /**
     * deklarasi tabel m_matakuliah
     *
     * @var string
    */
    protected $table = 'm_matakuliah';
     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_matakuliah';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $fillable = [
        'id_kurikulum_fk',
        'id_cpl_fk',
        'nama_matakuliah',
        'kode_matakuliah',
        'deskripsi',
        'sks',
        'bobot',
        'semester',
        'bobot_kajian'
    ];
    /**
         * Relasi ke tabel child m_kurikulum
         *
         * @return void
    */
    public function kurikulum()
    {
        return $this->hasOne(KurikulumModel::class, 'id_kurikulum', 'id_kurikulum_fk');
    }

     /**
     * Relasi ke tabel child m_detailmk
     *
     * @return void
     */
    public function detailMk()
    {
        return $this->hasMany(detailmkModel::class, 'id_mk_fk', 'id_matakuliah');
    }

     
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $mk = $this->query();

        if (!empty($filter['nama_matakuliah'])) {
            $mk->where('nama_matakuliah', 'LIKE', '%'.$filter['nama_mk'].'%');
        }

        $sort = $sort ?: 'id_matakuliah DESC';
        $mk->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $mk->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }

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


}
