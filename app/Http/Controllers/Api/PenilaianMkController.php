<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\PenilaianMkHelpers\PenilaianMkHelpers;
use App\Http\Controllers\Api\ApiStikiController;
use App\Http\Resources\PenilaianMk\PenilaianMkCollection;

class PenilaianMkController extends Controller
{
    private $penilaian;
    
    public function __construct()
    {
        $this->penilaian = new PenilaianMkHelpers();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = ['nama_mk' => $request->nama_mk ?? ''];
        $listMk = $this->penilaian->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');
        return response()->success(new PenilaianMkCollection($listMk));
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
            'penilaian',
        ]);

        $penilaian = $this->penilaian->store($payload);

        if (!$penilaian['status']) {
            return response()->failed($penilaian['error']);
        }

        return response()->success('Penilaian berhasil ditambahkan');
    }

    /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
     */
    public function penilaianDetail(Request $request)
    {
       
        $payload = $request->only([
            'id_mk_fk',
            'id_detailmk_fk',
        ]);

        $penilaian = $this->penilaian->penilaianDetail($payload);

        if (!$penilaian['status']) {
            return response()->failed($penilaian['error']);
        }

        return response()->success($penilaian["data"]);

    }

}
