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
            
        $filter = [
            'nama_mahasiswa' => $request->nama_mahasiswa ?? '',

        ];
        $listMahasiswa = $this->evaluasiCpl->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new EvaluasiCplCollection($listMahasiswa));
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
   
}
