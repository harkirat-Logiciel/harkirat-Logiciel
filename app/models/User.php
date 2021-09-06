<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;



class User extends Eloquent implements UserInterface, RemindableInterface
{

	use UserTrait, RemindableTrait;
		
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
  
    protected $protected = array('password' );
	protected $rules = ['fullname'=>'required' ,
	'email'=>'required',
    'password'=>'required'];

	protected $fillable = [
		'name', 'email', 'password',
	];
	protected $hidden = [
		 'password', 'remember_token',
	];
	public function posts()
    {
		return $this->hasMany(Post::class,'user_id', 'id');
    }
	public function coments()
    {
		return $this->hasMany(Comment::class,'user_id', 'id');
    }
}

