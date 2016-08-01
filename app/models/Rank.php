<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rank extends Model
{
    use SoftDeletes;
    protected $table = 'rank';
    protected $primaryKey ='rank_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['martial_art_id','rank_num','rank_name','rank_alias','rank_title','rank_image_url','rank_colour'];

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
    public static function getRankGroupCount($id)
    {
        $result=Rank::where('martial_art_id', '=', $id)->get();
        return sizeof($result);
    }
    public static function getRankGroup($id)
    {
        $result=Rank::where('martial_art_id', '=', $id)->orderBy('rank_num')->get();
        return $result;
    }
    public static function getByNum($rg, $num)
    {
        $result=Rank::where('martial_art_id', '=', $rg)->where('rank_num', '=', $num)->get();
        return $result->pop();
    }
    public function rankAttendance($club)
    {
        return $this->hasMany('App\models\RankAttendance', 'rank_id', 'rank_id')->where('club_id', '=', $club)->first();
    }
}
