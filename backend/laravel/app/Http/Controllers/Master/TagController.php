<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\Master\TagService;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
