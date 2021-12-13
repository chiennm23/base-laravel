<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TestController extends Controller
{
    public function index()
    {
        $array = [100, '200', 300, '400', 500];
        $string = ['Laravel'];
//        return Arr::where($array, function ($value, $key) {
//            return !is_string($value);
//        });
        return Arr::wrap($string);
    }
}
