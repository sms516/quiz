<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ClubMembership extends Model
{
    use SoftDeletes;
	 protected $table = 'club_membership';
	protected $primaryKey ='club_membership_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_id','membership_type_id','cost','frequency'];

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
	public static function getMemberships($club)
	{
		return ClubMembership::join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
				->where('club_id','=',$club)->get();	
	}
}
