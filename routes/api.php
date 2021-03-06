<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/', function () {

    $result = ['result' => 'OK',
               'data' => 'No Data Yet'];
  
    $response = \Response::json($result)->setStatusCode(200, 'Success');
  
    return $response;
  
  });


  Route::post('signup', function(Request $request){ // [1]

    // [2]
    $name = \Request::get('name');
    $email = \Request::get('email');
    $password = \Request::get('password');
  
    $password_bcrypt = bcrypt($password);
  
    // [3]
    $validator = Validator::make(
         [
             'name' => $name,
             'email' => $email,
             'password' => $password
         ],
         [
             'name' => 'required',
             'email' => 'required|email|unique:users',
             'password' => 'required|min:6'
         ]
     );
  
    // [4]
    if ($validator->fails()){
  
      $result = ['result' => 'Failed',
                 'message' => 'Some of the requirements are not met'];
  
       $response = \Response::json($result)->setStatusCode(400, 'Fail');
  
       // [5]
       return $response;
  
    } else {
  
      // [6]
      $user = new \App\User;
  
      $user->name = $name;
      $user->email = $email;
      $user->password = $password_bcrypt;
  
      $user->save();
  
      $result = ['result' => 'Success',
                 'message' => 'Account '. $name . ' with email '. $email . ' was created'];
  
       $response = \Response::json($result)->setStatusCode(200, 'Success');
       return $response;
  
    }
  
  });

  Route::post('login', function(Request $request){

    $email = \Request::get('email');
    $password = \Request::get('password');
  
    // [1]
    $user = \App\User::where('email','=', $email)->first();
  
    // IF THE THERE IS EMAIL MATCHED
    if ($user != null){
  
      if (Hash::check($password, $user->password)){
  
          $result = ['result' => 'Success',
                     'message' => 'Password correct',
                                       'user_id' => $user->id];
  
          $response = \Response::json($result)->setStatusCode(200, 'Success');
          return $response;
  
      }else{
  
        $result = ['result' => 'Failed',
                   'message' => 'Password Incorrect'];
  
        $response = \Response::json($result)->setStatusCode(201, 'Failed');
        return $response;
  
      }
  
    // NOT MATCHED
    }else{
  
      $result = ['result' => 'Failed',
                 'message' => 'User with email not found.Create New Account'];
                 
  
      $response = \Response::json($result)->setStatusCode(202, 'Failed');
      return $response;
  
    }
  
  });
  