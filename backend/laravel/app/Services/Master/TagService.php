<?php

namespace App\Services\Master;

use App\Models\Tag;

class TagService
{
    /**
     * @return array
     */
    public function fetchPickUpTags(): array
    {
        $tags = Tag::all();
        $data = [];
        foreach ($tags as $key => $tag) {
            $data[$key]['id'] = $tag->id;
            $data[$key]['name'] = $tag->name;
        }
        return $data;
    }
}
