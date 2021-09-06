<?php
/**
 *	@author Jeremy Legros
 *	@version 0.1.1
 * 	@license http://opensource.org/licenses/MIT MIT
 */

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Response as Response;
use Illuminate\Http\Response as IlluminateResponse;

/**
 *	Convenient method to format Json response messages, but not only
 */
class ApiResponse extends Response {


	/**
	 *	@param Validation $validator Illuminate\Validation\Validator instance to format errors
	 *	@param boolean $stringify Specify whether the response should be formatted in a string or as an array (default)
	 *	@param array $headers Additional header to append to the request
	 * 	@return json String or Array representation of the validation errors
	 */
	public static function validation ($validator, $message = "Validation Failed", $stringify=false, $headers=array(), $data = null ) {
		
		$status = IlluminateResponse::HTTP_PRECONDITION_FAILED;

		if ( !($validator instanceof Validator) )
			throw new Exception('Argument is not a Validator instance ('.get_class($validator).' found).');

		$response = array('validation'=>'Passed!');

		if ( $validator->fails() ) {
			$errors = $validator->messages()->toArray();
			if ( $stringify ) {
				$response = self::sringifyErrors($errors);
			} else{
				$response = $errors;
				
			}
		}

		$error = array( 
			'error' => array(
				'status_code' => $status,
				'validation' => $response,
				'message' => $message
			)
		);

		if ($data) {
			$error['error']['data'] = $data;
		}
		
		return self::json($error, $status, $headers);
	}

	public static function errorGeneral($message, $headers = array(), $data = array()){
		$status = IlluminateResponse::HTTP_PRECONDITION_FAILED;

		$error  =  array(
			'status_code' => $status,
			'message'     => $message
		);
		
		return self::json( array( 
			'error' => array_merge($error, $data)
		)
		, $status, $headers);
	}
	/**
	 *	@param url $url protocol to redirect to
	 *	@return ApiResponse Response to client
	 */
	public static function makeProtocol ( $url ) {
        $response = self::make();
        $response->header('Location', $url);
        return $response;
    }

	/**
	 *	Similar to 403 Forbidden, but specifically for use when authentication is required and 
	 *  has failed or has not yet been provided. The response must include a WWW-Authenticate header field 
	 *  containing a challenge applicable to the requested resource.
	 *	@param string $message Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorUnauthorized( $message = "Not Authorized", $headers=array()){
		
		$status = IlluminateResponse::HTTP_UNAUTHORIZED;
		return self::json( array( 
			'error' => array(
				'status_code' => $status,
				'message' => $message
			)
		)
		, $status, $headers);
	}

	/**
	 *	The request was a valid request, but the server is refusing to respond to it. 
	 *	Unlike a 401 Unauthorized response, authenticating will make no difference.
	 *	@param string $message Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorForbidden( $message = "Access Forbidden", $headers=array()){
		
		$status = IlluminateResponse::HTTP_FORBIDDEN;
		return self::json( array( 
			'error' => array(
				'status_code' => $status,
				'message' => $message
			)
		)
		, $status, $headers);
	}

	/**
	 *	The request is invalid request.
	 *	@param string $message Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorBadRequest( $message = "Bad Request", $headers=array()){
		
		$status = IlluminateResponse::HTTP_BAD_REQUEST;
		return self::json( array( 
			'error' => array(
				'status_code' => $status,
				'message' => $message
			)
		)
		, $status, $headers);
	}

	/**
	 *	The requested resource could not be found but may be available again in the future. 
	 *	Subsequent requests by the client are permissible.
	 *	@param string $message Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorNotFound( $message = "Not Found", $headers=array()){
		
		$status = IlluminateResponse::HTTP_NOT_FOUND;
		return self::json( array( 
			'error' => array(
				'status_code' => $status,
				'message' => $message
			)
		)
		, $status, $headers);
	}

	/**
	 *	A generic error message, given when an unexpected condition was encountered and no more specific message is suitable.
	 *	@param string $message Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function errorInternal( $message = "An internal error occured.", Exception $e = null, $headers=array()){
		
		$status = IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR;
		if($e) {
			Log::error($e);
			return self::json( array( 
				'error' => array(
					'status_code' => $status,
					'message' => $message,
					'error_details' => $e->getMessage().' in file '.$e->getFile().' on line number '.$e->getLine()
				)
			)
			, $status, $headers);
		}

		return self::json( array( 
			'error' => array(
				'status_code' => $status,
				'message' => $message
			)
		)
		,$status, $headers);
	}

	/**
	 *	The request has been accepted for processing, but the processing has not been completed
	 *	@param data|array $data Message to format
	 *	@param array $headers Additional header to append to the request
	 * 	@return ApiResponse JSON representation of the error message
	 */
	public static function accepted( $data = array(), $headers=array()){
		$status = IlluminateResponse::HTTP_ACCEPTED;
		$data['status'] = $status;
		return self::json( $data, $status, $headers);
	}

	public static function success( $data = array(), $headers=array()){
		$status = IlluminateResponse::HTTP_OK;
		$data['status'] = $status;
		return self::json( $data, $status, $headers);
	}

	public static function withPagination($items,$data) {
		$data = array_merge($data,[
			'paginator' =>[
				'total_count' => $items->getTotal(),
				'total_page' => ceil($items->getTotal()/$items->getPerPage()), 
				'current_page' => $items->getCurrentPage(),
				'limit' => $items->getPerPage()
			]
		]);
			
		return self::success($data);
	}

	/**
	 *	@param array $array Message to format
	 *	@return boolean true is associative array, false otherwise
	 */
	protected static function isAssocArray( $array ){
		if ( empty($array) ) return false;
    	return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	protected static function sringifyErrors($errors){
		$response = '';
		if ( is_array($errors) ) {
			foreach ($errors as $key => $value) {
				if ( self::isAssocArray($value) ){
					$response .= $key.' ';
					foreach ($value as $key => $val) {
						$response .= strtolower($key).'. ';
					}
				}
				else for ($i=0; $i <count($value) ; $i++) { 
						$response .= $value[$i].' ';
				}
			}
		}
		else $response .= $errors;

		return $response;
	}
}