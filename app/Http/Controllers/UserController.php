<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Validator;
use App\Helper\FunctionUtils;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {

            $user = \Auth::guard('sanctum')->user();
            // if($user->user_type == 'admin') {
                $users = User::get();
                foreach($users as $as_user) {
                    $as_user->status = isset(FunctionUtils::$status[$as_user->status]) ? FunctionUtils::$status[$as_user->status] : '';
                }

                $return['status'] = 1;
                $return['message'] = '';
                $return['data'] = $users;
            // }

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {
            $user = \Auth::guard('sanctum')->user();
            // if($user->user_type == 'admin') {
                $validator = Validator::make($request->all(), [
                    'user_type'             => ['required','in:blogger,manager'],
                    'name'                  => ['required'],
                    'email'                 => ['required', 'email', 'unique:users'],
                    'password'              => ['required', 'min:6', 'confirmed'],
                    'password_confirmation' => ['required'],
                    'status'                => ['required','in:0,1'],
                ]);

                if ($validator->fails()) {
                    return response()->json(FunctionUtils::getValidationError($validator),200,[],JSON_FORCE_OBJECT);
                }

                $new_user = User::create([
                    'user_type'     => $request->user_type,
                    'name'          => $request->name,
                    'email'         => $request->email,
                    'password'      => Hash::make($request->password),
                    'status'        => $request->status,
                ]);

                $return['status'] = 1;
                $return['message'] = 'User Created Successfully!';
                $return['data'] = $new_user;
            // }

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';
        try {
            $as_user = \Auth::guard('sanctum')->user();
            // if($as_user->user_type == 'admin') {
                $return['status'] = 1;
                $return['message'] = '';
                $return['data'] = $user;
            // }
            return response()->json($return, 200,[]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {

            $as_user = \Auth::guard('sanctum')->user();
            // if($as_user->user_type == 'admin') {
                $validator = Validator::make($request->all(), [
                    // 'user_type'             => ['nullable','in:blogger,manager'],
                    'name'                  => ['required'],
                    'email'                 => ['required', 'email', 'unique:users,id,'.$as_user->id],
                    'password'              => ['required_with:password_confirmation', 'min:6', 'confirmed'],
                    'password_confirmation' => ['required_with:password'],
                    'status'                => ['required','in:0,1'],
                ]);

                if ($validator->fails()) {
                    return response()->json(FunctionUtils::getValidationError($validator),200,[],JSON_FORCE_OBJECT);
                }

                $user_data = [];
                if($request->user_type) {
                    $user_data['user_type'] = $request->user_type;
                }
                $user_data['name'] = $request->name;
                $user_data['email'] = $request->email;
                if($request->password) {
                    $user_data['password'] = $request->password;
                }
                $user_data['status'] = $request->status;
                $user->update($user_data);

                $return['status'] = 1;
                $return['message'] = 'User Updated Successfully!';
                $return['data'] = $user;
            // }

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';
        try {
            $as_user = \Auth::guard('sanctum')->user();
            if($as_user->user_type == 'admin') {
                $user->delete();
                $return['status'] = 1;
                $return['message'] = 'User Deleted Successfully!';
            }
            return response()->json($return, 200,[]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }
}
