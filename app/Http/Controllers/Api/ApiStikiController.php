<?php

namespace App\Http\Controllers\Api;

use App\Libraries\ZukoLibs;
use Illuminate\Http\Request;
use App\Models\PenilaianMkModel;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Helpers\ApiStikiHelpers\ApiStikiHelpers;

class ApiStikiController extends Controller
{
    protected $apiStikiHelpers;

    public function __construct(ApiStikiHelpers $apiStikiHelpers)
    {
        $this->apiStikiHelpers = $apiStikiHelpers;
    }

    public function getMatkul(Request $request)
    {
        // Panggil metode getMatkul dari helper ApiStikiHelpers
        $data = $this->apiStikiHelpers->getMatkul();

        if ($data !== null) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }

    public function getListMahasiswa(Request $request)
    {
        $payload = $request->only([
            'id_mk_fk',
            'id_detailmk_fk',
            'id_subcpmk_fk',
        ]);

        // Panggil metode getMatkul dari helper ApiStikiHelpers
        $data = $this->apiStikiHelpers->getKelas($payload);

        if ($data !== null) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }

    public function getAllMahasiswa(Request $request)
    {
        $data = $this->apiStikiHelpers->getAllMahasiswa();

        if ($data !== null) {
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Failed to fetch data'], 500);
        }
    }


    // tabel dummy 
    public function getMahasiswaDummy(Request $request)
    {   
        $payload = $request->only([
            'id_mk_fk',
            'id_detailmk_fk',
            'id_subcpmk_fk',
        ]);

        $listMahasiswa = DB::table('m_perwalian')
        ->select(
            'm_mahasiswa.nrp',
            'm_mahasiswa.nama_mahasiswa',
            'm_mahasiswa.prodi',
            )
        ->leftJoin('m_mahasiswa', 'm_mahasiswa.nrp', '=', 'm_perwalian.nrp');

        if (isset($payload['id_mk_fk']) && !empty($payload['id_mk_fk'])) {
            $listMahasiswa->where('m_perwalian.id_matakuliah', '=', $payload['id_mk_fk']);
        }

        $listMahasiswa = $listMahasiswa->orderBy('m_perwalian.nrp', 'ASC')->get();


        // sambungkan dengan tabel penilaian 
        $listPenilaian = PenilaianMkModel::select('*')
        ->where('id_subcpmk_fk', '=', $payload['id_subcpmk_fk'])
        ->where('id_mk_fk', '=', $payload['id_mk_fk'])
        ->where('id_detailmk_fk', '=', $payload['id_detailmk_fk'])
        ->orderBy('id_penilaian', 'desc')
        ->get();
          
        $arrayKelas = [];

        foreach ($listPenilaian as $penilaian) {
           $arrayKelas[$penilaian['nrp']] = $penilaian;
        }
        
        $arrMahasiswaAll = [];
        foreach ($listMahasiswa  as $key => $mahasiswa) {
			if(isset($arrayKelas[$mahasiswa->nrp]))
			{  
                $arrMahasiswaAll[$key]['id_mk_fk'] = $payload['id_mk_fk'];
                $arrMahasiswaAll[$key]['id_detailmk_fk'] = $payload['id_detailmk_fk'];
                $arrMahasiswaAll[$key]['id_subcpmk_fk'] = $payload['id_subcpmk_fk'];

                $arrMahasiswaAll[$key]['nrp'] = strval($arrayKelas[$mahasiswa->nrp]['nrp']);
                $arrMahasiswaAll[$key]['nama'] = $arrayKelas[$mahasiswa->nrp]['nama'];
                $arrMahasiswaAll[$key]['prodi'] = $arrayKelas[$mahasiswa->nrp]['prodi'];

                $arrMahasiswaAll[$key]['id_penilaian'] = $arrayKelas[$mahasiswa->nrp]['id_penilaian'];
                $arrMahasiswaAll[$key]['partisipasi'] = $arrayKelas[$mahasiswa->nrp]['partisipasi'];
                $arrMahasiswaAll[$key]['tugas'] = $arrayKelas[$mahasiswa->nrp]['tugas'];
                $arrMahasiswaAll[$key]['presentasi'] = $arrayKelas[$mahasiswa->nrp]['presentasi'];
                $arrMahasiswaAll[$key]['tes_tulis'] = $arrayKelas[$mahasiswa->nrp]['tes_tulis'];
                $arrMahasiswaAll[$key]['tes_lisan'] = $arrayKelas[$mahasiswa->nrp]['tes_lisan'];
                $arrMahasiswaAll[$key]['tugas_kelompok'] = $arrayKelas[$mahasiswa->nrp]['tugas_kelompok'];
                $arrMahasiswaAll[$key]['total_nilai'] = $arrayKelas[$mahasiswa->nrp]['total_nilai'];
			
            }else{
                // jika belum ada nilainya
                $arrMahasiswaAll[$key]['id_mk_fk'] = $payload['id_mk_fk'];
                $arrMahasiswaAll[$key]['id_detailmk_fk'] = $payload['id_detailmk_fk'];
                $arrMahasiswaAll[$key]['id_subcpmk_fk'] = $payload['id_subcpmk_fk'];

                $arrMahasiswaAll[$key]['nrp'] = $mahasiswa->nrp;
                $arrMahasiswaAll[$key]['nama'] = $mahasiswa->nama_mahasiswa;
                $arrMahasiswaAll[$key]['prodi'] = $mahasiswa->prodi;

                $arrMahasiswaAll[$key]['id_penilaian'] = 0;
                $arrMahasiswaAll[$key]['partisipasi'] = 0;
                $arrMahasiswaAll[$key]['tugas'] =0;
                $arrMahasiswaAll[$key]['presentasi'] = 0;
                $arrMahasiswaAll[$key]['tes_tulis'] = 0;
                $arrMahasiswaAll[$key]['tes_lisan'] =0;
                $arrMahasiswaAll[$key]['tugas_kelompok'] = 0;
                $arrMahasiswaAll[$key]['total_nilai'] = 0;
            }
        }
        return response()->json($arrMahasiswaAll);
    }

}
