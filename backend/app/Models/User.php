<?php

namespace App\Models;

use App\Models\Profiles\UserCertificate;
use App\Models\Profiles\UserEducation;
use App\Models\Profiles\UserExperience;
use App\Models\Threads\Comment;
use App\Traits\Paging;
use App\Traits\Searchable;
use App\Traits\Uuids;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Uuids, Paging, Searchable;

    protected $table = 'com_users';
    protected $fillable = [
        'id',
        'role_id',
        'name',
        'email',
        'password',
        'login_by',
        'verified_kode',
        'is_verify',
        'verified_date',
        'google_token',
        'google_id',
        'fb_token',
        'is_active',
        'token_linov',
        'created_by',
        'updated_by',
        'is_consultant',
        'category_consultant_id',
        'version',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'token_linov',
        'google_token',
        'fb_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'verified_date' => 'datetime',
    ];

    public function notifications()
    {
        return $this->morphMany(ComNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    /**
     * Get the comments for the blog post.
     */
    public function sessions()
    {
        return $this->hasMany(\App\Models\Profiles\Session::class);
    }

    /**
     * Get the comments for the blog post.
     */
    public function profile()
    {
        return $this->hasOne(\App\Models\Profiles\Profile::class)->selectRaw("com_user_profiles.*, CONCAT('" . url('/') . '/' . "', photo) photo");
    }

    /**
     * Get the comments for the blog post.
     */
    public function category()
    {
        return $this->hasOne(\App\Models\Consults\Category::class, 'id', 'category_consultant_id');
    }

    /**
     * Get the comments for the blog post.
     */
    public function socials()
    {
        return $this->hasOne(\App\Models\Profiles\UserSocial::class)->leftJoin(DB::raw('(SELECT id, name, icon FROM com_social_medias) AS com_social_medias'), 'com_social_medias.id', '=', 'com_user_socials.social_media_id');
    }

    /**
     * Get the experiences for the blog post.
     */
    public function experiences()
    {
        return $this->hasMany(UserExperience::class)->where('is_active', '1');
    }

    /**
     * Get the experiences for the blog post.
     */
    public function educations()
    {
        return $this->hasMany(UserEducation::class)
            ->selectRaw('com_user_educations.id,user_id,is_current,start_date,end_date,other_title,other_university,other_major,com_title_degrees.id title_degree_id, com_title_degrees.name degree_name,com_universities.id university_id, com_universities.name university_name, com_majors.id major_id, com_majors.name major_name')
            ->leftJoin('com_title_degrees', 'com_title_degrees.id', '=', 'com_user_educations.title_degree_id')
            ->leftJoin('com_universities', 'com_universities.id', '=', 'com_user_educations.university_id')
            ->leftJoin('com_majors', 'com_majors.id', '=', 'com_user_educations.major_id');
    }

    /**
     * Get the experiences for the blog post.
     */
    public function certificates()
    {
        return $this->hasMany(UserCertificate::class);
    }

    /**
     * Set the user's first name.
     *
     * @param  string  $value
     * @return void
     */
    public function hasFilledProfile()
    {
        return $this->profile()->count() > 0;
    }

    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return !is_null($this->verified_date) && $this->is_verify == 1;
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'verified_date' => $this->freshTimestamp(),
            'is_verify' => '1'
        ])->save();
    }

    /**
     * Get thumbnail avatar
     *
     * @return bool
     */
    public function getAvatar()
    {
        $profile = $this->profile()->select('photo')->first();

        if ($profile && Storage::exists($profile->photo)) {
            return asset($profile->photo);
        }
    }

    public function isAdmin()
    {
        return $this->role_id == '389963c2-8abf-461a-b2fc-cdf989679cc1';
    }

    //    /**
    //  * Get humanize date
    //  *
    //  * @param  string  $value
    //  * @return string
    //  */
    // public function getHasVerifiedAttribute($value)
    // {
    //     return $this->role_id==self::ADMIN_ROLE || $this->is_consultant=='1';
    // }

    public function scopeHaveThread($query, $id, $type = 'thread')
    {
        return $query->when($type == 'thread', function ($query) use ($id) {
            return $query->join(DB::raw("(select id thread_id, user_id from hom_threads) thread"), 'thread.user_id', 'com_users.id')->where('thread_id', $id)->where('user_id', '!=', auth()->id());
        })->when($type == 'consult', function ($query) use ($id) {
            return $query->join(DB::raw("(select id thread_id, user_id from con_consul_threads) thread"), 'thread.user_id', 'com_users.id')->where('thread_id', $id)->where('user_id', '!=', auth()->id());
        });
    }

    public function scopeHaveComment($query, $id, $type = 'thread')
    {
        return $query->when($type == 'thread', function ($query) use ($id) {
            $query->join(DB::raw("(select id comment_id, thread_id, user_id from hom_comment_threads) comment"), 'comment.user_id', 'com_users.id')->where('comment_id', $id)->where('user_id', '!=', auth()->id());
        })->when($type == 'consult', function ($query) use ($id) {
            $query->join(DB::raw("(select id comment_id, con_comment_thread_id thread_id, user_id from con_comment_threads) comment"), 'comment.user_id', 'com_users.id')->where('comment_id', $id)->where('user_id', '!=', auth()->id());
        });
    }
}
