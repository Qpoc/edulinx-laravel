<?php

namespace App\Models\Team;

use Mpociot\Teamwork\TeamworkTeam;
use Illuminate\Support\Facades\Storage;

class Team extends TeamworkTeam
{

    protected $fillable = [
        'name',
        'description',
        'about',
        'owner_id',
        'cover_photo'
    ];

    protected $appends = [
        'cover_photo_url'
    ];


    public function owner()
    {
        return $this->belongsTo(config('teamwork.user_model'), 'owner_id')->without([
            'roles',
            'current_role'
        ]);
    }

    public function users(){

        return $this->belongsToMany(config('teamwork.user_model'), 'team_user', 'team_id', 'user_id');
    }

    public function getCoverPhotoUrlAttribute(){
        if ($this->cover_photo) {
            return Storage::disk('team_cover')->url($this->cover_photo);
        }
    }
}
