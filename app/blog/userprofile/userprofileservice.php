<?php
namespace blog\userprofile;
use blog\Repositories\userprofilerepository;
use Sorskod\Larasponse\Larasponse;
use Auth;

class userprofileservice {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	function __construct(userprofilerepository $repo, Larasponse $response) {
		$this->response = $response;
		$this->repo = $repo;
	
	}
	
    public function image($file){
          
            $name = "User_pic";
            $extension = $file->getClientOriginalExtension();
            $fileName = $name.Auth::id().'.'.$extension;
			$destinationPath=public_path('/user_profile/');
            $file->move($destinationPath , $fileName);
			$url ='user_profile/' . $fileName;
            $image = $this->repo->save($url);
            return $image;
    }


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
       
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
