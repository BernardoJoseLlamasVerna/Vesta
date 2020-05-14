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
    // eucrite constants:
    protected $eucriteConstants = [0.870934895, 0, 1.085860881, 1.178224997, 1.03832738, 0.769017478, 0.793369077];
    // diogenite constants:
    protected $diogeniteConstants = [0.70610281, 0, 1.075217256, 1.035497439, 0.676677086, 0.420781304, 0.47940363];
    // howardite constants:
    protected $howarditeConstants = [0.829196281, 0, 1.074897103, 1.078338401, 0.673516674, 0.401810592, 0.439768495];

    // starting coordinates:
    protected $coord_x = 944;
    protected $coord_y = 963;

    public function startAnalysis()
    {
        $re = '([0-9.,]+)m';

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

        $euc = 0;
        $dio = 0;
        $how = 0;

        for($i = 0; $i< sizeof($matches2); $i++)
        {
            //check if 0, empty or null--->continue iteration
            if(!$this->checkPixelIfValid($matches2[$i][0])) {
                continue;
            }

            //calcular coordenadas
            $pixel_x = $this->coordinatesCalculation($i)[0];
            $pixel_y = $this->coordinatesCalculation($i)[1];
            //calcular coordenadas

            $f8 = str_replace(',', '.', $matches8[$i][0]);
            $f2 = str_replace(',', '.', $matches2[$i][0]);
            $f7 = str_replace(',', '.', $matches7[$i][0]);
            $f3 = str_replace(',', '.', $matches3[$i][0]);
            $f6 = str_replace(',', '.', $matches6[$i][0]);
            $f4 = str_replace(',', '.', $matches4[$i][0]);
            $f5 = str_replace(',', '.', $matches5[$i][0]);

            //corregimos cada valor: suponemos $dv y $flujo ordenados según orden de filtros
            $f8_fixed = $this->distanceAndFluxSun($f8, 0);
            $f2_fixed = $this->distanceAndFluxSun($f2, 1);
            $f7_fixed = $this->distanceAndFluxSun($f7, 2);
            $f3_fixed = $this->distanceAndFluxSun($f3, 3);
            $f6_fixed = $this->distanceAndFluxSun($f6, 4);
            $f4_fixed = $this->distanceAndFluxSun($f4, 5);
            $f5_fixed = $this->distanceAndFluxSun($f5, 6);
            //corregimos cada valor: suponemos $dv y $flujo ordenados según orden de filtros

            //normalizamos con respecto a F2:
            $f8_normalized = $this->f2Normalization($f8_fixed, $f2_fixed);
            $f2_normalized = $this->f2Normalization($f2_fixed, $f2_fixed);
            $f7_normalized = $this->f2Normalization($f7_fixed, $f2_fixed);
            $f3_normalized = $this->f2Normalization($f3_fixed, $f2_fixed);
            $f6_normalized = $this->f2Normalization($f6_fixed, $f2_fixed);
            $f4_normalized = $this->f2Normalization($f4_fixed, $f2_fixed);
            $f5_normalized = $this->f2Normalization($f5_fixed, $f2_fixed);

            $filtersNormalized = [$f8_normalized, $f2_normalized, $f7_normalized, $f3_normalized, $f6_normalized, $f4_normalized, $f5_normalized];
            //normalizamos con respecto a F2:

            //Determinamos el tipo de material:
            //1.-Eucrite:
            $dif_euc = $this->eucriteComparation($filtersNormalized);

            //2.-Diogenite:
            $dif_dio = $this->diogeniteComparation($filtersNormalized);

            //3.-Howardite:
            $dif_how = $this->howarditeComparation($filtersNormalized);

            //Material comparison:
            $materialComparative = [$dif_euc, $dif_dio, $dif_how];
            $result_material = array_search(min($materialComparative), $materialComparative);

            if($result_material === 2){
                $how = $how + 1;
                continue;
            }

            if($result_material === 0){
                /*$result = new Result();
                $result->material = 'euc';
                $result->coord_x = $pixel_x;
                $result->coord_y = $pixel_y;
                $result->save();*/

                $euc = $euc + 1;
                continue;
            }

            if($result_material === 1){
                /*$result = new Result();
                $result->material = 'dio';
                $result->coord_x = $pixel_x;
                $result->coord_y = $pixel_y;
                $result->save();*/

                $dio = $dio + 1;
                continue;
            }
            continue;
        }

        print_r('euc: '.$euc.' /  dio: '.$dio.' / how: '.$how);
    }

    /**
     * @param $elementToFix
     * @param $position
     * @return float|int
     */
    private function distanceAndFluxSun($elementToFix, $position)
    {
        return $elementToFix*M_PI
                *pow($this->distVestaSol[$position], 2)
                *pow($this->flujoSolar[$position], -1);
    }

    /**
     * @param $elementToNormalize
     * @param $f2_element
     * @return float|int
     */
    private function f2Normalization($elementToNormalize, $f2_element)
    {
        return $elementToNormalize/$f2_element;
    }

    /**
     * @param $arrayToAnalyze
     * @return float
     */
    private function eucriteComparation($arrayToAnalyze)
    {
        $f8_euc_compared = pow($arrayToAnalyze[0] - $this->eucriteConstants[0], 2);
        $f2_euc_compared = 0;
        $f7_euc_compared = pow($arrayToAnalyze[2] - $this->eucriteConstants[2], 2);
        $f3_euc_compared = pow($arrayToAnalyze[3] - $this->eucriteConstants[3], 2);
        $f6_euc_compared = pow($arrayToAnalyze[4] - $this->eucriteConstants[4], 2);
        $f4_euc_compared = pow($arrayToAnalyze[5] - $this->eucriteConstants[5], 2);
        $f5_euc_compared = pow($arrayToAnalyze[6] - $this->eucriteConstants[6], 2);

        $euc_compared_resumed = [$f8_euc_compared, $f2_euc_compared, $f7_euc_compared, $f3_euc_compared, $f6_euc_compared, $f4_euc_compared, $f5_euc_compared];

        return sqrt(array_sum($euc_compared_resumed));
    }

    /**
     * @param $arrayToAnalyze
     * @return float
     */
    private function diogeniteComparation($arrayToAnalyze)
    {
        $f8_dio_compared = pow($arrayToAnalyze[0] - $this->diogeniteConstants[0], 2);
        $f2_dio_compared = 0;
        $f7_dio_compared = pow($arrayToAnalyze[2] - $this->diogeniteConstants[2], 2);
        $f3_dio_compared = pow($arrayToAnalyze[3] - $this->diogeniteConstants[3], 2);
        $f6_dio_compared = pow($arrayToAnalyze[4] - $this->diogeniteConstants[4], 2);
        $f4_dio_compared = pow($arrayToAnalyze[5] - $this->diogeniteConstants[5], 2);
        $f5_dio_compared = pow($arrayToAnalyze[6] - $this->diogeniteConstants[6], 2);

        $euc_compared_resumed = [$f8_dio_compared, $f2_dio_compared, $f7_dio_compared, $f3_dio_compared, $f6_dio_compared, $f4_dio_compared, $f5_dio_compared];

        return sqrt(array_sum($euc_compared_resumed));
    }

    /**
     * @param $arrayToAnalyze
     * @return float
     */
    private function howarditeComparation($arrayToAnalyze)
    {
        $f8_how_compared = pow($arrayToAnalyze[0] - $this->howarditeConstants[0], 2);
        $f2_how_compared = 0;
        $f7_how_compared = pow($arrayToAnalyze[2] - $this->howarditeConstants[2], 2);
        $f3_how_compared = pow($arrayToAnalyze[3] - $this->howarditeConstants[3], 2);
        $f6_how_compared = pow($arrayToAnalyze[4] - $this->howarditeConstants[4], 2);
        $f4_how_compared = pow($arrayToAnalyze[5] - $this->howarditeConstants[5], 2);
        $f5_how_compared = pow($arrayToAnalyze[6] - $this->howarditeConstants[6], 2);

        $euc_compared_resumed = [$f8_how_compared, $f2_how_compared, $f7_how_compared, $f3_how_compared, $f6_how_compared, $f4_how_compared, $f5_how_compared];

        return sqrt(array_sum($euc_compared_resumed));
    }

    /**
     * @param $pixel
     * @return bool
     */
    private function checkPixelIfValid($pixel)
    {
        if((str_replace(',', '.', $pixel) == 0) ||
            empty($pixel) ||
            is_null($pixel) ||
            is_nan($pixel)
        ) {
            return false;
        }
        return true;
    }

    /**
     * @param $iteration
     * @return array
     */
    private function coordinatesCalculation($iteration)
    {
        if($iteration%1024 === 0) {
            $pixel_x = $this->coord_x;
            $pixel_y = $this->coord_y - ($iteration/1024);
        }

        if($iteration > 0 && $iteration%1024 !== 0) {
            $pixel_x = $this->coord_x + 0.5*abs(floor($iteration/1024)*1024 - $iteration);
            $pixel_y = $this->coord_y - floor($iteration/1024);
        }

        return [$pixel_x, $pixel_y];
    }
}
