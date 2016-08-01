<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class UserMembership extends Model
{
	use SoftDeletes;
    protected $table = 'user_membership';
	protected $primaryKey ='user_membership_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','family_id','club_id','club_membership_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	public function write()
	{
		$this->save();
	}
	public static function deleteForUserClub($user,$club)
	{
		return UserMembership::where('user_id','=',$user)->where('club_id','=',$club)->delete();
	}
	public static function deleteByClubMembership($membership,$club)
	{
		return UserMembership::where('club_membership_id','=',$membership)->where('club_id','=',$club)->delete();
	}
	public static function getByUser($club,$user)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('club_membership_stream','club_membership_stream.club_membership_id','=','club_membership.club_membership_id')
		->where('club_membership.club_id','=',$club)
		->where('user_membership.user_id','=',$user)->get();
	}
	public static function getFamilyByClub($club)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
		->join('family','user_membership.family_id','=','family.family_id')
		->where('club_membership.club_id','=',$club)
		->whereNotNull('user_membership.family_id')
		->whereNotIn('frequency', array('Session'))
		->groupBy('user_membership.family_id')->groupBy('user_membership.club_membership_id')->get();
	}
	public static function getByClub($club)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
		->join('users','users.user_id','=','user_membership.user_id')
		->where('club_membership.club_id','=',$club)
		->whereNotIn('frequency', array('Session'))
		->whereNull('family_id')->get();
	}
	public static function getMonthlyFamilyByClub($club)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
		->join('family','user_membership.family_id','=','family.family_id')
		->where('club_membership.club_id','=',$club)
		->whereNotNull('user_membership.family_id')
		->where('frequency', '=','Month')
		->groupBy('user_membership.family_id')->groupBy('user_membership.club_membership_id')->get();
	}
	public static function getMonthlyByClub($club)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
		->join('users','users.user_id','=','user_membership.user_id')
		->where('club_membership.club_id','=',$club)
		->where('frequency', '=','Month')
		->whereNull('family_id')->get();
	}
	
	
	
	public static function getSessionFamilyByClub($club)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
		->join('family','user_membership.family_id','=','family.family_id')
		->where('club_membership.club_id','=',$club)
		->whereNotNull('user_membership.family_id')
		->whereIn('frequency', array('Session'))
		->groupBy('user_membership.family_id')->groupBy('user_membership.club_membership_id')->get();
	}
	public static function getSessionByClub($club)
	{
		return UserMembership::join('club_membership','club_membership.club_membership_id','=','user_membership.club_membership_id')
		->join('membership_type','membership_type.membership_type_id','=','club_membership.membership_type_id')
		->join('users','users.user_id','=','user_membership.user_id')
		->where('club_membership.club_id','=',$club)
		->whereIn('frequency', array('Session'))
		->whereNull('family_id')->get();
	}
}
