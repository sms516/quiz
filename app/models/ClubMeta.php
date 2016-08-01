<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class ClubMeta extends Model
{
    use SoftDeletes;
	 protected $table = 'club_meta';
	protected $primaryKey ='club_meta_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_id','meta_name','meta_val'];

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
	public static function getClubMeta($club)
	{
		return ClubMeta::where('club_id','=',$club)->get();	
	}
	public static function getClubMetaByName($club,$name)
	{
		$result=ClubMeta::where('club_id','=',$club)
		->where('meta_name','=',$name)->get();
		if(count($result)==1)
		{
			$r=$result->pop();
			return $r->meta_val;
		}
		else return NULL;
	}
	public static function deleteRecord($club,$name)
	{
		$result=ClubMeta::where('club_id','=',$club)
		->where('meta_name','=',$name)->delete();
	}
}
