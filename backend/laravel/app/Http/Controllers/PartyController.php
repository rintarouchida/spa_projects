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

    /**
     * 参加済みか判定
     * @param int $party_id
     *
     * @return bool
     */
    public function checkIfJoined(int $party_id): array
    {
        $data['result'] = $this->service->checkIfJoined($party_id);
        return $data;
    }

    /**
     * もくもく会参加
     *
     * @param Request $request
     */
    public function join(Request $request)
    {
        $party_id = $request->party_id;
        $this->service->join($party_id);
        return response()->json(['message' => '参加が完了しました。'], 200);
    }
}
