<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json('Hola');
    }

    public function show($id) : JsonResponse
    {
        if (!empty($id)) {
            $zipCodesFound = Place::where('d_codigo', $id)->get();

            $respuesta = array();
            if (!empty($zipCodesFound)) {
                $data= json_decode($zipCodesFound, true);
                $settlements = array();
                $federalEntity = null;
                $municipality = null;
                foreach ($data as $key => $qs) {
                    $respuesta['zip_code'] = $qs['d_codigo'];
                    $respuesta['locality'] = mb_strtoupper($qs['d_ciudad'], 'utf-8');
                    $federalEntity = array(
                        "key" => $qs['c_estado'],
                        "name" => mb_strtoupper($qs['d_estado'], 'utf-8'),
                        "code" => $qs['c_CP']
                    );
                    $municipality = array(
                        "key" => $qs['c_mnpio'],
                        "name" => mb_strtoupper($qs['D_mnpio'], 'utf-8')
                    );
                    $settlement = array(
                        "key" => $qs['id_asenta_cpcons'],
                        "name" => mb_strtoupper($qs['d_asenta'], 'utf-8'),
                        "zone_type" => $qs['d_zona'],
                        "settlement_type" => array(
                            "name" => $qs['d_tipo_asenta']
                        )
                    );
                    array_push($settlements, $settlement);
                }
                if (!is_null($federalEntity) || !is_null($municipality)) {
                    $respuesta['federal_entity'] = $federalEntity;
                    $respuesta['settlements'] = $settlements;
                    $respuesta['municipality'] = $municipality;
                }
            }
            $responseCode = (count($respuesta) > 0) ? 200 : 404;
        } else {
            $responseCode = 501;
            $respuesta = null;
        }

        return response()->json($respuesta, $responseCode);

    }
}
