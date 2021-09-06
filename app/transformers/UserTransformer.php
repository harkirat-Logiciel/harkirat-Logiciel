<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;
class UserTransformer extends TransformerAbstract
{
   
    public function transform($users)
    {   
        return [
            'id'  => $users->id,
            'first_name'  =>  $users->first_name,
            'last_name'  =>  $users->last_name,
            'email'  => $users->email,
            // 'email_verified_at' => $users->email_verified_at,
            // 'active'  =>$users->active,
            // 'password'  => $users->password,       
            'created_at'  => $users->created_at->format('Y-m-d') . "." .$users->created_at->format('h:m:s'),
            'updated_at'  => $users->updated_at->format('Y-m-d') . "." .$users->created_at->format('h:m:s'),
        ];
    }
    
	
}