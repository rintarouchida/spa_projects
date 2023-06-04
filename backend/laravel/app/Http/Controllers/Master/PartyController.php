<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Services\Master\PartyService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PartyController extends Controller
{
    protected $service;

    public function __construct(PartyService $service)
    {
        $this->service = $service;
    }

    /**
     * おすすめのもくもく会を抽出(現状は7日以内に作られたものをピックアップ)
     *
     * @return array
     */
    public function index(): array
    {
        $data = $this->service->fetchPickUpParties();
        return $data;
    }
}
