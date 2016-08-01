<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ClubMembershipStream extends Model
{
	 protected $table = 'club_membership_stream';
	protected $primaryKey ='club_membership_stream_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_membership_stream_id','club_membership_id','club_stream_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
	protected $dates= ['deleted_at'];
	public static function getByMembership($club_membership_id)
	{
		return ClubMembershipStream::where('club_membership_id','=',$club_membership_id)->get();	
	}
	public static function deleteByMembership($id)
	{
		ClubMembershipStream::where('club_membership_id','=',$id)->delete();
	}
	public static function deleteByStream($id)
	{
		ClubMembershipStream::where('club_stream_id','=',$id)->delete();
	}
	public function write()
	{
		$this->save();
	}
	
}
