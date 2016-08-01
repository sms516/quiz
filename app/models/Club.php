<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\models\ClubMeta;

class Club extends Model
{
    use SoftDeletes;
    protected $table = 'club';
    protected $primaryKey ='club_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['club_name','club_short_name','club_email','club_phone','club_location','club_map_url','martial_art_id'];

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
    public static function getByShortName($club)
    {
        $qry= Club::where('club_short_name', '=', $club);
        $retval = $qry->get();

        return sizeof($retval)=== 1 ? $retval->pop() : $retval;
    }

    public static function getClubUrl($club)
    {
        $qry= Club::where('club_map_url', '=', $club);
        $retval = $qry->get();

        return sizeof($retval)=== 1 ? $retval->pop() : $retval;
    }

    public static function getClubLocation($club)
    {
        $qry= Club::where('club_location', '=', $club);
        $retval = $qry->get();

        return sizeof($retval)=== 1 ? $retval->pop() : $retval;
    }

    public function getLogo()
    {
        $qry= ClubMeta::where('club_id', '=', $this->club_id)
        ->where('meta_name', '=', 'club_logo');
        $retval = $qry->get();
        if (sizeof($retval)==1) {
            $row=$retval->pop();
            return $row->meta_val;
        }
        return null;
    }
    public function getBG()
    {
        $bg=$this->getBGColor();
        if ($bg!=null) {
            return array('type'=>'color','val'=>$bg);
        } else {
            $bg=$this->getBGImg();
            if ($bg!=null) {
                return array('type'=>'img','val'=>$bg);
            }
        }
        return null;
    }
    public function getBGImg()
    {
        $qry= ClubMeta::where('club_id', '=', $this->club_id)
        ->where('meta_name', '=', 'club_bg_image');
        $retval = $qry->get();
        if (sizeof($retval)==1) {
            $row=$retval->pop();
            return $row->meta_val;
        }
        return null;
    }
    public function getBGColor()
    {
        $qry= ClubMeta::where('club_id', '=', $this->club_id)
        ->where('meta_name', '=', 'club_bg');
        $retval = $qry->get();
        if (sizeof($retval)==1) {
            $row=$retval->pop();
            return $row->meta_val;
        }
        return null;
    }
    public function syllabus()
    {
        return $this->hasMany('App\models\Syllabus', 'club_id', 'club_id');
    }
    public function attendanceReq()
    {
        return $this->hasMany('App\models\RankAttendance', 'club_id', 'club_id');
    }
}
