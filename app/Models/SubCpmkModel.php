<?php

namespace App\Models;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Traits\RecordSignature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;


class SubCpmkModel extends Model
{
    use SoftDeletes, RecordSignature, HasRelationships, HasFactory;

    /**
     * deklarasi tabel m_kurikulum
     *
     * @var string
     */
    protected $table = 'm_subcpmk';

    /**
     * Deklarasi primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_subcpmk';

    /**
     * Akan mengisi kolom "created_at" dan "updated_at" secara otomatis,
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = [
        'kode_subcpmk',
        'nama_subcpmk',
        'indikator_pencapaian',
        'diskusi_kelompok',
        'simulasi',
        'studi_kasus',
        'kaloboratif',
        'kooperatif',
        'berbasis_proyek',
        'berbasis_masalah',
        'partisipasi',
        'tugas',
        'presentasi',
        'tes_tulis',
        'tes_lisan',
        'tugas_kelompok',
        'instrumen_penilaian',
        'pertemuan',
        'id_mk_fk',
        'id_detailmk_fk',
        'available',
        'status_penilaian',
        'pesan_penilaian'
    ];

    /**
     * Relasi ke tabel child m_matakuliah
     *
     * @return void
     */
    public function mk()
    {
        return $this->hasOne(MataKuliahModel::class, 'id_matakuliah', 'id_mk_fk');
    }

    /**
     * Relasi ke tabel child m_detailmk
     *
     * @return void
     */
    public function detailMk()
    {
        return $this->hasOne(detailmkModel::class, 'id_detailmk', 'id_detailmk_fk');
    }


    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): object
    {
        $subCpmk = $this->query();

        if (!empty($filter['id_mk_fk'])) {
            $subCpmk->where('id_mk_fk', 'LIKE', '%' . $filter['id_mk_fk'] . '%');
        }

        if (!empty($filter['id_detailmk_fk'])) {
            $subCpmk->where('id_detailmk_fk', 'LIKE', '%' . $filter['id_detailmk_fk'] . '%');
        }

        $sort = $sort ?: 'id_subcpmk DESC';
        $subCpmk->orderByRaw($sort);
        $itemPerPage = $itemPerPage > 0 ? $itemPerPage : false;

        return $subCpmk->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function settingMetode()
    {
        $kata = '';

        if (isset($this->diskusi_kelompok) && $this->diskusi_kelompok === 1) {
            $kata .= 'Diskusi Kelompok';
        }

        if (isset($this->simulasi) && $this->simulasi === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Simulasi';
        }

        if (isset($this->studi_kasus) && $this->studi_kasus === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Studi Kasus';
        }

        if (isset($this->kaloboratif) && $this->kaloboratif === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Pembelajaran Kaloboratif';
        }

        if (isset($this->kooperatif) && $this->kooperatif === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Pembelajaran Kooperatif';
        }

        if (isset($this->berbasis_proyek) && $this->berbasis_proyek === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Pembelajaran Berbasis Proyek';
        }
        if (isset($this->berbasis_masalah) && $this->berbasis_masalah === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Pembelajaran Berbasis Masalah';
        }
        return $kata;
    }

    public function settingPenilaian()
    {
        $kata = '';

        if (isset($this->partisipasi) && $this->partisipasi >= 1) {
            $kata .= 'Partisipasi'.'('. $this->partisipasi . ')';
        }

        if (isset($this->tugas) && $this->tugas >= 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Tugas/Quiz'.'('. $this->tugas . ')';
        }

        if (isset($this->presentasi) && $this->presentasi >= 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Presentasi'.'('. $this->presentasi . ')' ;
        }

        if (isset($this->tes_tulis) && $this->tes_tulis >= 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Tes Tulis'.'('. $this->tes_tulis . ')';
        }

        if (isset($this->tes_lisan) && $this->tes_lisan >= 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', ';
            }
            $kata .= 'Tes Lisan'.'('. $this->tes_lisan . ')';
        }

        if (isset($this->tugas_kelompok) && $this->tugas_kelompok === 1) {
            // Jika sudah ada kata sebelumnya, tambahkan koma
            if ($kata !== '') {
                $kata .= ', '.'('. $this->tugas_kelompok . ')';
            }
            $kata .= 'Tugas Kelompok / Proyek';
        }
       
        return $kata;
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
        // dd($id);
        return $this->find($id)->delete();
    }

    public function getAllForHelper()
    {
        return $this->get()->toArray();
    }

    // untuk submit nilai 
    public function submit(array $payload)
    {
        return $this
            ->where('id_mk_fk', $payload['id_mk_fk'])
            ->where('id_detailmk_fk', $payload['id_detailmk_fk'])
            ->where('id_subcpmk', $payload['id_subcpmk'])
            ->update(['available' => $payload['available']]);

    }

     // untuk rubah status penilaian menjadi diterima 
     public function diterima(array $payload)
     {
         return $this
             ->where('id_mk_fk', $payload['id_mk_fk'])
             ->where('id_detailmk_fk', $payload['id_detailmk_fk'])
             ->where('id_subcpmk', $payload['id_subcpmk'])
             ->update([
                'status_penilaian' => 'diterima',
                'pesan_penilaian' => null,

            ]);
 
     }

     // untuk rubah status penilaian menjadi ditolak
     public function ditolak(array $payload)
     {
         return $this
             ->where('id_mk_fk', $payload['id_mk_fk'])
             ->where('id_detailmk_fk', $payload['id_detailmk_fk'])
             ->where('id_subcpmk', $payload['id_subcpmk'])
             ->update([
                'status_penilaian' => 'ditolak',
                'pesan_penilaian' => $payload['pesan_penilaian'],

                ]);
 
     }
}
