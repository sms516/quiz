<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Carbon\Carbon;
use App\models\UserRank;
use App\models\UserAttendance;
use App\models\RankAttendance;
use App\models\UserFamily;
use App\models\ClubMembership;
use App\models\UserMembership;
use App\models\UserRankSyllabus;
use App\models\UserPatternSyllabus;
use DB;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    protected $primaryKey ='user_id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['f_name','l_name','username','gender','dob','email','phone', 'password','organization_id'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    protected $dates= ['deleted_at'];
    public function write()
    {
        $this->password=bcrypt($this->password);
        $this->save();
    }
    public function getLoginRedirect()
    {
        $query=User::join('user_role', 'users.user_id', '=', 'user_role.user_id')->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->join('club', 'club.club_id', '=', 'user_role.club_id')
        ->where('users.user_id', '=', $this->user_id);

        $result=$query->get();
        if (count($result)==1) {
            $r=$result->pop();
            return '/'.strtolower($r->club_short_name);
        } else {
            return '/';
        }
    }
    public function hasRole($role, $club=null)
    {
        $query=User::join('user_role', 'users.user_id', '=', 'user_role.user_id')->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->where('role.role_name', '=', $role)
        ->where('users.user_id', '=', $this->user_id);
        if ($club!=null) {
            $query=$query->join('club', 'club.club_id', '=', 'user_role.club_id')
            ->where('club.club_short_name', '=', $club);
        } else {
            $query=$query->whereNull('user_role.club_id');
        }
        $result=$query->get();
        if (sizeof($result)>0) {
            return true;
        }
        return false;
    }
    public function hasPermission($permission, $club)
    {
        $query=User::join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->join('role_permissions', 'role_permissions.role_id', '=', 'role.role_id')
        ->join('permissions', 'permissions.permission_id', '=', 'role_permissions.permission_id')
        ->where('permissions.permission_name', '=', $permission)
        ->where('users.user_id', '=', $this->user_id)
        ->where(function ($query) use ($club) {
            $query->where('user_role.club_id', '=', $club)
             ->orWhereNull('user_role.club_id');
        });
        $result=$query->get();
        if (sizeof($result)>0) {
            return true;
        }
        return false;
    }
    public function currentRank()
    {
        $rank= UserRank::join('rank', 'rank.rank_id', '=', 'user_rank.rank_id')->
                where('user_rank.user_id', '=', $this->user_id)->
                orderBy('grading_date', 'DESC')->take(1)->get();
        return sizeof($rank)=== 1 ? $rank->pop() : null;
    }
    public function gradingDate()
    {
        $rank= UserRank::join('rank', 'rank.rank_id', '=', 'user_rank.rank_id')->
                where('user_rank.user_id', '=', $this->user_id)->
                orderBy('grading_date', 'DESC')->take(1)->get();
        if (sizeof($rank)=== 1) {
            $r=$rank->pop();
            return Carbon::createFromFormat('Y-m-d', $r->grading_date)->toFormattedDateString();
        } else {
            return null;
        }
    }
    public static function getClubMembers($club)
    {
        $query=User::select('users.*', 'rank.*', DB::raw('MIN(rank.rank_num) as currentRank,MAX(user_rank.grading_date) as gradeDate'))
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_role.club_id', '=', $club)
        ->where('role.role_name', '!=', 'Inactive')
        ->groupBy('users.user_id')
        ->orderBy('currentRank')
        ->orderBy('gradeDate')
        ->orderBy('dob')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return $result;
    }
    public function getMembershipType($club)
    {
        $result=UserMembership::join('club_membership', 'club_membership.club_membership_id', '=', 'user_membership.club_membership_id')->
                join('membership_type', 'membership_type.membership_type_id', '=', 'club_membership.membership_type_id')->
                where('user_id', '=', $this->user_id)->where('user_membership.club_id', '=', $club)->get();
        if (count($result)==1) {
            $r=$result->pop();
            return $r->membership_type_name;
        }
        return count($result).' Memberships';
    }
    public static function getClubAlumni($club)
    {
        $query=User::select('users.*', 'rank.*', DB::raw('MIN(rank.rank_num) as currentRank,MAX(user_rank.grading_date) as gradeDate'))
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_role.club_id', '=', $club)
        ->where('role.role_name', '=', 'Inactive')
        ->groupBy('users.user_id')
        ->orderBy('currentRank')
        ->orderBy('gradeDate')
        ->orderBy('dob')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return $result;
    }
    public static function exists($r, $id=null)
    {
        if (isset($r['dobYear'])) {
            $dob=$r['dobYear'].'-'.$r['dobMonth'].'-'.$r['dobDay'];
        } else {
            $dob=$r['dob'];
        }
        $result=User::where('f_name', '=', $r['f_name'])
                ->where('l_name', '=', $r['l_name'])
                ->where('dob', '=', $dob)
                ->where('gender', '=', $r['gender']);
        if ($id!=null) {
            $result=$result->where('user_id', '!=', $id);
        }
        $result=$result->get();
        if (count($result)>0) {
            return true;
        } else {
            return false;
        }
    }
    public static function getClubMember($club, $id)
    {
        $query=User::select('users.*', 'rank.*', DB::raw('MIN(rank.rank_num) as currentRank,MAX(user_rank.grading_date) as gradeDate'))
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_role.club_id', '=', $club)
        ->where('users.user_id', '=', $id)
        ->groupBy('users.user_id')
        ->orderBy('currentRank')
        ->orderBy('gradeDate')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        if (count($result)!=1) {
            return null;
        } else {
            return $result->pop();
        }
    }
    public static function getClubMemberCount($club)
    {
        $query=User::join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->where('user_role.club_id', '=', $club)
        ->groupBy('users.user_id')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return count($result);
    }
    public function rankHistory()
    {
        return UserRank::join('rank', 'rank.rank_id', '=', 'user_rank.rank_id')->
        where('user_rank.user_id', '=', $this->user_id)->
        orderBy('grading_date', 'DESC')->get();
    }
    public function getDisplayName()
    {
        $name='';
        $result=$this->join('user_rank', 'user_rank.user_id', '=', 'users.user_id')
        ->where('user_rank.user_id', '=', $this->user_id)
        ->join('rank', 'rank.rank_id', '=', 'user_rank.rank_id')->orderBy('grading_date', 'Desc')->take(1)->get();
        if (count($result)==1) {
            $rank=$result->pop();
            $name.=$rank->rank_title;
        }
        $name.=$this->f_name.' '.$this->l_name;
        return $name;
    }
    public function getAge()
    {
        return Carbon::createFromFormat('Y-m-d', $this->dob)->age;
    }
    public function getImgSrc()
    {
        if ($this->image_url!=null) {
            return url('/').'/'.$this->image_url;
        } else {
            return url('/images').'/'.$this->gender.'-nophoto.gif';
        }
    }
    public static function createUsername($initials)
    {
        $result=User::where('username', 'like', $initials.'%')->get();
        $username=strtolower($initials.(count($result)+1));
        return $username;
    }
    public static function generatePassword($length = 8)
    {

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);

    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
        $length = $maxlength;
    }

    // set up a counter for how many characters are in the password so far
    $i = 0;

    // add random characters to $password until $length is reached
    while ($i < $length) {

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);

      // have we already used this character in $password?
      if (!strstr($password, $char)) {
          // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }
    }

    // done!
    return $password;
    }

    public static function getClassList($club)
    {
        $query=User::select('users.*', 'rank.*', 'user_rank.grading_date')
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_role.club_id', '=', $club)
        ->where('role.role_name', '!=', 'Inactive')
        ->groupBy('users.user_id')
        ->orderBy('rank.rank_num')
        ->orderBy('user_rank.grading_date', 'DESC')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return $result;
    }
    public function getAttendance($stream, $day, $train)
    {
        $attend= $this->hasMany('App\models\UserAttendance')
        ->where('club_stream_id', '=', $stream)
        ->where('training_date', '=', $day)
        ->where('club_training_time_id', '=', $train)
        ->get();
        return sizeof($attend)=== 1 ? $attend->pop() : null;
    }
    public function getFamilyID()
    {
        $family=UserFamily::where('user_id', '=', $this->user_id)->get();
        if (sizeof($family)===0) {
            return null;
        } else {
            $f=$family->pop();
            return $f->family_id;
        }
    }
    public function family($club)
    {
        $family=UserFamily::where('user_id', '=', $this->user_id)->get();
        if (sizeof($family)===0) {
            return null;
        } else {
            $f=$family->pop();
        }
        return User::join('user_family', 'user_family.user_id', '=', 'users.user_id')
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_family.family_id', '=', $f->family_id)
        ->where('user_role.club_id', '=', $club)
        ->where('user_family.user_id', '!=', $this->user_id)
        ->groupBy('users.user_id')
        ->orderBy('user_rank.grading_date', 'DESC')
        ->get();
    }
    public static function memberHasNoFamily($club)
    {
        $hasFamily=User::select('users.user_id')
        ->join('user_family', 'user_family.user_id', '=', 'users.user_id')
        ->join('family', 'family.family_id', '=', 'user_family.family_id')
        ->where('family.club_id', '=', $club)
        ->lists('users.user_id');

        $query=User::select('users.*', 'rank.*')
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_role.club_id', '=', $club)
        ->whereNotIn('users.user_id', $hasFamily)
        ->groupBy('users.user_id')
        ->orderBy('rank.rank_num')
        ->orderBy('user_rank.grading_date', 'DESC')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return $result;
    }
    public static function familyMembers($club, $family)
    {
        $query=User::select('users.*', 'rank.*')
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->join('user_family', 'user_family.user_id', '=', 'users.user_id')
        ->join('family', 'family.family_id', '=', 'user_family.family_id')
        ->where('user_role.club_id', '=', $club)
        ->where('user_family.family_id', '=', $family)
        ->groupBy('users.user_id')
        ->orderBy('rank.rank_num')
        ->orderBy('user_rank.grading_date', 'DESC')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return $result;
    }
    public static function searchClubMember($club, $stream=null, $search)
    {
        $query=User::select('users.*', 'rank.*')
        ->join('user_role', 'users.user_id', '=', 'user_role.user_id')
        ->join('role', 'role.role_id', '=', 'user_role.role_id')
        ->leftJoin('user_rank', 'users.user_id', '=', 'user_rank.user_id')
        ->leftJoin('rank', 'rank.rank_id', '=', 'user_rank.rank_id')
        ->where('user_role.club_id', '=', $club)
        ->where(function ($query) use ($search) {
            $query->where(DB::raw('CONCAT(users.f_name, " ", users.l_name)'), 'LIKE', '%'.$search.'%')
             ->orWhere('users.username', 'LIKE', '%'.$search.'%')
             ->orWhere('users.organization_id', '=', $search);
        })
        ->groupBy('users.user_id')
        ->orderBy('rank.rank_num')
        ->orderBy('user_rank.grading_date', 'DESC')
        ->orderBy('users.l_name')
        ->orderBy('users.f_name');
        $result=$query->get();
        return $result;
    }
    public function getFirstTrainingDate()
    {
        return UserAttendance::where('user_id', '=', $this->user_id)->min('training_date');
    }
    public function getAttendanceCount()
    {
        return count(UserAttendance::where('user_id', '=', $this->user_id)->where('training_date', '>=', $this->currentRank()->grading_date)->get());
    }
    public function hasMetTimeRequirement($club)
    {
        $lastGrade=Carbon::createFromFormat('Y-m-d', $this->currentRank()->grading_date);
        $requirements=RankAttendance::where('club_id', '=', $club)->where('rank_id', '=', $this->currentRank()->rank_id)->get();
        if (count($requirements)>0) {
            $req=$requirements->pop();
            $lastGrade->addMonths($req->time_requirement);
            $today=Carbon::today();
            if ($today->diffInMonths($lastGrade, false)<0) {
                return true;
            } else {
                return $lastGrade->toFormattedDateString();
            }
        }
        return false;
    }
    public function hasMetAttendanceRequirement($club)
    {
        $attend=$this->getAttendanceCount();
        $requirements=RankAttendance::where('club_id', '=', $club)->where('rank_id', '=', $this->currentRank()->rank_id)->get();
        if (count($requirements)>0) {
            $req=$requirements->pop();
            if ($attend>$req->session_requirement) {
                return true;
            } else {
                return $req->session_requirement-$attend;
            }
        }
        return false;
    }
    public function getMemberships($club)
    {
        return ClubMembership::
        join('user_membership', 'club_membership.club_membership_id', '=', 'user_membership.club_membership_id')->
        join('membership_type', 'membership_type.membership_type_id', '=', 'club_membership.membership_type_id')->
        where('user_id', '=', $this->user_id)->
        where('club_membership.club_id', '=', $club)->
        whereNull('user_membership.deleted_at')->get();
    }
    public function syllabusRequirement($s)
    {
        $result=UserRankSyllabus::where('user_id', '=', $this->user_id)->
      where('syllabus_id', '=', $s)->
      where('rank_id', '=', $this->currentRank()->rank_id)->get();
        if (count($result)>0) {
            $row=$result->pop();
            return $row->requirement_met;
        }
        return 0;
    }
    public function patternRequirement($p)
    {
        $result=UserPatternSyllabus::where('user_id', '=', $this->user_id)->
      where('pattern_id', '=', $p)->
      where('rank_id', '=', $this->currentRank()->rank_id)->get();
        if (count($result)>0) {
            $row=$result->pop();
            return $row->requirement_met;
        }
        return 0;
    }
    public function rankSyllabusRequirement($r)
    {
        $result=UserRankSyllabus::where('user_id', '=', $this->user_id)->
      where('rank_syllabus_id', '=', $r)->
      where('rank_id', '=', $this->currentRank()->rank_id)->get();
        if (count($result)>0) {
            $row=$result->pop();
            return $row->requirement_met;
        }
        return 0;
    }
}
