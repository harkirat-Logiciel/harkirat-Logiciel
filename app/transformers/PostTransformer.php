<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;
class PostTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'comment','users','favourites'
    ];
    public function transform($posts)
    {   
        return [
            'id'  => $posts->id,
            // 'user_id'  =>  $posts->user_id,
            'title'  =>  $posts->title,
            'marked_by' => $posts->marked_by,
            'description'  => $posts->description,    
            'created_at'  => $posts->created_at->format('Y-m-d') . "." .$posts->created_at->format('h:m:s'),
            'updated_at'  => $posts->updated_at->format('Y-m-d') . "." .$posts->created_at->format('h:m:s'),
        ];
    }
    public function includeComment($posts)
    {
        $comment = $posts->comment;
        //  dd($comment);
        return $this->collection($comment, new CommentTransformer);
    }
    public function includeUsers($posts)
    {
        $user = $posts->users;
        
        return $this->item($user, new UserTransformer);
    }
    public function includeFavourites($posts)
    {
        $data = $posts->favourites;
        if($data) {
            return $this->item($data,function($data) {
                return [
                    'id'=> $data->id,
                    'first_name'=> $data->first_name,
                ];
        });
        }
    }
}
