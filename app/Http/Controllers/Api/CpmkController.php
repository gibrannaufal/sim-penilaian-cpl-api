<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CpmkHelpers\CpmkHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cpmk\CpmkCollection;
use App\Http\Resources\Cpmk\CpmkResource;
use Illuminate\Http\Request;

class CpmkController extends Controller
{
    private $cpmk;
    
    public function __construct()
    {
        $this->cpmk = new CpmkHelper();
    }

    /**
     * Menampilkan CPMK.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("coba");
        
        $filter = [
            'id_kurikulum' => $request->id_kurikulum ?? '',

        ];
        $listCpmk = $this->cpmk->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new CpmkCollection($listCpmk));
        
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
            'id_cpl_fk',
            'detail_cpmk'
        ]);

        $cpmk = $this->cpmk->create($payload);

        if (!$cpmk['status']) {
            return response()->failed($cpmk['error']);
        }
        // dd($cpmk['data']);

        return response()->success(new CpmkResource($cpmk['data']), 'CPMK berhasil ditambahkan');
    }

    /**
         * Display the specified resource.
         *
         * @param  int  $id
         * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cpmk = $this->cpmk->getById($id);

        if (!($cpmk['status'])) {
            return response()->failed(['Data kurikulum tidak ditemukan'], 404);
        }

        return response()->success(new CpmkResource($cpmk['data']));
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
            'id_cpmk',
            'id_kurikulum_fk',
            'id_cpl_fk',
            'kode_cpmk',
            'deskripsi_cpmk'
        ]);


        $cpmk = $this->cpmk->update($payload, $payload['id_cpmk'] ?? 0);

        if (!$cpmk['status']) {
            return response()->failed($cpmk['error']);
        }

        return response()->success(new CpmkResource($cpmk['data']), 'CPMK berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cpmk = $this->cpmk->delete($id);
        
        if (!$cpmk['status']) {
            return response()->failed(['Mohon maaf CPMK tidak ditemukan']);
        }

        return response()->success($cpmk, 'CPMK berhasil dihapus');
    }
}
