<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\RekapNilaiMahasiswa\Kaprodi\RekapNilaiByKaprodiCollection;
use App\Http\Resources\RekapNilaiMahasiswa\Kaprodi\RekapNilaiByKaprodiResource;
use App\Helpers\EvaluasiCplHelpers\RekapNilaiMahasiswaHelper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RekapNilaiMahasiswa extends Controller
{
    private $rekapNilai;
    
    public function __construct()
    {
        $this->rekapNilai = new RekapNilaiMahasiswaHelper();
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
        $listMahasiswa = $this->rekapNilai->getAll($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new RekapNilaiByKaprodiCollection($listMahasiswa));
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

        $listRekap = $this->rekapNilai->rekapNilai($filter);

        return response()->success($listRekap);
    }
}
