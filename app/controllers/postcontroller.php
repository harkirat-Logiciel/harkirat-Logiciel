<?php


use transformers\PostTransformer;
use transformers\ExcelTransformer;
use Sorskod\Larasponse\Larasponse;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;
use User;
// use DB;

class postcontroller extends BaseController {
	protected $response;

    /**
     * ArticlesController constructor.
     */
    public function __construct(Larasponse $response)
    {
        $this->response = $response;
		if((Input::get('include')))
		{
		$this->response->parseIncludes(Input::get('include'));
		}
    }
	/*
	 * Display a listing of the resource.
	 *
	 * @return Response 
	 */
	public function index()
	{    
		if($special=Input::get('favourites'))
			{
			$add=Post::where('is_favourites','LIKE' , "%$special%" )->get();
			$message =[
			   		"data" => $this->response->collection($add,	 new PostTransformer),
				];
			return Response::json($message,200);
			}
		elseif(Input::get('favourites_by'))
			{
				$multi=Post::where ('user_id', Auth::id() )
				->where('is_favourites','=',TRUE)->get();
				$message =[
						"data" => $this->response->collection($multi, new PostTransformer),
						];
				return Response::json($message,200);
			}
				$limit=Input::get('limit');
				$user=Post::paginate($limit);
				$message =[
					"data"=>$this->response->paginatedCollection($user, new PostTransformer),
				];
				return Response::json($message,200);
				
	}
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response 
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	public function store()
	{
		
		// dd(Auth::id());
		$rules=[
            'title'=> 'required|unique:posts|max:20',
            'description'=> 'required|max:200'
        ];
        $validation=Validator::make(Input::all(),$rules);
        if($validation->fails()){
            return Response::json($validation->errors(),412);
        }
		  $add=new Post;
		  $add->user_id=Auth::id();
          $add->title = Input::get('title');
          $add->description = Input::get('description');
          $add->save();
		 $message = [
			 "message"=> "Recored Inseted successfully",
			 "data" => $this->response->item($add, new PostTransformer)
		 ];
		  return Response::json( $message,200);	
		
	}
	public function favourites()
    {
		
		$add=Post::find(Input::get('post_id'));
           if($add){
			// $users=Auth::id();
			// dd($users);
		$status= Input::get('favourites');
        if($status==0){		
			$add->is_favourites= $status;
			$add->marked_by=Auth::id();
			$add->save();
            return Response::json([
                "message" => "Mark as Unfavourites"
            ],200);
        }
        else if($status==1){
			$add->is_favourites= $status;
			$add->marked_by=Auth::id();
			$add->save();
            return Response::json([
                "message" => "Mark as Favourites"
            ],200   );
        }
     	}
		 return Response::json([
			"message" => "invalid post id"
		],200   );
    }
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$users=Post::find($id);
		// $title->title = Request::get('title');
		return $this->response->item($users, new PostTransformer);
 	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function excel()
	{
		$post=Post::all();
		$message = $this->response->collection($post, new ExcelTransformer);
		 Excel::create('post', function($excel) use($message)  {

			$excel->sheet('myexcel', function($sheet) use($message){
		
				$sheet->fromArray(reset($message));
			});
		
		})->export('xls');
	}
              

	/**
	 * Update the specified resource in storage.
	 *  verma
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
	$user=Post::find($id);	
	if ($user){
	$rules=[
		'title' => 'required|max:20|unique:posts,title' . ($id ? ",$id": ''),
		'description' => 'required|max:200',			
	];
	$validation=Validator::make(Input::all(),$rules);
	if($validation->fails()){
		return Response::json($validation->errors(),412);
	}
			$user->title = Request::get('title');
			$user->description = Request::get('description');
			$user->update();
		    $message =[
			"message" =>"Record updated",
			"Data"=> $this->response->item($user, new PostTransformer)
			
		];
		return Response::json($message,200);	
	}
			return Response::json([
				"message" => "records not found"
			  ],404);	

 	}

	public function delete($id)
	{
		
        $user=Post::find($id);
		if($user){
		$user->delete();
		return Response::json([
            "message" => "records deleted successfully"
          ], 200 );	
		}

		return Response::json([
            "message" => "records not found"
          ], 404 );	
    }
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function excelpost()
	{
		$rules=[
			'data' => 'required',			
		];
		$validation=Validator::make(Input::all(),$rules);
		if($validation->fails()){
			return Response::json($validation->errors(),412);
		}
		$insert_data = [];
			$path =Input::file('data')->getRealPath();
			$data = Excel::load($path)->get();
			if(!empty($data) && $data->count() > 0)
			{
			 foreach($data->toArray() as $row )
			 {
				$userid=User::where('first_name', $row['user_name'])->first()->id;
				$userfav=User::where('first_name', $row['marked_by_user'] )->first()->id;
				$post=Post::where('title',$row['title'])->first();
				if($post) 
				{
				   $postId=$post->id;
						if($postId)
						{
							$update_data= [
								'User_id'  		=> $userid,
								'Marked_by' 	=> $userfav,
								'Description'   => $row['description'],
								'created_at'    => Carbon\Carbon::now(),
								'updated_at'    => Carbon\Carbon::now()
							];
							DB::table('posts')->where('id',$postId)->update($update_data);
						}		
				} else{
						$insert_data[]= [
							'User_id'  		=> $userid,
							'Title'  		=> $row['title'],
							'Marked_by'   	=> $userfav,
							'Description'   => $row['description'],
							'created_at'    => Carbon\Carbon::now(),
							'updated_at'    => Carbon\Carbon::now()
						];
					}
			 }
			 if(!empty($insert_data))
				{
					DB::table('posts')->insert($insert_data, new ExcelTransformer);
					return Response::json([
						"message" => "records inserted"
					], 200 );	
				}
			 return Response::json([
				"message" => "records updated"
			  ], 200);	
			}
	}
	   
}

