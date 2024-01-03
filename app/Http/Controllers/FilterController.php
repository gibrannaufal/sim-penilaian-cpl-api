<?php

namespace App\Http\Controllers;

use App\Models\cplModel;
use App\Models\CpmkModel;
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
     /**
     * Menampilkan cpmk untuk filter by id_kurikulum_fk.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCpmkFilter($id)
    {
        $listCpmk = CpmkModel::select('id_cpmk','id_kurikulum_fk','id_cpl_fk',  'kode_cpmk', 'deskripsi_cpmk')
        ->where('id_kurikulum_fk', '=', $id)
        ->orderBy('id_cpmk', 'desc')
        ->get();

        return response()->json($listCpmk);
    }
       /**
     * Menampilkan cpmk untuk filter by id_kurikulum_fk dan id_cpl_fk.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCpmkAll(Request $request)
    {
        // dd("hello");
        $listCpmk = CpmkModel::select('id_cpmk','id_kurikulum_fk','id_cpl_fk',  'kode_cpmk', 'deskripsi_cpmk')
        ->where('id_kurikulum_fk', '=', $request->id_kurikulum)
        ->where('id_cpl_fk', '=', $request->id_cpl)
        ->orderBy('id_cpmk', 'desc')
        ->get();

        return response()->json($listCpmk);
    }
}
