<?php


use transformers\UserTransformer;
use Sorskod\Larasponse\Larasponse;
use LucaDegasperi\OAuth2Server\Authorizer;
use app\services\AuthenticationService;



class usercontroller extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	protected $authenticationService;

	 function __construct(Larasponse $response,
	  Authorizer $authorizer,
	  AuthenticationService $authenticationService)
    {
		$this->authorizer = $authorizer;
		$this->authservice = $authenticationService;
         $this->response = $response;
		if((Input::get('include')))
		{
		$this->response->parseIncludes(Input::get('include'));
		}	
    }
	public function index()
	{
		$limit=Input::get('limit');	
		$title=User::where('first_name' , 'LIKE' , '%' . Input::get('first_name') . '%'  )->paginate($limit);
		$message =[
			"data" => $this->response->paginatedCollection($title, new UserTransformer),
		];
		return Response::json($message,200);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function status()
    {
        $rules=[
            'active'=> 'required',
        ];
        $validation=Validator::make(Input::all(),$rules);
        if($validation->fails()){
            return Response::json($validation->errors(),412);
        }
        $status = Input::get('active');
        // $add=new User;
        // $add->is_active = Request::get('is_active');
        // $add->save();
        if($status==0){
			$user=[
				"message" => "inactive"
			];
            return Response::json([
                "message" => "inactive"
            ],200  );
        }
        else if($status==1){
            return Response::json([
                "message" => "active"
            ],200   );
        }
        else{
            return Response::json([
                "message" => "please enter valid boolean number"
            ],404   );
        }
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$rules=[
            'first_name'=> 'required|max:20',
            'last_name'=> 'required|max:20',
            'email'=> 'required|unique:users|max:30',
            'password'=> 'required|unique:users|max:20'		
        ];
        $validation=Validator::make(Input::all(),$rules);
        if($validation->fails()){
            return Response::json($validation->errors(),412);
        }
		  $add=new User;
          $add->first_name =Input::get('first_name');
          $add->last_name =Input::get('last_name');
          $add->email = Input::get('email');
          $add->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		  
          $add->save();
		  
		 $message = [
			 "message"=> "Recored Inseted successfully",
			 "data" => $this->response->item($add, new UserTransformer),
			 
		 ];
		  return Response::json($message,200);	
		
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{ 
		$users=User::find($id);
		if($users)
		{
		return $this->response->item($users, new UserTransformer);
		}
		return Response::json([
            "message" => "records not found"
          ], 404 );	
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function login()
	{
		$input=Input::all();
		$user=User::where('email' ,  $input['username'] )->first();
		//  dd($user);
		$token=$this->authorizer->issueAccessToken();
		// dd($token);
		$token=$this->authservice->verify($token, $user ,$input);
		$message = [
			"message"=> "welcome buddy",
			"data" => $token,
		];
		return Response::json($message,200);	
	}	
 
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user=User::find($id);	
		if ($user){
		$rules=[
			'firstname' => 'required|max:20|unique:users,firstname' . ($id ? ",$id": ''),
			'lastname' => 'required|max:200',
			'email' => 'required|unique:users|max:200',
		];
		$validation=Validator::make(Input::all(),$rules);
		if($validation->fails())                                
		{
			return Response::json($validation->errors(),404);
		}
				$user->firstname = Request::get('firstname');
				$user->lastname = Request::get('lastname');
				$user->email = Request::get('email');
				$user->update();
				$message = [
				"message" =>"Record updated",
				"Data"=> $this->response->item($user, new UserTransformer)
				
			];
			return Response::json($message,200);	
		}
				return Response::json([
					"message" => "records not found"
				  ],404);	
	
		 }
	


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		
        $users=User::find($id);
		if($users)
		{
		$users->delete();
		return Response::json([
            "message" => "records deleted successfully"
          ], 200 );	
		}

		return Response::json([
            "message" => "records not found"
          ], 404 );	
    }


}
