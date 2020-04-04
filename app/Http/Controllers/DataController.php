<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DataController extends Controller
{
    public function startAnalysis() {
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

        print_r(sizeof($matches8));
        //1048576
        //1048576
        //1048576
        //1048576
        //1048576
        //1048576
        //1048576
        //1048576


        $arrayResume = array();
        for($i = 0; $i< sizeof($matches1); $i++)
        {
            $f8 = str_replace(',', '.', $matches8[$i][0]);
            $f2 = str_replace(',', '.', $matches2[$i][0]);
            $f7 = str_replace(',', '.', $matches7[$i][0]);
            $f3 = str_replace(',', '.', $matches3[$i][0]);
            $f6 = str_replace(',', '.', $matches6[$i][0]);
            $f4 = str_replace(',', '.', $matches4[$i][0]);
            $f5 = str_replace(',', '.', $matches5[$i][0]);

            $arrayResume[] = [$f8, $f2, $f7, $f3, $f6, $f4, $f5];

            print_r($arrayResume);
            die();
        }

    }
}
