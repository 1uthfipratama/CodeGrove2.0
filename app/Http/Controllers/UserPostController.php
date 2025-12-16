<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\ProgrammingLanguage;
use App\Models\User;
use App\Models\UserLike;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserPostController extends Controller
{
    public function view()
    {
        $userId = -1;
        $archiveCount = Post::where('user_id', -1)->count();
        if (Auth::user()) {
            $userId = Auth::user()->id;
            $archiveCount = Post::where('status', 'archived')
                        ->where('user_id', $userId)
                        ->count();
        }
        $languages = ProgrammingLanguage::all();
        return view('add_question', ['languages' => $languages, 'userId' => Auth::user()->id, 'archiveCount' => $archiveCount]);
    }


    public function addQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:1',
            'language' => 'required|not_in:""'
        ], [
            'question.required' => 'Question must be filled.',
            'language.required' => 'Please select a programming language.',
            'language.not_in' => 'Please select a programming language.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'programming_language_id' => intval($request->language),
            'post_id' => null,
            'post_content' => $request->question,
            'status' => 'active',
            'created_at' => Carbon::now()
        ]);

        $postId = $post->id;

        return redirect('/post/'.$postId);
    }

    public function detail(Request $request) {
        $userId = -1;
        $archiveCount = Post::where('user_id', -1)->count();
        if (Auth::user()) {
            $userId = Auth::user()->id;
            $archiveCount = Post::where('status', 'archived')
                        ->where('user_id', $userId)
                        ->count();
        }
        $postId = $request->route('postId');
        $post = Post::find($postId);
        $replies = Post::where('post_id', $postId)->get();
        $userLike = UserLike::where('user_id', $userId)->where('post_id', $postId)->exists();
        $likes = UserLike::where('post_id', $postId)->count();
        return view('post_detail', ['post' => $post, 'replies' => $replies, 'userLike' => $userLike, 'likes' => $likes, 'archiveCount' => $archiveCount]);
    }


    public function addReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reply' => 'required|string|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $post = Post::create([
            'user_id' => Auth::user()->id,
            'programming_language_id' => $request->programming_language_id,
            'post_id' => $request->post_id,
            'post_content' => $request->reply,
            'status' => 'active',
            'created_at' => Carbon::now()
        ]);

        $postId = $post->post_id;

        return redirect('/post/'.$postId);

    }

    public function likePost(Request $request)
    {
        $postId = $request->post_id;
        $userId = Auth::user()->id;
        $post = Post::find($postId);
        $userLike = UserLike::where('user_id', $userId)->where('post_id', $postId)->first();
        if ($userLike) {
            $userLike->delete();
            $post->user->decrement('lines', 1);
        }
        else {
            UserLike::create([
                'user_id' => $userId,
                'post_id' => $postId
            ]);
            $post->user->increment('lines', 1);
        }
        return redirect('/post/'.$postId);
    }

    public function markSolution(Request $request, $replyId)
    {
        $reply = Post::find($replyId);

        if (!$reply) {
            return redirect()->back()->with('error', 'Reply not found.');
        }

        $parentPost = Post::find($reply->post_id);

        if (!$parentPost) {
            return redirect()->back()->with('error', 'Parent post not found.');
        }

        if (!Auth::check() || $parentPost->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        Post::where('post_id', $parentPost->id)->where('is_solution', true)->update(['is_solution' => false]);

        $reply->is_solution = true;
        $reply->save();

        $replyAuthor = User::find($reply->user_id);

        if ($replyAuthor) {
            $replyAuthor->increment('lines', 5);
        }

        return redirect('/post/'.$parentPost->id);
    }

    public function unmarkSolution(Request $request, $replyId)
    {
        $reply = Post::find($replyId);

        if (!$reply) {
            return redirect()->back()->with('error', 'Reply not found.');
        }

        $parentPost = Post::find($reply->post_id);

        if (!$parentPost) {
            return redirect()->back()->with('error', 'Parent post not found.');
        }

        if (!Auth::check() || $parentPost->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $reply->is_solution = false;
        $reply->save();

        $replyAuthor = User::find($reply->user_id);

        if ($replyAuthor) {
            $newLines = max(0, $replyAuthor->lines - 5);
            $replyAuthor->update(['lines' => $newLines]);
        }

        return redirect('/post/'.$parentPost->id);
    }

    public function viewEditPost(Request $request) {
        $userId = -1;
        $archiveCount = Post::where('user_id', -1)->count();
        if (Auth::user()) {
            $userId = Auth::user()->id;
            $archiveCount = Post::where('status', 'archived')
                        ->where('user_id', $userId)
                        ->count();
        }
        $postId = $request->route('postId');
        $post = Post::find($postId);
        $languages = ProgrammingLanguage::all();
        return view('edit_question', ['languages' => $languages, 'question' => $post, 'archiveCount' => $archiveCount]);
    }

    public function editPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:1',
            'language' => 'required|not_in:""'
        ], [
            'question.required' => 'Question must be filled.',
            'language.required' => 'Please select a programming language.',
            'language.not_in' => 'Please select a programming language.'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $postId = $request->route('postId');

        $post = Post::find($postId);

        $post->post_content = $request->question;
        $post->programming_language_id = $request->language;
        $post->status = "active";
        $post->save();

        return redirect('/post/'.$postId);
    }

    public function deletePost(Request $request)
    {
        $post = Post::find($request->post_id);
        if (Auth::user() && $post->user_id == Auth::user()->id) {
            $post->delete();
        }
        return redirect('/');
    }
}
