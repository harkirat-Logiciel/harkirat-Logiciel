<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;
class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'profile','department'
    ];
    public function transform($data)
    {   
        return [
            'id'  => $data->id,
            'first_name'  =>  $data->first_name,
            'last_name'  =>  $data->last_name,
            'email'  => $data->email,
            // 'email_verified_at' => $data->email_verified_at,
            // 'active'  =>$data->active,
            // 'password'  => $data->password,       
            'created_at'  => $data->created_at->format('Y-m-d') . "." .$data->created_at->format('h:m:s'),
            'updated_at'  => $data->updated_at->format('Y-m-d') . "." .$data->created_at->format('h:m:s'),
        ];
    }
    
    public function includeProfile($data)
    {

        $user = $data->profile;
        return $this->item($user, new UserProfileTransformer);
    }
    public function includeDepartment($data)
    {

        $user = $data->depart;
        return $this->collection($user, new DepartmentTransformer);
    }
}