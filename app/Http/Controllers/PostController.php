<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\{User,Post};
use Auth;
use Validator;
use App\Helper\FunctionUtils;

class PostController extends Controller
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
            if($user) {
                if($user->user_type == 'blogger') {
                    $posts = Post::select('posts.*', 'users.name as user_name')
                    ->join("users",function($join) {
                        $join->on('users.id', '=', 'posts.user_id');
                    })
                    ->where('user_id', $user->id)->orderBy('posts.id', 'desc')->get();
                } elseif($user->user_type == 'manager') {
                    $posts = Post::select('posts.*', 'users.name as user_name')
                    ->join("users",function($join) {
                        $join->on('users.id', '=', 'posts.user_id');
                    })
                    ->where('users.user_type', '!=', 'admin')
                    ->orderBy('posts.id', 'desc')->get();
                } else {
                    $posts = Post::select('posts.*', 'users.name as user_name')
                    ->join("users",function($join) {
                        $join->on('users.id', '=', 'posts.user_id');
                    })
                    ->orderBy('posts.id', 'desc')->get();
                }
                foreach($posts as $post) {
                    $post->status = isset(FunctionUtils::$postStatus[$post->status]) ? FunctionUtils::$postStatus[$post->status] : '';
                }

                $return['status'] = 1;
                $return['message'] = '';
                $return['data'] = $posts;
            }

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

            $validator = Validator::make($request->all(), [
                'user_id'               => ['nullable'],
                'title'                 => ['required'],
                'content'               => ['required'],
                'status'                => ['required', 'in:0,1,2'],
            ]);

            if ($validator->fails()) {
                return response()->json(FunctionUtils::getValidationError($validator),200,[],JSON_FORCE_OBJECT);
            }

            $post = Post::create($request->post());
            $post->slug = FunctionUtils::createSlug($post->title);
            $post->user_id = $request->user_id ? $request->user_id : $user->id;
            $post->save();

            $return['status'] = 1;
            $return['message'] = 'Post Created Successfully!';
            $return['data'] = $post;

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';
        try {

            $user = \Auth::guard('sanctum')->user();
            if($user) {
                if($user->user_type == 'blogger') {
                    if($post->user_id == $user->id) {
                        $return['status'] = 1;
                        $return['message'] = '';
                        $return['data'] = $post;
                    }
                } else {
                    $return['status'] = 1;
                    $return['message'] = '';
                    $return['data'] = $post;
                }

            }

            return response()->json($return, 200,[]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';

        try {

            $user = \Auth::guard('sanctum')->user();

            $validator = Validator::make($request->all(), [
                'title'                 => ['required'],
                'content'               => ['required'],
                'status'                => ['required', 'in:0,1,2'],
            ]);

            if ($validator->fails()) {
                return response()->json(FunctionUtils::getValidationError($validator),200,[],JSON_FORCE_OBJECT);
            }

            $post_data = [];
            $post_data['title'] = $request->title;
            $post_data['slug'] = FunctionUtils::createSlug($request->title);;
            $post_data['content'] = $request->content;
            $post_data['status'] = $request->status;
            $post->update($post_data);

            $return['status'] = 1;
            $return['message'] = 'Post Updated Successfully!';
            $return['data'] = $post;

            return response()->json($return, 200,[]);

        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $return = array();
        $return['status'] = 0;
        $return['message'] = 'Something went wrong, Please try again later!';
        try {
            $post->delete();
            $return['status'] = 1;
            $return['message'] = 'Post Deleted Successfully!';
            return response()->json($return, 200,[]);
        } catch (\Exception $e) {
            return response()->json(["status" => 0, "message" => "Something went wrong, Please try again later", 'error' => $e->getMessage()]);
        }
    }
}
