<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class CpmkModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    /**
     * deklarasi tabel m_kurikulum
     *
     * @var string
    */
    protected $table = 'm_cpmk';

     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_cpmk';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $fillable = [
        'id_kurikulum_fk',
        'id_cpl_fk',
        'kode_cpmk',
        'deskripsi_cpmk'
    ];

    /**
         * Relasi ke tabel child m_cpl
         *
         * @return void
    */
    public function cpl()
    {
        return $this->hasOne(cplModel::class, 'id_cpl', 'id_cpl_fk');
    }

    /**
         * Relasi ke tabel child m_kurikulum
         *
         * @return void
    */
    public function kurikulum()
    {
        return $this->hasOne(KurikulumModel::class, 'id_kurikulum', 'id_kurikulum_fk');
    }


    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $kurikulum = $this->query();

        if (!empty($filter['id_kurikulum'])) {
            $kurikulum->where('id_kurikulum_fk', 'LIKE', '%'.$filter['id_kurikulum'].'%');
        }

        $sort = $sort ?: 'id_cpmk DESC';
        $kurikulum->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $kurikulum->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function getById(int $id)
    {
        return $this->find($id);
    }


    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }

    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }

}

