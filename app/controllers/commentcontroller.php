<?php
// use Illuminate\Http\Response;
use transformers\CommentTransformer;
use Sorskod\Larasponse\Larasponse;
// use Post;
use Traits\SortableTrait;



class commentcontroller extends BaseController {
	
	protected $response;
    use SortableTrait;
    /**
     * ArticlesController constructor.
     */
    public function __construct(Larasponse $response )
    {
        $this->response = $response;	
		if((Input::get('include')))
			{
			$this->response->parseIncludes(Input::get('include'));
			}
    }
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
    {     
		
		
		$add=Comment::query()->paginate();
		$limit=10;
		$query=$this->sort($add,$limit);
			$message =[
				"data" =>$this->response->paginatedCollection($query, new CommentTransformer ),
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
			/*     validation       */
			// $post=Post::where('id','=', Input::get('post_id'))->first('id');
			// dd($post);
			// $check=Post::find($post);
			// if($check)
			// {
				$rules=[
					'post_id'=> 'required',
					'comment'=> 'required|max:200' 
				];
				$validation=Validator::make(Input::all(),$rules);
					if($validation->fails()){
						return Response::json($validation->errors(),412);
					}
						$add=new Comment;
						$add->user_id=Auth::id();
						$add->post_id = Input::get('post_id');
							if($comment=Comment::where('id','=', Input::get('parent_id'))->first())
							{
								if( $firstReply=$comment->reply)
								{
									if($firstReply->reply)
									{ 
									return Response::json(["message"=>"Restriction Reached"],412);
									}
								}
									$add->parent_id = Input::get('parent_id');
									
							}
						$add->comment = Input::get('comment');
						$add->save();
						
						$message = [
							"message"=> "Recored Inseted successfully",
							"Data" => $this->response->item($add, new CommentTransformer)
						];
						return Response::json( $message,200);
			// }
			// $message = [
			// 	"message"=> "Invalid Post",
			// ];
			// return Response::json( $message,404);
		}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$user=Comment::find($id);
		// $title->title = Request::get('title');
		return $this->response->item($user, new CommentTransformer,200);
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
	 *  
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$com=Comment::find($id);	
		if ($com)
			{
				$com->comment = Input::get('comment');
				$com->update();
				$message = [
				"message" =>"Record updated",
				"data"=> $this->response->item($com, new CommentTransformer)
				
				];
				return Response::json($message,200);	
			}
		return Response::json([
			"message" => "records not found"
			 ],404);	
 	}

	public function delete($id)
	{
		$user=Comment::find($id);
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

