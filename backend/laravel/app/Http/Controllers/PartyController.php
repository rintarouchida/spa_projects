<?php

namespace App\Http\Controllers;

use App\Http\Requests\Party\RegisterRequest;
use App\Services\PartyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartyController extends Controller
{
    protected $service;

    public function __construct(PartyService $service)
    {
        $this->service = $service;
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->all();
        $user_id = Auth::id();
        $this->service->register($data, $user_id);
        return response()->json(['message' => '登録が完了しました。'], 200);
    }

    /**
     * @param int $party_id
     * 
     * @return array
     */
    public function getData(int $party_id): array
    {
        $data = $this->service->getData($party_id);
        return $data;
    }
}
