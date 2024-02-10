<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\detailmkModel;
use App\Models\MataKuliahModel;
use Illuminate\Http\Request;

class ValidasiMkController extends Controller
{
    /**
        * status hanya ada pada mk dan detail mk
        * mk pada roles sek prodi 
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
     */

    public function diterima(Request $request)
    {
        $id_matakuliah = $request['id_matakuliah'];

        $matakuliah = MataKuliahModel::find($id_matakuliah);
    
        if ($matakuliah) {
            // Jika ditemukan, perbarui status menjadi 'diterima'
            $matakuliah->status = 'diterima';
            $matakuliah->pesan_validasi = null;
            $matakuliah->save();

            return response()->json(['message' => 'Status matakuliah diterima'], 200);
        } else {
           
            return response()->json(['error' => 'Matakuliah tidak ditemukan'], 404);
        }
    }

    public function ditolak(Request $request)
    {
        $id_matakuliah = $request['id_matakuliah'];
        $pesan = $request['pesan'];

        $matakuliah = MataKuliahModel::find($id_matakuliah);
    
        if ($matakuliah) {
            // Jika ditemukan, perbarui status menjadi 'ditolak'
            $matakuliah->status = 'ditolak';
            $matakuliah->pesan_validasi = $pesan;
            $matakuliah->save();

            return response()->json(['message' => 'Status matakuliah ditolak'], 200);
        } else {
           
            return response()->json(['error' => 'Matakuliah tidak ditemukan'], 404);
        }
    }


    /**
        * untuk mengubah status detail mata kuliah. 
        * seharusnya perubahan status akan berpengaruh pada sub-cpmk tetapi 
        * ada di detail mk agar mempermudahkan programmer dalam menggolongkan sub-cpmk menurut cpmknya
        * hanya tampil roles dosen dan pada fitur sub-cpmk. 
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
    */

    public function diterimaDetail(Request $request)
    {
        $id_mk_fk = $request['id_mk_fk'];
        $id_detailmk = $request['id_detailmk'];
        
        $detail = detailmkModel::where('id_mk_fk', $id_mk_fk)
                                   ->where('id_detailmk', $id_detailmk)
                                   ->first();  
    
        if ($detail) {
            // Jika ditemukan, perbarui status menjadi 'diterima'
            $detail->status = 'diterima';
            $detail->pesan = null;
            $detail->save();

            return response()->json(['message' => 'Status detail mata kuliah diterima'], 200);
        } else {
           
            return response()->json(['error' => 'detail mata kuliah tidak ditemukan'], 404);
        }
    }

    public function ditolakDetail(Request $request)
    {
        $pesan = $request['pesan']; 
        $id_mk_fk = $request['id_mk_fk'];
        $id_detailmk = $request['id_detailmk'];
        
        $detail = detailmkModel::where('id_mk_fk', $id_mk_fk)
                                   ->where('id_detailmk', $id_detailmk)
                                   ->first();  
    
        if ($detail) {
            // Jika ditemukan, perbarui status menjadi 'ditolak'
            $detail->status = 'ditolak';
            $detail->pesan = $pesan;
            $detail->save();

            return response()->json(['message' => 'Status detail mata kuliah ditolak'], 200);
        } else {
           
            return response()->json(['error' => 'detail mata kuliah tidak ditemukan'], 404);
        }
      
    }
}
