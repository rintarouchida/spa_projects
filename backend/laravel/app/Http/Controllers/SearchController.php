<?php

namespace App\Http\Controllers;

use App\Http\Requests\Party\SearchPartyRequest;
use App\Services\Search\PartyService;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    protected $service;

    public function __construct(PartyService $service)
    {
        $this->service = $service;
    }

    /**
     * @param SearchPartyRequest $request
     *
     * @return array
     */
    public function index(SearchPartyRequest $request): array
    {
        $params = $request->all();
        $auth_id = Auth::id();
        $data = $this->service->searchParties($params, $auth_id);
        return $data;
    }
}
