<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;


class CommentTransformer extends TransformerAbstract
{
    protected $availableIncludes=['post','users','reply'];
    public function transform($comment)
    
    {
        // return $users;
 
        return [
            'id'  => $comment->id,
            'post_id'  => $comment->post_id,
            'parent_id'  => $comment->parent_id,
            'comment'  =>$comment->comment,    
            'created_at'  => $comment->created_at->format('Y-m-d') . "." .$comment->created_at->format('h:m:s'),
            'updated_at'  => $comment->updated_at->format('Y-m-d') . "." .$comment->created_at->format('h:m:s'),
         
        ];
    }
    public function includePost($comment)
    {
        $post = $comment->post;
        // dd($post);
        if ($post)
        {
            return $this->item($post, new PostTransformer);
        }
       
    }
    public function includeUsers($comment)
    {
        $users = $comment->users;
        
        return $this->item($users, new UserTransformer);
    }
    public function includeReply($comment)
    {
        $reply = $comment->replies;

        return $this->collection($reply, new CommentTransformer);
    }
}