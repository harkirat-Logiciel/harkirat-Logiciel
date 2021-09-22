<?php 
namespace transformers;

use League\Fractal\TransformerAbstract;

class ExcelTransformer extends TransformerAbstract
{
   
    public function transform($posts)
    {
        return  [
            'User_name'  =>  $posts->users->first_name,
            'Title'  =>  $posts->title,
            'Marked_by_User' => $posts->usersmarked->first_name,
            'Description'  => $posts->description,    
        ];   
    }
}
