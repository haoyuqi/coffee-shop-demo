<?php

namespace App\Utilities;

use App\Models\Tag;
use Illuminate\Support\Arr;

class Tagger
{
    public static function tagCafe($cafe, $tags, $userId)
    {
        foreach (Arr::wrap($tags) as $tag) {
            $name = trim($tag);

            $newCafeTag = Tag::firstOrNew(['name' => $name]);
            $newCafeTag->name = $name;
            $newCafeTag->save();

            $cafe->tags()->syncWithoutDetaching([$newCafeTag->id => ['user_id' => $userId]]);
        }
    }
}
