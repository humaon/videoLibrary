<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use JWTAuth;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Video::withCount('likes')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required'

        ]);
        if ($validator->fails()) {

            return ['error' => ['status' => 400, 'message' => $validator->errors()]];
        }

        if(Video::create($request->all())){
            return ['success' => ['status' => 200, 'message' => 'Video has been added successfully']];
        }

    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


       $collection = Video::with('likes.users')->with('comments.users')->find($id);
       $user = JWTAuth::parseToken()->authenticate();
        $logged_in_user_like_status=Like::where('video_id',$id)->where('user_id',$user->id)->exists();

       if(empty($collection ))
       {
           return ['success' => ['status' => 200, 'message' => "Sorry the video was not found"]];
       }




        $newCollection = [];
        $collection_comment=[];


        foreach ($collection->likes as $user) {

            array_push($newCollection, $user->users);




        }
        foreach ($collection->comments as $comment) {
            $collection_comment[]=['comment'=>$comment->comment,'users'=>$comment->users];

        }

        unset($collection->likes);
        unset($collection->comments);

        $collection->likes = ['like' => $logged_in_user_like_status, 'user' => $newCollection];
        $collection->Comments =  $collection_comment;
        return $collection;


    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'url' => 'required'

        ]);
        if ($validator->fails()) {

            return ['error' => ['status' => 400, 'message' => $validator->errors()]];
        }

        if(Video::find($id)->update($request->all())){
            return ['success' => ['status' => 200, 'message' => 'Video has been updated successfully']];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::beginTransaction();

        try {
            Video::destroy($id);
            Like::where('video_id',$id)->delete();
            Comment::where('video_id',$id)->delete();

            DB::commit();
            return ['success' => ['status' => 200, 'message' => "Video Deleted Successfully"]];

        } catch (\Exception $e) {
            DB::rollback();

            return ['error' => ['status' => 400, 'message' => "sorry something went wrong"]];

        }
    }

    public function like_video($video_id)
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }
        if (!Like::where('user_id', $user->id)->where('video_id', $video_id)->exists()) {
            if(Like::create(['user_id' => $user->id, 'video_id' => $video_id]))
            {
                return ['success' => ['status' => 200, 'message' => "You liked the video"]];
            }


        } else {
            if(Like::where('video_id',$video_id)->delete()){
                return ['success' => ['status' => 200, 'message' => "You unliked the video"]];
            }

        }
    }
    public function comment_on_video(Request $request,$video_id)
    {

        $validator = Validator::make($request->all(), [
            'comment' => 'required|string',


        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }


            if(Comment::create(['user_id' => $user->id, 'video_id' => $video_id,'comment'=>$request->comment]))
            {
                return ['success' => ['status' => 200, 'message' => "You comment has been posted"]];
            }




    }
}
