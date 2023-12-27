<?php

namespace App\Http\Controllers;

use App\Http\Requests\Party\RegisterRequest;
use App\Services\PartyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

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

    /**
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->all();
        $user_id = Auth::id();
        $this->service->register($data, $user_id);
        return response()->json(['message' => 'もくもく会の作成が完了しました。'], 200);
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
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function join(Request $request): JsonResponse
    {
        $party_id = $request->party_id;
        $this->service->join($party_id);
        return response()->json(['message' => '参加が完了しました。'], 200);
    }
}
