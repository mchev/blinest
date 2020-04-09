<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{

	protected $guarded = [];

    public function pending()
    {
        return $this->where('parent_id', null)
        			->where('closed_at', null)
        			->where('rejected_at', null)
        			->where('opened_at', null)
        			->where('planned_at', null)
        			->get();
    }

    public function planned()
    {
        return $this->where('parent_id', null)
        			->where('closed_at', null)
        			->where('rejected_at', null)
        			->where('opened_at', null)
        			->where('planned_at', '!=', null);
    }

    public function opened()
    {
        return $this->where('parent_id', null)
        			->where('closed_at', null)
        			->where('rejected_at', null)
        			->where('opened_at', '!=', null);
    }

    public function closed()
    {
        return $this->where('parent_id', null)
        			->where('closed_at', '!=', null)
        			->orWhere('rejected_at', '!=', null);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function childs()
    {
    	return $this->hasMany(Lab::class, 'parent_id', 'id');
    }

    public function votes()
    {
    	return $this->hasMany(LabVotes::class);
    }

    public function voteUp()
    {
    	return $this->hasMany(LabVotes::class)->where('down', null)->count();
    }

    public function voteDown()
    {
    	return $this->hasMany(LabVotes::class)->where('up', null)->count();
    }

}
