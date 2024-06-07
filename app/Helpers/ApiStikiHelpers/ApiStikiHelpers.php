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
            'limit' => 20,
            'offset' => 0
        ];

        $parKelas = array(
            'filter' => array(
                'prodi' => 'SI-S1',
                'tahun' => '2020',
                'semester' => 'Genap'
            ),
            'limit' => 100,
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
        $listPenilaian = PenilaianMkModel::select('*')
        ->where('id_subcpmk_fk', '=', $payload['id_subcpmk_fk'])
        ->where('id_mk_fk', '=', $payload['id_mk_fk'])
        ->where('id_detailmk_fk', '=', $payload['id_detailmk_fk'])
        ->orderBy('id_penilaian', 'desc')
        ->toSql();
        
       return $listPenilaian;


    }

    public function getAllMahasiswa()
    {
        $output = $this->zuko->connect();
        $token = $output['data']['session_token'];
        
        $par = [];
        
        $mahasiswa = $this->zuko->get_mhs_jumlah_aktif_by_program($token, $par);    

        return $mahasiswa;


    }
}
