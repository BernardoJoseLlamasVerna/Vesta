<?php

namespace App\Http\Controllers;

use App\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    // flujo solar:
    protected $flujoSolar = [1.863, 1.274, 0.865, 0.785, 1.058, 1.572, 1.743];

    // dist. vesta-sol en el momento de observación:
    protected $distVestaSol = [346664468.058, 346664384.989, 346664453.969, 346664396.148, 346664434.414, 346664406.144, 346664418.037];

    public function startAnalysis()
    {
        $re = '([0-9.,]+)m';
        $arrayElements1 = Storage::get('upload/F1/F1');
        preg_match_all($re, $arrayElements1, $matches1, PREG_SET_ORDER, 0);

        $arrayElements2 = Storage::get('upload/F2/F2');
        preg_match_all($re, $arrayElements2, $matches2, PREG_SET_ORDER, 0);

        $arrayElements3 = Storage::get('upload/F3/F3');
        preg_match_all($re, $arrayElements3, $matches3, PREG_SET_ORDER, 0);

        $arrayElements4 = Storage::get('upload/F4/F4');
        preg_match_all($re, $arrayElements4, $matches4, PREG_SET_ORDER, 0);

        $arrayElements5 = Storage::get('upload/F5/F5');
        preg_match_all($re, $arrayElements5, $matches5, PREG_SET_ORDER, 0);

        $arrayElements6 = Storage::get('upload/F6/F6');
        preg_match_all($re, $arrayElements6, $matches6, PREG_SET_ORDER, 0);

        $arrayElements7 = Storage::get('upload/F7/F7');
        preg_match_all($re, $arrayElements7, $matches7, PREG_SET_ORDER, 0);

        $arrayElements8 = Storage::get('upload/F8/F8');
        preg_match_all($re, $arrayElements8, $matches8, PREG_SET_ORDER, 0);

        $coord_x = 944;
        $coord_y = 963;

        for($i = 0; $i< sizeof($matches1); $i++)
        {
            //calcular coordenadas
            $line = $i/1024;
            $rest_division = $i%1024;
            $coord_x = round($coord_x + (0.5*$rest_division), 2);
            if($i == 0) {
                $coord_y = $coord_y;
            }
            $coord_y = round($coord_y - ($line-1), 2);
            //calcular coordenadas

            if(str_replace(',', '.', $matches2[$i][0]) == 0) {
                continue;
            }

            $f8 = str_replace(',', '.', $matches8[$i][0]);
            $f2 = str_replace(',', '.', $matches2[$i][0]);
            $f7 = str_replace(',', '.', $matches7[$i][0]);
            $f3 = str_replace(',', '.', $matches3[$i][0]);
            $f6 = str_replace(',', '.', $matches6[$i][0]);
            $f4 = str_replace(',', '.', $matches4[$i][0]);
            $f5 = str_replace(',', '.', $matches5[$i][0]);

            /*$arrayResume[] = [$f8, $f2, $f7, $f3, $f6, $f4, $f5];

            var_dump($arrayResume);
            die();*/

            //corregimos cada valor: suponemos $dv y $flujo ordenados según orden de filtros
            $f8_fixed = $f8*M_PI*pow($this->distVestaSol[0], 2)*pow($this->flujoSolar[0], -1);
            $f2_fixed = $f2*M_PI*pow($this->distVestaSol[1], 2)*pow($this->flujoSolar[1], -1);
            $f7_fixed = $f7*M_PI*pow($this->distVestaSol[2], 2)*pow($this->flujoSolar[2], -1);
            $f3_fixed = $f3*M_PI*pow($this->distVestaSol[3], 2)*pow($this->flujoSolar[3], -1);
            $f6_fixed = $f6*M_PI*pow($this->distVestaSol[4], 2)*pow($this->flujoSolar[4], -1);
            $f4_fixed = $f4*M_PI*pow($this->distVestaSol[5], 2)*pow($this->flujoSolar[5], -1);
            $f5_fixed = $f5*M_PI*pow($this->distVestaSol[6], 2)*pow($this->flujoSolar[6], -1);

            //$arrayResume[] = [$f8_fixed, $f2_fixed, $f7_fixed, $f3_fixed, $f6_fixed, $f4_fixed, $f5_fixed];

            //normalizamos con respecto a F2:
            $f8_normalized = $f8_fixed/$f2_fixed;
            $f2_normalized = $f2_fixed/$f2_fixed;
            $f7_normalized = $f7_fixed/$f2_fixed;
            $f3_normalized = $f3_fixed/$f2_fixed;
            $f6_normalized = $f6_fixed/$f2_fixed;
            $f4_normalized = $f4_fixed/$f2_fixed;
            $f5_normalized = $f5_fixed/$f2_fixed;

            //$arrayResume[] = [$f8_normalized, $f2_normalized, $f7_normalized, $f3_normalized, $f6_normalized, $f4_normalized, $f5_normalized];

            /*print_r($arrayResume);
            die();*/

            //Determinamos el tipo de material:
            //1.-Eucrite:
            $f8_euc_compared = pow($f8_normalized-0.870934895, 2);
            $f2_euc_compared = pow($f2_normalized-1, 2);
            $f7_euc_compared = pow($f7_normalized-1.085860881, 2);
            $f3_euc_compared = pow($f3_normalized-1.178224997, 2);
            $f6_euc_compared = pow($f6_normalized-1.03832738, 2);
            $f4_euc_compared = pow($f4_normalized-0.769017478, 2);
            $f5_euc_compared = pow($f5_normalized-0.793369077, 2);

            $euc_compared_resumed = [$f8_euc_compared, $f2_euc_compared, $f7_euc_compared, $f3_euc_compared, $f6_euc_compared, $f4_euc_compared, $f5_euc_compared ];

            $dif_euc = sqrt(array_sum($euc_compared_resumed));

            //2.-Diogenite:
            $f8_dio_compared = pow($f8_normalized-0.70610281, 2);
            $f2_dio_compared = pow($f2_normalized-1, 2);
            $f7_dio_compared = pow($f7_normalized-1.075217256, 2);
            $f3_dio_compared = pow($f3_normalized-1.035497439, 2);
            $f6_dio_compared = pow($f6_normalized-0.676677086, 2);
            $f4_dio_compared = pow($f4_normalized-0.420781304, 2);
            $f5_dio_compared = pow($f5_normalized-0.47940363, 2);

            $dio_compared_resumed = [$f8_dio_compared, $f2_dio_compared, $f7_dio_compared, $f3_dio_compared, $f6_dio_compared, $f4_dio_compared, $f5_dio_compared ];

            $dif_dio = sqrt(array_sum($dio_compared_resumed));

            //3.-Howardite:
            $f8_how_compared = pow($f8_normalized-0.829196281, 2);
            $f2_how_compared = pow($f2_normalized-1, 2);
            $f7_how_compared = pow($f7_normalized-1.074897103, 2);
            $f3_how_compared = pow($f3_normalized-1.078338401, 2);
            $f6_how_compared = pow($f6_normalized-0.673516674, 2);
            $f4_how_compared = pow($f4_normalized-0.401810592, 2);
            $f5_how_compared = pow($f5_normalized-0.439768495, 2);

            $how_compared_resumed = [$f8_how_compared, $f2_how_compared, $f7_how_compared, $f3_how_compared, $f6_how_compared, $f4_how_compared, $f5_how_compared ];

            $dif_how = sqrt(array_sum($how_compared_resumed));

            //Material comparison:
            $materialComparative = [$dif_euc, $dif_dio, $dif_how];
            $result_material = array_search(min($materialComparative), $materialComparative);

            if($result_material === 0){
                $result_material = 'euc';
                print_r($result_material);
            }

            if($result_material === 1){
                $result_material = 'dio';
                print_r($result_material);
            }

            if($result_material === 2){
                $result_material = 'how';
                print_r($result_material);
            }

            if($result_material === 'euc' || $result_material === 'dio') {
                $result = new Result();
                $result->material = $result_material;
                $result->coord_x = $coord_x;
                $result->coord_y = $coord_x;
                $result->save();
            }
        }
    }
}
