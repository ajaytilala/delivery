<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Validator;
use App\Helper\FunctionUtils;

class AuthController extends Controller
{
    public function login(Request $request){
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ],[
                'email.required' => 'Please enter email',
                'password.required' => 'Please enter password',
            ]);

            if ($validator->fails()) {
                return response()->json(FunctionUtils::getValidationError($validator),200,[],JSON_FORCE_OBJECT);
            }

            if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                $user = Auth::guard('sanctum')->user();
                // $user->tokens()->delete();
                $token = $user->createToken('auth_token')->plainTextToken;
                $return['status'] = 1;
                $return['message'] = 'Login successfully!';
                $return['access_token'] = $token;
                $return['token_type'] = 'Bearer';
                $return['data'] = $user;
            } else {
                $return['message'] = 'Wrong email or password';
            }

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    public function register(Request $request) {

        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {

            $validator = Validator::make($request->all(), [
                'name'                  => ['required'],
                'email'                 => ['required', 'email', 'unique:users'],
                'password'              => ['required', 'min:6', 'confirmed'],
                'password_confirmation' => ['required'],
            ],[
                'name.required'         => 'Please enter name!',
                'email.required'        => 'Please enter email!',
                'password.required'     => 'Please enter password!',
            ]);

            if ($validator->fails()) {
                return response()->json(FunctionUtils::getValidationError($validator),200,[],JSON_FORCE_OBJECT);
            }

            User::create([
                'user_type'     => 'blogger',
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'status'        => 1,
            ]);

            $return['status'] = 1;
            $return['message'] = 'Registered successfully, You can login with email and password.';

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    public function profile(Request $request)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {
            $user = Auth::guard('sanctum')->user();
            $return['status'] = 1;
            $return['message'] = '';
            $return['data'] = $user;
            return response()->json($return, 200,[]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }


    public function logout(Request $request)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {
            $user = \Auth::guard('sanctum')->user();
            $user->tokens()->delete();
            // $user->currentAccessToken()->delete();
            // Auth::guard('sanctum')->user()->tokens()->delete();
            $return['status']   = 1;
            $return['message']  = 'You have successfully logged out.';
        } catch (\Exception $e) {
            $return['status']   = 0;
            $return['error']    = $e->getMessage();
        }

        return response()->json($return);
    }
}
