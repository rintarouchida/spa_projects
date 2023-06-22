<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\Master\TagService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TagController extends Controller
{
    protected $service;

    public function __construct(TagService $service)
    {
        $this->service = $service;
    }

    /**
     *
     * @return array
     */
    public function index(): array
    {
        $data = $this->service->fetchPickUpTags();
        return $data;
    }
}
