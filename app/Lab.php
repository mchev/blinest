<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{

	protected $guarded = [];

    protected $dates = ['planned_at', 'opened_at', 'closed_at', 'rejected_at'];

    public function pending()
    {
        return $this->where('parent_id', null)
        			->where('closed_at', null)
        			->where('rejected_at', null)
        			->get();
    }

    public function closed()
    {
        return $this->where('parent_id', null)
        			->where('closed_at', '!=', null)
        			->orWhere('rejected_at', '!=', null)
                    ->get();
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
