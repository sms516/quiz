<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserRank extends Model
{
    use SoftDeletes;
	 protected $table = 'user_rank';
	protected $primaryKey ='user_rank_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','rank_id','grading_notes','grading_date'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	protected $dates= ['deleted_at'];
	public function write()
	{
		$this->save();
	}
	
}
