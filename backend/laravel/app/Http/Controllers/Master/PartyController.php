<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Services\Master\PartyService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartyController extends Controller
{
    protected $service;

    public function __construct(PartyService $service)
    {
        $this->service = $service;
    }

    /**
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
