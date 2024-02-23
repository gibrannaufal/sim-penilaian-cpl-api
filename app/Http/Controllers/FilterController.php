<?php

namespace App\Http\Controllers;

use App\Models\cplModel;
use App\Models\CpmkModel;
use App\Models\detailmkModel;
use Illuminate\Http\Request;
use App\Models\KurikulumModel;
use App\Models\MataKuliahModel;
use App\Models\PenilaianMkModel;
use App\Models\SubCpmkModel;

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
        ->where('id_cpl_fk', '=', $request->id_cpl  )
        ->orderBy('id_cpmk', 'desc')
        ->get();

        return response()->json($listCpmk);
    }

    /**
         * Menampilkan detail untuk list detail mk pop up modal
         *
        * @return \Illuminate\Http\Response
    */
    public function getDetailMk(Request $request)
    {
        // dd("hello");
        $listCpmk = detailmkModel::select('*', 'm_cpmk.*', 'm_cpl.kode_cpl','m_cpl.deskripsi_cpl')
        ->leftJoin("m_cpmk", "m_cpmk.id_cpmk", "=", "m_detailmk.id_cpmk_fk")
        ->leftJoin("m_cpl", "m_cpl.id_cpl", "=", "m_detailmk.id_cpl_fk")
        ->where('id_mk_fk', '=', $request->id_matakuliah)
        ->orderBy('id_detailmk', 'desc')
        ->get();

        return response()->json($listCpmk);
    }

    /**
         * Menampilkan mk by id untuk menampilkan di form
         *
        * @return \Illuminate\Http\Response
    */
    public function getMkById(Request $request)
    {
        $listMkById = MataKuliahModel::select('*',
         'm_kurikulum.nama_kurikulum', 
         'm_kurikulum.kode_Kurikulum',

         'm_cpmk.deskripsi_cpmk',
         'm_cpmk.kode_cpmk',
         'm_detailmk.bobot_detailmk',
         'm_detailmk.indikator_pencapaian',

         )
        ->leftJoin("m_detailmk", "m_detailmk.id_mk_fk", "=", "m_matakuliah.id_matakuliah")
        ->leftJoin("m_kurikulum", "m_kurikulum.id_kurikulum", "=", "m_matakuliah.id_kurikulum_fk")
        ->leftJoin("m_cpmk", "m_cpmk.id_cpmk", "=", "m_detailmk.id_cpmk_fk")
        
        ->where('id_matakuliah', '=', $request->id_mk_fk)
        ->where('id_detailmk', '=', $request->id_detailmk_fk)
        ->orderBy('id_matakuliah', 'desc')
        ->get();

        return response()->json($listMkById);
    }

    /**
         * Mengambil semua kode sub-cpmk untuk keperluan pengkode an 
         *
        * @return \Illuminate\Http\Response
    */
    public function getSubCpmkAll(Request $request)
    {
        // dd("hello");
        $listSubCpmk = SubCpmkModel::select('*')
        ->where('id_mk_fk', '=', $request->id_mk_fk)
        ->where('id_detailmk_fk', '=', $request->id_detailmk_fk)
        ->orderBy('id_subcpmk', 'asc')
        ->get();

        return response()->json($listSubCpmk);
    }

    /**
         * Mengambil SUB-CPMK By id 
         *
        * @return \Illuminate\Http\Response
    */
    public function getSubCpmkById(Request $request)
    {
        // dd($request->id_subcpmk);
        $listSubCpmk = SubCpmkModel::select('*')
        ->where('id_subcpmk', '=', $request->id_subcpmk)
        ->where('id_mk_fk', '=', $request->id_mk_fk)
        ->where('id_detailmk_fk', '=', $request->id_detailmk_fk)
        ->orderBy('id_subcpmk', 'asc')
        ->first();

        return response()->json($listSubCpmk);
    }

    /**
         * Mengambil Semua penilaian 
         *
        * @return \Illuminate\Http\Response
    */
    public function getPenilaianAll(Request $request)
    {
        // dd($request->id_subcpmk);
        $listSubCpmk = PenilaianMkModel::select('*')
        ->where('id_subcpmk', '=', $request->id_subcpmk)
        ->where('id_mk_fk', '=', $request->id_mk_fk)
        ->where('id_detailmk_fk', '=', $request->id_detailmk_fk)
        ->orderBy('id_penilaian', 'desc')
        ->get();

        return response()->json($listSubCpmk);
    }




}
