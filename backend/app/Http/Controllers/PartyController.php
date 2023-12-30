<?php

namespace App\Http\Controllers;

use App\Http\Requests\Party\RegisterRequest;
use App\Services\PartyService;
use App\Http\Requests\Party\SearchPartyRequest;
use App\Http\Requests\Party\UpdateRequest;
use App\Http\Resources\Party\PartyResource;
use App\Http\Resources\Party\PartyCollection;
use App\Models\Party;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
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
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $auth_id = Auth::id();
        $parties = $this->service->fetchPickUpParties($auth_id);
        return PartyCollection::make($parties);
    }

    /**
     * @return ResourceCollection
     */
    public function indexCreated(): ResourceCollection
    {
        $auth_id = Auth::id();
        $parties = $this->service->fetchPickUpCreatedParties($auth_id);
        return PartyCollection::make($parties);
    }

    /**
     * @return ResourceCollection
     */
    public function indexParticipated(): ResourceCollection
    {
        $auth_id = Auth::id();
        $parties = $this->service->fetchPickUpParticipatedParties($auth_id);
        return PartyCollection::make($parties);
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


    public function getData(int $party_id)
    {
        $party = $this->service->getParty($party_id);
        return PartyResource::make($party);
    }

    /**
     * @param int $party_id
     *
     * @return array
     */
    public function checkIfJoined(int $party_id): array
    {
        $data['result'] = $this->service->checkIfJoined($party_id);
        return $data;
    }

    /**
     * @param int $party_id
     *
     * @return array
     */
    public function checkIfEditable(int $party_id): array
    {
        $auth_id = Auth::id();
        $party = Party::find($party_id);
        $data['result'] = $party->leader_id === $auth_id && $party->created_at->diffInHours(Carbon::now()) < 24;
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
     * @return ResourceCollection
     */
    public function search(SearchPartyRequest $request): ResourceCollection
    {
        $params = $request->all();
        $auth_id = Auth::id();
        $parties = $this->service->searchParties($params, $auth_id);
        return PartyCollection::make($parties);
    }

    /**
     * @param int $party_id
     *
     * @return JsonResource|JsonResponse
     */
    public function edit(int $party_id)
    {
        $party = $this->service->getParty($party_id);
        $auth_id = Auth::id();
        if ($party->leader_id != $auth_id) {
            return response()->json(['message' => 'ログインユーザー以外が作成したもくもく会の内容は編集できません。'], 400);
        }
        if ($party->created_at->diffInHours(Carbon::now()) > 24) {
            return response()->json(['message' => '作成から24時間経過したもくもく会の内容は編集できません。'], 400);
        }

        return PartyResource::make($party);
    }

    /**
     * @param UpdateRequest $request
     * @param int $party_id
     *
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, int $party_id): JsonResponse
    {
        $params = $request->except(['now_participated_num']);
        $auth_id = Auth::id();

        $query = Party::with(['tags']);

        if (!($query->where('leader_id', $auth_id)->exists())) {
            return response()->json(['message' => 'ログインユーザー以外が作成したもくもく会の内容は更新できません。'], 400);
        }
        $party = $query->first();
        if ($party->created_at->diffInHours(Carbon::now()) > 24) {
            return response()->json(['message' => '作成から24時間経過したもくもく会の内容は変更できません。'], 400);
        }

        $this->service->update($params, $party);

        return response()->json(['message' => '更新が完了しました。'], 200);
    }
}
