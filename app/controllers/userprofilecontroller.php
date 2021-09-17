<?php
use Sorskod\Larasponse\Larasponse;
use blog\userprofile\userprofileservice;
// use blog\Repositories\userprofilerepository;
use transformers\UserProfileTransformer;
use Auth;

class userprofilecontroller extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct(userprofileservice $service,  Larasponse $response) {
		$this->response = $response;
		$this->service = $service;
	
	}
	public function profile()
	{
		
		$title=User::where('id',Auth::id())->get();
		
		$message =[
			"data" => $this->response->collection($title, new UserProfileTransformer),
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
	
		$input = Input::all();
		$rules = [
              'profile' => 'required|mimes:jpeg',
		];
		$validator = Validator::make($input, $rules);
		if($validator->fails()) {
			return Response::json($validator->errors(),412);
		}
		if(Input::hasFile('profile')){
			$file = Input::file('profile');
			$image = $this->service->image($file);
			$message = [
				"Message" => 'Profile Uploaded Succesfully',
				"data" => $this->response->item($image,new UserProfileTransformer),
			];
			return Response::json($message, 200);

		}
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
