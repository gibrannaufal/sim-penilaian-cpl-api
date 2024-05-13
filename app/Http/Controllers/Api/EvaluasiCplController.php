<?php

namespace App\Http\Controllers\Api;

use App\Helpers\EvaluasiCplHelpers\EvaluasiCplHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\EvaluasiCpl\EvaluasiCplCollection;
use Illuminate\Http\Request;

class EvaluasiCplController extends Controller
{
    private $evaluasiCpl;
    
    public function __construct()
    {
        $this->evaluasiCpl = new EvaluasiCplHelper();
    }

    /**
     * menampilkan hasil mahasiswa yang sudah dilakukan penilaian
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd("coba");
        try {
            $filter = [
                'nama_mahasiswa' => $request->nama_mahasiswa ?? '',
                'kurikulum' => $request->kurikulum ?? '',
            ];

            $listMahasiswa = $this->evaluasiCpl->getAll($filter);

            return response()->success($listMahasiswa);
        } catch (\Exception $e) {
            return response()->error($e->getMessage());
        }
    }

    /**
        * menampilkan hasil rekap nilai mahasiswa by nrp yang sudah dilakukan penilaian
        *
        * @return \Illuminate\Http\Response
     */
    public function rekap(Request $request)
    {
        // dd("coba");
            
        $filter = [
            'nrp' => $request->nrp ?? '',

        ];

        $listRekap = $this->evaluasiCpl->rekapNilai($filter);

        return response()->success($listRekap);
    }

     /**
        * menampilkan hasil rekap nilai mahasiswa by nrp yang sudah dilakukan penilaian
        *
        * @return \Illuminate\Http\Response
     */
    public function getCplMahasiswa(Request $request)
    {
        // dd("coba");
            
        $filter = [
            'nrp' => $request->nrp ?? '',
            'nama_cpl' => $request->nama_cpl ?? '',
        ];

        $listRekap = $this->evaluasiCpl->cplMahasiswa($filter);

        return response()->success($listRekap);
    }


   
}
