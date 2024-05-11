<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\SubCpmkHelpers\SubCpmkHelper;
use App\Http\Resources\SubCpmk\SubCpmkResource;
use App\Http\Resources\SubCpmk\SubCpmkCollection;
use App\Http\Resources\MataKuliah\MataKuliahCollection;

class SubCpmkController extends Controller
{

    private $SubCpmk;
    
    public function __construct()
    {
        $this->SubCpmk = new SubCpmkHelper();
    }

     /**
     * Menampilkan Sub CPMK pada pop up form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'id_mk_fk' => $request->id_mk_fk ?? '',
            'id_detailmk_fk' => $request->id_detailmk_fk ?? '',

        ];
        $listCpmk = $this->SubCpmk->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new SubCpmkCollection($listCpmk));
        
    }    

    /**
     * Menampilkan MK yang diterima pada sub-cpmk
     *
     * @return \Illuminate\Http\Response
     */
    public function getMkSubCpmk(Request $request)
    {
        $filter = ['nama_mk' => $request->nama_mk ?? ''];
        $listMk = $this->SubCpmk->getAllMk($filter, $request->itemperpage ?? 0, $request->sort ?? '');

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
            'id_mk_fk',
            'id_detailmk_fk',
            'detail_subcpmk',
            'delete_subcpmk'
        ]);

        $subCpmk = $this->SubCpmk->create($payload);

        if (!$subCpmk['status']) {
            return response()->failed($subCpmk['error']);
        }

        return response()->success('SUB-CPMK berhasil ditambahkan');
    }

    /**
     * Merubah status dari sub-cpmk menjadi revisi
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */ 
    public function updateStatus(Request $request)
    {
        $payload = $request->only([
            'status_penilaian',
            'id_mk_fk',
            'id_detailmk_fk',
            'id_subcpmk',
        ]);

        $subCpmk = $this->SubCpmk->updateStatus($payload);

        if (!$subCpmk['status']) {
            return response()->failed($subCpmk['error']);
        }

        return response()->success('SUB-CPMK berhasil dirubah');
    }


    /**
     * Merubah sub-cpmk supaya tidak bisa mengupdate nilai.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function submit(Request $request)
    {
       
        $payload = $request->only([
            'available',
            'id_subcpmk',
            'id_mk_fk',
            'id_detailmk_fk',
        ]);

        $subCpmk = $this->SubCpmk->submit($payload);

        if (!$subCpmk['status']) {
            return response()->failed($subCpmk['error']);
        }

        return response()->success('SUB-CPMK berhasil ditambahkan');
    }

     /**
     * validasi penilaian sub cpmk diterima 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function diterima(Request $request)
    {
       
        $payload = $request->only([
            'id_subcpmk',
            'id_mk_fk',
            'id_detailmk_fk',
        ]);

        $subCpmk = $this->SubCpmk->diterima($payload);

        if (!$subCpmk['status']) {
            return response()->failed($subCpmk['error']);
        }

        return response()->success('SUB-CPMK berhasil ditambahkan');
    }

    /**
     * validasi penilaian sub cpmk ditolak 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ditolak(Request $request)
    {
       
        $payload = $request->only([
            'pesan_penilaian',
            'id_subcpmk',
            'id_mk_fk',
            'id_detailmk_fk',
        ]);

        $subCpmk = $this->SubCpmk->ditolak($payload);

        if (!$subCpmk['status']) {
            return response()->failed($subCpmk['error']);
        }

        return response()->success('SUB-CPMK berhasil ditambahkan');
    }
   
}
