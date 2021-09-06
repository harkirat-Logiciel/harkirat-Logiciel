<?php

// use Illuminate\Http\Response;
use transformers\PostTransformer;
use Sorskod\Larasponse\Larasponse;
// use Illuminate\Support\Facades\Auth;

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
		$limit=Input::get('limit');

		if( Input::get('user_id'))
		{
			$limit=Input::get('limit');
			$user=Post::where('user_id',  Input::get('user_id'))->paginate($limit);
			$message =[
				"data" => $this->response->paginatedCollection($user, new PostTransformer),
			];
			return Response::json($message,200);
		} elseif(Input::get('title'))
		{
			$limit=Input::get('limit');
			$title=Post::where('title','LIKE' , '%' . Input::get('title') . '%'  )->paginate($limit);
			$message =[
				"data" => $this->response->paginatedCollection($title, new PostTransformer),
			];
			
			return Response::json($message,200);
	    } 
	
		$username = Input::get('name');
		if($username) 
		{
			$user = Post::leftJoin('users','posts.user_id', '=','users.id')
			->whereIn('first_name',$username)
			->get();
			$message =[
				"data" => $this->response->collection($user, new PostTransformer),
			];
			return Response::json($message,200);
			
	    }
		$user=Post::paginate($limit);
		$message =[
			"data" => $this->response->paginatedCollection($user, new PostTransformer),
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
	public function edit($id)
	{
       //
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
	if($validation->fails())                                
	{
		return Response::json($validation->errors(),404);
	}
			$user->title = Request::get('title');
			$user->description = Request::get('description');
			$user->update();
		    $message = [
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
	


}
