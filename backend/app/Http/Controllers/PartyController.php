<?php

namespace App\Http\Controllers;

use App\Http\Requests\Party\RegisterRequest;
use App\Services\PartyService;
use App\Http\Requests\Party\SearchPartyRequest;
use App\Http\Requests\Party\UpdateRequest;
use App\Models\Party;
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

    //todo:tag_id追加分のテスト修正
    /**
     * @param SearchPartyRequest $request
     *
     * @return array
     */
    public function search(SearchPartyRequest $request): array
    {
        $params = $request->all();
        $auth_id = Auth::id();
        $data = $this->service->searchParties($params, $auth_id);
        return $data;
    }

    /**
     * @param UpdateRequest $request
     * @param int $party_id
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $party_id): JsonResponse
    {
        $params = $request->all();
        $auth_id = Auth::id();

        $query = Party::with(['tags']);

        if (!($query->where('leader_id', $auth_id)->exists())) {
            return response()->json(['message' => 'ログインユーザー以外が作成したもくもく会の内容は更新できません。'], 400);
        }
        $party = $query->first();
        if (!$this->service->isEditableParty($party)) {
            return response()->json(['message' => '作成から24時間経過したもくもく会の内容は変更できません。'], 400);
        }

        $this->service->update($params, $party);

        return response()->json(['message' => '更新が完了しました。'], 200);
    }
}
