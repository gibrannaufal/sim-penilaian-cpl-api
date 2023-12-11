<?php

namespace App\Http\Controllers;

use App\Models\cplModel;
use Illuminate\Http\Request;
use App\Models\KurikulumModel;

class FilterController extends Controller
{
    /**
     * Menampilkan kurikulum untuk filter.
     *
     * @return \Illuminate\Http\Response
     */
    public function getKurilumFilter(Request $request)
    {
        // dd("coba");
        $listKurikulum = KurikulumModel::select('id_kurikulum', 'kode_kurikulum', 'nama_kurikulum')
        ->orderBy('id_kurikulum', 'desc')
        ->get();

        return response()->json($listKurikulum);
    }

    /**
     * Menampilkan cpl untuk filter by id_kurikulum_fk.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCplFilter($id)
    {
        $listCpl = cplModel::select('id_kurikulum_fk','id_cpl',  'kode_cpl', 'deskripsi_cpl')
        ->where('id_kurikulum_fk', '=', $id)
        ->orderBy('id_cpl', 'desc')
        ->get();

        return response()->json($listCpl);
    }
}