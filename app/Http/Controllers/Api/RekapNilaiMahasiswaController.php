<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\RekapNilaiMahasiswa\Kaprodi\RekapNilaiByKaprodiCollection;
use App\Http\Resources\RekapNilaiMahasiswa\Kaprodi\RekapNilaiByKaprodiResource;
use App\Helpers\RekapNilaiMahasiswaHelpers\RekapNilaiMahasiswaHelper;

use App\Http\Controllers\Controller;
use App\Http\Resources\RekapNilaiMahasiswa\Mahasiswa\RekapNilaiForMahasiswaCollection;
use Illuminate\Http\Request;

class RekapNilaiMahasiswaController extends Controller
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
            'id_mk_fk' => $request->id_mk_fk ?? '',

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

     /**
        * menampilkan hasil rekap nilai mahasiswa by nrp yang sudah dilakukan penilaian untuk per mahasiswa
        *
        * @return \Illuminate\Http\Response
     */
    public function rekapMahasiswa(Request $request)
    {
        // dd("coba");
            
        $filter = [
            'nrp' => $request->nrp ?? '',
            'nama_matakuliah' => $request->nama_matakuliah ?? '',

        ];
        
        $listRekap = $this->rekapNilai->rekapNilaiMahasiswa($filter, $request->itemperpage ?? 0, $request->sort ?? '');

        return response()->success(new RekapNilaiForMahasiswaCollection($listRekap));

    }
}
