<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule as rules;
use App\Helpers\KurikulumHelpers\KurikulumHelper;
use App\Http\Resources\Kurikulum\KurikulumResource;
use App\Http\Resources\Kurikulum\KurikulumCollection;

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
        // if (isset($request->validator) && $request->validator->fails()) {
        //     return response()->failed($request->validator->errors());
        // }

        $request->validate([
            'kode_kurikulum' => 'required|string',
            'nama_kurikulum' => 'required|string',
            'tahun' => [
                'required',
                'integer',
                rules::unique('m_kurikulum')->where(function ($query) use ($request) {
                    return $query
                        ->where('tahun', $request->tahun)
                        ->where('periode', $request->periode);
                }),
            ],
            'periode' => 'required|string',
            'profil_lulusan' => 'required|string',
        ], [
            'tahun.unique' => 'Kombinasi tahun dan periode sudah ada di database.',
            'kode_kurikulum.required' => 'Kode kurikulum harus di isi.',
            'nama_kurikulum.required' => 'Nama kurikulum harus di isi.',
            'tahun.required' => 'Tahun harus di isi.',
            'tahun.integer' => 'Tahun harus berbentuk angka.',
            'periode.required' => 'speriode harus di isi.',
            'profil_lulusan.required' => 'profil lulusan harus di isi.',
        ]);
        
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
        $request->validate([
            'kode_kurikulum' => 'required|string',
            'nama_kurikulum' => 'required|string',
            'tahun' => [
                'required',
                'integer'
            ],
            'periode' => 'required|string',
            'profil_lulusan' => 'required|string',
        ], [
            'kode_kurikulum.required' => 'Kode kurikulum harus di isi.',
            'nama_kurikulum.required' => 'Nama kurikulum harus di isi.',
            'tahun.required' => 'Tahun harus di isi.',
            'tahun.integer' => 'Tahun harus berbentuk angka.',
            'periode.required' => 'periode harus di isi.',
            'profil_lulusan.required' => 'profil lulusan harus di isi.',
        ]);
        
        $payload = $request->only([
            'kode_kurikulum',
            'nama_kurikulum',
            'tahun',
            'periode',
            'profil_lulusan',
            'cpl',
            'status',
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
