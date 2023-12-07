<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\KurikulumHelpers\KurikulumHelper;
use App\Http\Resources\Kurikulum\KurikulumCollection;
use App\Http\Resources\Kurikulum\KurikulumResource;

class KurikulumController extends Controller
{
    private $kurikulum;
    
    public function __construct()
    {
        $this->kurikulum = new KurikulumHelper();
    }
    /**
     * Menampilkan Kurikulum.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("coba");
        $filter = ['nama_kurikulum' => $request->nama_kurikulum ?? ''];
        $listKurikulum = $this->kurikulum->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new KurikulumCollection($listKurikulum));
    }

    /**
     * Store a newly created resource in storage.
     * author naufalgibran971@gmail.com 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only([
            'kode_kurikulum',
            'nama_kurikulum',
            'tahun',
            'periode',
            'profil_lulusan',
            'cpl',
            'id'
        ]);

        $kurikulum = $this->kurikulum->create($payload);

        if (!$kurikulum['status']) {
            return response()->failed($kurikulum['error']);
        }
        // dd($kurikulum['data']);

        return response()->success(new kurikulumResource($kurikulum['data']), 'kurikulum berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kurikulum = $this->kurikulum->getById($id);

        if (!($kurikulum['status'])) {
            return response()->failed(['Data kurikulum tidak ditemukan'], 404);
        }

        return response()->success(new KurikulumResource($kurikulum['data']));
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
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }
        
        $payload = $request->only([
            'kode_kurikulum',
            'nama_kurikulum',
            'tahun',
            'periode',
            'profil_lulusan',
            'cpl',
            'id_kurikulum',
            'cpl_deleted'
        ]);


        $kurikulum = $this->kurikulum->update($payload, $payload['id_kurikulum'] ?? 0);

        if (!$kurikulum['status']) {
            return response()->failed($kurikulum['error']);
        }

        return response()->success(new kurikulumResource($kurikulum['data']), 'kurikulum berhasil diubah');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kurikulum = $this->kurikulum->delete($id);
        
        if (!$kurikulum['status']) {
            return response()->failed(['Mohon maaf kurikulum tidak ditemukan']);
        }

        return response()->success($kurikulum, 'kurikulum berhasil dihapus');
    }
}
