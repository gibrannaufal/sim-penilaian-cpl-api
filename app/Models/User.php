<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Repository\ModelInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\RecordSignature;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;


class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes, RecordSignature, HasRelationships;
        /**
     * Menentukan nama tabel yang terhubung dengan Class ini
     *
     * @var string
     */
    protected $table = 'users';

      /**
     * Menentukan primary key, jika nama kolom primary key adalah "id",
     * langkah deklarasi ini bisa dilewati
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_roles_id',
        'alamat',
        'jenis_kelamin',
        'prodi',
        'nrp',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id' => $this->id,
                'email' => $this->email,
                'updated_security' => $this->updated_security
            ]
        ];
    }

    /**
         * Relasi ke tabel child m_cpl
         *
         * @return void
    */
    public function userRoles()
    {
        return $this->hasOne(UserRolesModel::class, 'id', 'user_roles_id');
    }


    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $mk = $this->query();

        if (!empty($filter['nama'])) {
            $mk->where('name', 'LIKE', '%'.$filter['nama'].'%');
        }
        if (!empty($filter['nrp'])) {
            $mk->where('nrp', 'LIKE', '%'.$filter['nrp'].'%');
        }

        $sort = $sort ?: 'id DESC';
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

    // rubah roles jika roles sudah dihapus
    public function editRoles(array $payload, int $id)
    {
        return $this->where('user_roles_id', $id)->update($payload);
    }
    


}
