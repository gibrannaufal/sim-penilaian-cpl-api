<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

class UserRolesModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    /**
     * deklarasi tabel m_kurikulum
     *
     * @var string
    */
    protected $table = 'user_roles';

     /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;
    
    protected $fillable = [
        'id',
        'name',
        'access',
    ];
    
    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $mk = $this->query();

        if (!empty($filter['name'])) {
            $mk->where('name', 'LIKE', '%'.$filter['name'].'%');
        }

        $sort = $sort ?: 'id ASC';
        $mk->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;
        
        return $mk->paginate($itemPerPage)->appends('sort', $sort);
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
