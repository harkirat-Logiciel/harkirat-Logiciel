<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;


class Post extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
		
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	
    protected $fillable = array('title' , 'description');
	protected $rules = ['title'=>'required' ,
	'description'=>'required'];
	
	
	public function comment()
   		{
			return $this->hasMany(Comment::class);
		}
	public function users()
    	{
			return $this->belongsTo('User','user_id','id');		
		}
	public function comments()
		{
		 return $this->hasMany(Comment::class,'id')->whereNull('parent_id');
	 }
}


