<?php
// app/Helpers/ApiStikiHelpers.php
namespace App\Helpers\ApiStikiHelpers;

use App\Libraries\ZukoLibs;
use App\Models\PenilaianMkModel;

class ApiStikiHelpers 
{
    protected $zuko;

    public function __construct(ZukoLibs $zuko)
    {
        $this->zuko = $zuko;
    }

    public function getMatkul()
    {
        // Panggil fungsi connect untuk mendapatkan token sesi
        $output = $this->zuko->connect();
        $token = $output['data']['session_token'];

        // Persiapkan parameter
        $parMatkul = [
            'filter' => [
                'prodi_raw' => 'SI-S1',
                'kurikulum' => null,
                'sks' => '4',
                'semester' => null,
                'jenis_raw' => null
            ],
            'order' => [
                'prodi' => 'asc',
                'sks' => 'desc'
            ],
            'limit' => 5,
            'offset' => 0
        ];

        $parKelas = array(
            'filter' => array(
                'prodi' => 'SI-S1',
                'tahun' => '2020',
                'semester' => 'Genap'
            ),
            'limit' => 50,
            'order' => array('kode'=>'asc')

        );  
        $kelasKuliah = $this->zuko->get_kelas_kuliah($token, $parKelas);    

        // Panggil fungsi get_mata_kuliah dengan token dan parameter
        $mataKuliah = $this->zuko->get_mata_kuliah($token, $parMatkul);

        $arrayKuliah = [];

        foreach ($kelasKuliah["data"] as $kelas) {
           $arrayKuliah[$kelas['kode']] = $kelas;
        }

		foreach ($mataKuliah["data"] as $key => $mataKuliahData) {
			if(isset($arrayKuliah[$mataKuliahData['kode']]))
			{
				$mataKuliah["data"][$key]['uid'] = $arrayKuliah[$mataKuliahData['kode']]['uid'] ?? null ;
				$mataKuliah["data"][$key]['prodi'] = $arrayKuliah[$mataKuliahData['kode']]['prodi'] ?? null ;
				$mataKuliah["data"][$key]['kelas'] = $arrayKuliah[$mataKuliahData['kode']]['kelas'] ?? null ;

			}else{
				unset($mataKuliah["data"][$key]);
			}
	
		}

        // Tangani respons dari API
        if(isset($mataKuliah['isOk']) && $mataKuliah['isOk']){
            $data =  array_values($mataKuliah["data"]);
            // Lakukan sesuatu dengan data yang diperoleh
            return $data;
        } else {
            // Tangani jika terjadi kesalahan dalam permintaan
            return null;
        }
    }

    public function getKelas(array $payload)
    {
        // Panggil fungsi connect untuk mendapatkan token sesi
        $output = $this->zuko->connect();
        $token = $output['data']['session_token'];
        
        $par = array(
            'kelas_uid' => $payload['uid']
        
          );  
        
        $listApi = $this->zuko->get_peserta_kelas($token,$par);

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

        foreach ($listApi['data']  as $key => $listMahasiswa) {
			if(isset($arrayKelas[$listMahasiswa['nrp']]))
			{  
                $listApi['data'][$key]['id_penilaian'] = $arrayKelas[$listMahasiswa['nrp']]['id_penilaian'];
                $listApi['data'][$key]['id_mk_fk'] = $payload['id_mk_fk'];
                $listApi['data'][$key]['id_detailmk_fk'] = $payload['id_detailmk_fk'];
                $listApi['data'][$key]['id_subcpmk_fk'] = $payload['id_subcpmk_fk'];
                $listApi['data'][$key]['nrp'] = strval($arrayKelas[$listMahasiswa['nrp']]['nrp']);
                $listApi['data'][$key]['nama'] = $arrayKelas[$listMahasiswa['nrp']]['nama'];
                $listApi['data'][$key]['prodi'] = $arrayKelas[$listMahasiswa['nrp']]['prodi'];
                $listApi['data'][$key]['partisipasi'] = $arrayKelas[$listMahasiswa['nrp']]['partisipasi'];
                $listApi['data'][$key]['tugas'] = $arrayKelas[$listMahasiswa['nrp']]['tugas'];
                $listApi['data'][$key]['presentasi'] = $arrayKelas[$listMahasiswa['nrp']]['presentasi'];
                $listApi['data'][$key]['tes_tulis'] = $arrayKelas[$listMahasiswa['nrp']]['tes_tulis'];
                $listApi['data'][$key]['tes_lisan'] = $arrayKelas[$listMahasiswa['nrp']]['tes_lisan'];
                $listApi['data'][$key]['tugas_kelompok'] = $arrayKelas[$listMahasiswa['nrp']]['tugas_kelompok'];
                $listApi['data'][$key]['total_nilai'] = $arrayKelas[$listMahasiswa['nrp']]['total_nilai'];
			}else{
                $listApi['data'][$key]['id_penilaian'] = 0;
                $listApi['data'][$key]['id_mk_fk'] = $payload['id_mk_fk'];
                $listApi['data'][$key]['id_detailmk_fk'] = $payload['id_detailmk_fk'];
                $listApi['data'][$key]['id_subcpmk_fk'] = $payload['id_subcpmk_fk'];
                $listApi['data'][$key]['partisipasi'] = 0;
                $listApi['data'][$key]['tugas'] = 0 ;
                $listApi['data'][$key]['presentasi'] = 0;
                $listApi['data'][$key]['tes_tulis'] = 0;
                $listApi['data'][$key]['tes_lisan'] = 0;
                $listApi['data'][$key]['tugas_kelompok'] = 0;
                $listApi['data'][$key]['total_nilai'] =0;
            }
	
		}

       return $listApi['data'];


    }
}
