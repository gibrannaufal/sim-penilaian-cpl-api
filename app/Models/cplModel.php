<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class cplModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    /**
     * deklarasi tabel m_kurikulum
     *
     * @var string
    */
    protected $table = 'm_cpl';

     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_cpl';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $fillable = [
        'id_kurikulum_fk',
        'kode_cpl',
        'deskripsi_cpl',
    ];
    public function store(array $payload)
    {
        return $this->create($payload);
    }

    public function edit(array $payload, int $id)
    {
        return $this->find($id)->update($payload);
    }
    public function dropByKurikulumId(int $kurikulumId)
    {
        return $this->where('id_kurikulum_fk', $kurikulumId)->delete();
    }
    
    public function drop(int $id)
    {
        return $this->find($id)->delete();
    }
}
