<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
    /**
     *
     */
    public function getCountries()
    {
        $countries = DB::table('countries')->get([ 'iso','nicename', 'phonecode',]);
        return $this->respondWithSuccess($countries);
    }
}
