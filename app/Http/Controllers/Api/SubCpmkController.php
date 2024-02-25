<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SubCpmkHelpers\SubCpmkHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SubCpmk\SubCpmkCollection;
use App\Http\Resources\SubCpmk\SubCpmkResource;

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
   
}
