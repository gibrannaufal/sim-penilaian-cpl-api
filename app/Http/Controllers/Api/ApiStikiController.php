<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\ZukoLibs;
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
            'uid',
            'id_mk_fk',
            'id_detailmk_fk',
            'id_mk_fk',
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

}
