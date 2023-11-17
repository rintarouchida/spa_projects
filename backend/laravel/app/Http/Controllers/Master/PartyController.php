<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Services\Master\PartyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $auth_id = Auth::id();
        $data = $this->service->fetchPickUpParties($auth_id);
        return $data;
    }

    /**
     * @return array
     */
    public function indexCreated(): array
    {
        $auth_id = Auth::id();
        $data = $this->service->fetchPickUpCreatedParties($auth_id);
        return $data;
    }

    /**
     * @return array
     */
    public function indexParticipated(): array
    {
        $auth_id = Auth::id();
        $data = $this->service->fetchPickUpParticipatedParties($auth_id);
        return $data;
    }
}
