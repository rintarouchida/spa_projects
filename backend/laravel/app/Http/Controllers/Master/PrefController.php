<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pref;
use App\Models\User;

class PrefController extends Controller
{
    public function index()
    {
        $prefs = Pref::all();
        $data = [];
        foreach ($prefs as $key => $pref) {
            $data[$key]['id'] = $pref->id;
            $data[$key]['name'] = $pref->name;
        }
        return $data;
    }
}
