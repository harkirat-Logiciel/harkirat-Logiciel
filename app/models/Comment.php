<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
// use app\blog\Grid\SortableTrait;

class Comment extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'comments';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

    protected $fillable = array('post_id','parent_id','comment');

	public function post(){
        	return $this->belongsTo('Post','post_id');
    	}
	public function users(){
			return $this->belongsTo('User','user_id','id');	
		}
	public function replies(){
			return $this->hasMany('Comment','parent_id');
		}
	public function reply(){
			return $this->belongsTo(Comment::class);
		}
}
