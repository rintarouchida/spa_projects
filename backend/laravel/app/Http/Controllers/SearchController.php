<?php

namespace App\Http\Controllers;

use App\Http\Requests\Party\SearchPartyRequest;
use App\Services\Search\PartyService;

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
        $data = $this->service->searchParties($data);
        return $data;
    }
}
