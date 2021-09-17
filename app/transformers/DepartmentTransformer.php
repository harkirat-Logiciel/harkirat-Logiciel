<?php 
namespace transformers;
use League\Fractal\TransformerAbstract;
class DepartmentTransformer extends TransformerAbstract
{
    public function transform($data)
    {   
        return [
            'id'  => $data->id,
            'department_name'  =>  $data->department_name,      
            'created_at'  => $data->created_at->format('Y-m-d') . "." .$data->created_at->format('h:m:s'),
            'updated_at'  => $data->updated_at->format('Y-m-d') . "." .$data->created_at->format('h:m:s'),
        ];
    }
    
   
}