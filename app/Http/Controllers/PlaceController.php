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
                foreach ($data as $key => $value) {
                    $respuesta['zip_code'] = $value['d_codigo'];
                    $respuesta['locality'] = strtoupper($this->replaceSpecialCharacters($value['d_ciudad']));
                    $federalEntity = array(
                        "key" => intval($value['c_estado']),
                        "name" => strtoupper($this->replaceSpecialCharacters($value['d_estado'])),
                        "code" => $value['c_CP']
                    );
                    $municipality = array(
                        "key" => intval($value['c_mnpio']),
                        "name" => strtoupper($this->replaceSpecialCharacters($value['D_mnpio']))
                    );
                    $settlement = array(
                        "key" => intval($value['id_asenta_cpcons']),
                        "name" => strtoupper($this->replaceSpecialCharacters($value['d_asenta'])),
                        "zone_type" => strtoupper($value['d_zona']),
                        "settlement_type" => array(
                            "name" => $value['d_tipo_asenta']
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

    function replaceSpecialCharacters($cadena) : String
    {
        $cadena = str_replace(
            array('??', '??', '??', '??', '??', '??', '??', '??', '??'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        $cadena = str_replace(
            array('??', '??', '??', '??', '??', '??', '??', '??'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena );

        $cadena = str_replace(
            array('??', '??', '??', '??', '??', '??', '??', '??'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena );

        $cadena = str_replace(
            array('??', '??', '??', '??', '??', '??', '??', '??'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena );

        $cadena = str_replace(
            array('??', '??', '??', '??', '??', '??', '??', '??'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena );

        $cadena = str_replace(
            array('??', '??', '??', '??'),
            array('?', '?', '?', '?'),
            $cadena
        );

        return $cadena;
    }

}
