<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class detailmkModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;


    /**
     * deklarasi tabel m_detailmk
     *
     * @var string
    */
    protected $table = 'm_detailmk';
     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_detailmk';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $fillable = [
        'id_mk_fk',
        'id_cpl_fk',
        'id_cpmk_fk',
        'indikator_pencapaian',
        'bobot_detailmk',
        'status',
        'pesan'
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
         * Relasi ke tabel child m_cpmk
         *
         * @return void
    */
    public function cpmk()
    {
        return $this->hasOne(CpmkModel::class, 'id_cpmk', 'id_cpmk_fk');
    }

    public function store(array $payload)
    {
        return $this->create($payload);
    }
    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }
    public function dropByMkId(int $mkId)
    {
        return $this->where('id_mk_fk', $mkId)->delete();
    }
    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }
}
