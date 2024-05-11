<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\MataKuliah\MataKuliahCollection;
use App\Http\Resources\MataKuliah\MataKuliahResource;
use App\Helpers\MataKuliahHelpers\MataKuliahHelper;

class MataKuliahController extends Controller
{
    private $mk;
    
    public function __construct()
    {
        $this->mk = new MataKuliahHelper();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ['nama_mk' => $request->nama_mk ?? ''];
        $listMk = $this->mk->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new MataKuliahCollection($listMk));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $payload = $request->only([
            'id_kurikulum_fk',
            'nama_matakuliah',
            'kode_matakuliah',
            'deskripsi',
            'sks',
            'bobot',
            'semester',
            'bobot_kajian',
            'uuid_api',
            'prodi',
            'kelas',
            'mk_detail'
        ]);
        // dd($payload);

        $mk = $this->mk->create($payload);

        if (!$mk['status']) {
            return response()->failed($mk['error']);
        }

        return response()->success(new MataKuliahResource($mk['data']), 'Mata Kuliah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mk = $this->mk->getById($id);

        if (!($mk['status'])) {
            return response()->failed(['Data Mata Kuliah tidak ditemukan'], 404);
        }

        return response()->success(new MataKuliahResource($mk['data']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $payload = $request->only([
            'id_matakuliah',
            'id_kurikulum_fk',
            'nama_matakuliah',
            'kode_matakuliah',
            'deskripsi',
            'sks',
            'bobot',
            'semester',
            'bobot_kajian',
            'uuid_api',
            'prodi',
            'kelas',
            'status',
            'mk_detail',
            'mk_detail_deleted'
        ]);

        $mk = $this->mk->update($payload, $payload['id_matakuliah'] ?? 0);

        if (!$mk['status']) {
            return response()->failed($mk['error']);
        }

        return response()->success(new MataKuliahResource($mk['data']), 'Mata kuliah berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mk = $this->mk->delete($id);
        
        if (!$mk['status']) {
            return response()->failed(['Mohon maaf mata kuliah tidak ditemukan']);
        }

        return response()->success($mk, 'Mata kuliah berhasil dihapus');
    }
}
