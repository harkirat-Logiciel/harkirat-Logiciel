<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class UserProfile extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
		
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'usersprofile';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
    protected $fillable = array('profile_photo' );
	
	public static function scopeFindOrCreate($id)
    {
        $obj = static::find($id);
        return $obj ?: new static;
    }
	public function user(){
		return $this->belongsTo(User::class,'user_id','id');
    }
}


