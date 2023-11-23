<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Pref;
use App\Models\User;
use Illuminate\Http\Request;

class PrefController extends Controller
{
    /**
     * @return array
     */
    public function index(): array
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
