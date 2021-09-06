<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;
class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'comment','users'
    ];
    public function transform($users)
    {   
        return [
            'id'  => $users->id,
            // 'user_id'  =>  $users->user_id,
            'title'  =>  $users->title,
            'description'  => $users->description,    
            'created_at'  => $users->created_at->format('Y-m-d') . "." .$users->created_at->format('h:m:s'),
            'updated_at'  => $users->updated_at->format('Y-m-d') . "." .$users->created_at->format('h:m:s'),
        ];
    }
    public function includeComment($users)
    {
        $comment = $users->comment;
        //  dd($comment);
        return $this->collection($comment, new CommentTransformer);
    }
    public function includeUsers($users)
    {
        $user = $users->users;
        
        return $this->item($user, new UserTransformer);
    }
	
}