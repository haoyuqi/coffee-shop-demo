<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cafe extends Model
{
    public function brewMethods()
    {
        return $this->belongsToMany(BrewMethod::class, 'cafes_brew_methods', 'cafe_id', 'brew_method_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'id');
    }

    public function parent()
    {
        return $this->hasOne(self::class, 'id', 'parent');
    }

    public function userLike()
    {
        return $this->belongsToMany(User::class, 'users_cafes_likes', 'cafe_id', 'user_id')
            ->where('user_id', auth()->id());
    }
}
