<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;


class UserProfileTransformer extends TransformerAbstract
{
   
    public function transform($user)
    
    {
        // return $users;
 
        return [
            'id'  => $user->id,
            'profile'  =>\URL::asset($user->profile),    
            'user_id'  =>$user->user_id,    
            'created_at'  => $user->created_at->format('Y-m-d') . "." .$user->created_at->format('h:m:s'),
            'updated_at'  => $user->updated_at->format('Y-m-d') . "." .$user->created_at->format('h:m:s'),
         
        ];
    }
  
}