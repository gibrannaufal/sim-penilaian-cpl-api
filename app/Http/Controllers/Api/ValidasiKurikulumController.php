<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KurikulumModel;
use Illuminate\Http\Request;

class ValidasiKurikulumController extends Controller
{
     /**
        * status hanya ada pada m_kurikulum
        * mk pada roles kaprodi
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
     */

     public function diterima(Request $request)
     {
         $id_kurikulum_fk = $request['id_kurikulum_fk'];
 
         $kurikulum = KurikulumModel::find($id_kurikulum_fk);
     
         if ($kurikulum) {
             // Jika ditemukan, perbarui status menjadi 'diterima'
             $kurikulum->status = 'diterima';
             $kurikulum->pesan = null;
             $kurikulum->save();
 
             return response()->json(['message' => 'Status Kurikulum diterima'], 200);
         } else {
            
             return response()->json(['error' => 'kurikulum tidak ditemukan'], 404);
         }
     }
 
     public function ditolak(Request $request)
     {
         $id_kurikulum_fk = $request['id_kurikulum_fk'];
         $pesan = $request['pesan'];
 
         $kurikulum = KurikulumModel::find($id_kurikulum_fk);
     
         if ($kurikulum) {
             // Jika ditemukan, perbarui status menjadi 'ditolak'
             $kurikulum->status = 'ditolak';
             $kurikulum->pesan = $pesan;
             $kurikulum->save();
 
             return response()->json(['message' => 'Status Kurikulum ditolak'], 200);
         } else {
            
             return response()->json(['error' => 'Kurikulum tidak ditemukan'], 404);
         }
     }
 
}
