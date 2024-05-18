<?php

namespace App\Http\Controllers;

use App\Models\cplModel;
use App\Models\CpmkModel;
use App\Models\SubCpmkModel;
use Illuminate\Http\Request;
use App\Models\detailmkModel;
use App\Models\KurikulumModel;
use App\Models\MataKuliahModel;
use App\Models\PenilaianMkModel;
use Illuminate\Support\Facades\DB;

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
        $listKurikulum = KurikulumModel::select('id_kurikulum', 'kode_kurikulum', 'nama_kurikulum', 'tahun')
        ->where('m_kurikulum.status', '=', 'diterima')
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
        $listCpmk = detailmkModel::select(
            'm_detailmk.id_detailmk',  
            'm_detailmk.id_mk_fk',  
            'm_detailmk.id_cpl_fk',  
            'm_detailmk.id_cpmk_fk',  
            'm_detailmk.indikator_pencapaian',  
            'm_detailmk.bobot_detailmk',  
            'm_detailmk.pesan',  
            'm_detailmk.status',  
            'm_detailmk.is_nilai',  
            'm_cpmk.kode_cpmk', 
            'm_cpmk.deskripsi_cpmk', 
            'm_cpl.kode_cpl',
            'm_cpl.deskripsi_cpl',
            DB::raw('COUNT(CASE WHEN m_subcpmk.available = 0 THEN m_subcpmk.id_subcpmk END) AS jumlah_belum_nilai')
            )
        ->leftJoin("m_subcpmk", "m_subcpmk.id_detailmk_fk", "=", "m_detailmk.id_detailmk")
        ->leftJoin("m_cpmk", "m_cpmk.id_cpmk", "=", "m_detailmk.id_cpmk_fk")
        ->leftJoin("m_cpl", "m_cpl.id_cpl", "=", "m_detailmk.id_cpl_fk")
        ->where('m_detailmk.id_mk_fk', '=', $request->id_matakuliah)
        ->orderBy('m_detailmk.id_detailmk', 'ASC')
        ->groupBy( 
            'm_detailmk.id_detailmk',  
            'm_detailmk.id_mk_fk',  
            'm_detailmk.id_cpl_fk',  
            'm_detailmk.id_cpmk_fk',
            'm_detailmk.indikator_pencapaian',  
            'm_detailmk.bobot_detailmk',  
            'm_detailmk.pesan',  
            'm_detailmk.status',  
            'm_detailmk.is_nilai',  'm_cpmk.kode_cpmk', 
            'm_cpmk.deskripsi_cpmk', 
            'm_cpl.kode_cpl',
            'm_cpl.deskripsi_cpl'
        )
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
         * bisa digunakan untuk hal lain juga 
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
        $listSubCpmk = PenilaianMkModel::select(
            'm_subcpmk.nama_subcpmk',
            'm_subcpmk.kode_subcpmk',
            'm_penilaian.nrp',
            'm_penilaian.nama',
            'm_penilaian.partisipasi',
            'm_penilaian.tugas',
            'm_penilaian.presentasi',
            'm_penilaian.tes_tulis',
            'm_penilaian.tes_lisan',
            'm_penilaian.tugas_kelompok',
            'm_penilaian.total_nilai',

            )
        ->leftJoin('m_subcpmk', 'm_subcpmk.id_subcpmk', '=', 'm_penilaian.id_subcpmk_fk');
        
        if (isset($request->id_detailmk_fk) && !empty($request->id_detailmk_fk)) {
            $listSubCpmk->where('m_penilaian.id_detailmk_fk', '=', $request->id_detailmk_fk);
        }
        if (isset($request->id_mk_fk) && !empty($request->id_mk_fk)) {
            $listSubCpmk->where('m_penilaian.id_mk_fk', '=', $request->id_mk_fk);
        }
        if (isset($request->nrp) && !empty($request->nrp)) {
            $listSubCpmk->where('m_penilaian.nrp', '=', $request->nrp);
        }

        $listSubCpmk = $listSubCpmk->orderBy('m_penilaian.id_penilaian', 'ASC')->get();
    

        return response()->json($listSubCpmk);
    }

    /**
         * Mengambil Semua penilaian pada cpmk mahasiswa 
         *
        * @return \Illuminate\Http\Response
    */
    public function getPenilaianCpmk(Request $request)
    {
        $listNilaiCpmk = DB::table('t_totalnilai_cpmk')
        ->select(
            't_totalnilai_cpmk.id_mk_fk',
            't_totalnilai_cpmk.id_detailmk_fk',
            't_totalnilai_cpmk.nrp',
            't_totalnilai_cpmk.nama',
            't_totalnilai_cpmk.total_nilai',
            'm_matakuliah.nama_matakuliah' , 
            'm_cpmk.deskripsi_cpmk', 
            'm_cpmk.kode_cpmk', 
            'm_cpl.deskripsi_cpl',
            'm_cpl.kode_cpl',
            )
        ->leftJoin('m_matakuliah', 'm_matakuliah.id_matakuliah', '=', 't_totalnilai_cpmk.id_mk_fk')
        ->leftJoin('m_detailmk', 't_totalnilai_cpmk.id_mk_fk', '=', 'm_detailmk.id_detailmk')
        ->leftJoin('m_cpl', 'm_cpl.id_cpl', '=', 'm_detailmk.id_cpl_fk')
        ->leftJoin('m_cpmk', 'm_cpmk.id_cpmk', '=', 'm_detailmk.id_cpmk_fk');

        if (isset($request->nrp) && !empty($request->nrp)) {
            $listNilaiCpmk->where('t_totalnilai_cpmk.nrp', '=', $request->nrp);
        }
        if (isset($request->id_mk_fk) && !empty($request->id_mk_fk)) {
            $listNilaiCpmk->where('t_totalnilai_cpmk.id_mk_fk', '=', $request->id_mk_fk);
        }
        if (isset($request->id_detailmk_fk) && !empty($request->id_detailmk_fk)) {
            $listNilaiCpmk->where('t_totalnilai_cpmk.id_detailmk_fk', '=', $request->id_detailmk_fk);
        }

        $listNilaiCpmk = $listNilaiCpmk->orderBy('t_totalnilai_cpmk.nrp', 'ASC')->get();
        
        return response()->json($listNilaiCpmk);
    }

    /**
         * Mengambil cpl by kurikulum
         *
        * @return \Illuminate\Http\Response
    */
    public function getCplByKurikulum(Request $request)
    {
        $cpl = cplModel::select('*')->where('m_cpl.id_kurikulum_fk', '=', $request->id_kurikulum_fk);
        $cpl = $cpl->orderBy('id_cpl', 'ASC')->get();
        return response()->json($cpl);

    }

     /**
         * Mengambil Semua roles pada user_roles  
         *
        * @return \Illuminate\Http\Response
    */
    public function getRoles(Request $request)
    {
        $list = DB::table('user_roles')
        ->select('*');

        $list = $list->orderBy('user_roles.id', 'ASC')->get();
        
        return response()->json($list);
    }

}
