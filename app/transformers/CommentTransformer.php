<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes=['post','users','reply'];
    public function transform($user)
    
    {
        // return $users;
 
        return [
            'id'  => $user->id,
            'post_id'  => $user->post_id,
            'parent_id'  => $user->parent_id,
            'comment'  =>$user->comment,    
            'created_at'  => $user->created_at->format('Y-m-d') . "." .$user->created_at->format('h:m:s'),
            'updated_at'  => $user->updated_at->format('Y-m-d') . "." .$user->created_at->format('h:m:s'),
         
        ];
    }
    public function includePost($user)
    {
        $post = $user->post;
        // dd($post);
        if ($post)
        {
            return $this->item($post, new PostTransformer);
        }
       
    }
    public function includeUsers($users)
    {
        $user = $users->users;
        
        return $this->item($user, new UserTransformer);
    }
    public function includeReply($users)
    {
        $reply = $users->replies;
        
        return $this->collection($reply, new CommentTransformer);
    }
}