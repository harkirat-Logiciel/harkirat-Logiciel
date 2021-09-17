<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;




class Department extends Eloquent implements UserInterface, RemindableInterface
{

	use UserTrait, RemindableTrait;
		
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'departments';

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
	public function user(){
		return $this->belongsToMany(User::class,'user_department','department_id','user_id');
	}
}

