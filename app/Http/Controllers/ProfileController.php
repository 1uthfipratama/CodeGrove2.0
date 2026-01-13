<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\UserLike;
use App\Models\UserSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function view()
    {
        $archiveCount = Post::where('user_id', -1)->count();
        if (Auth::user()) {
            $userId = Auth::user()->id;
            $archiveCount = Post::where('status', 'archived')
                        ->where('user_id', $userId)
                        ->count();
        }
        $user = Auth::user();
        if ($user->display_picture_path) {
            $profile_picture = 'storage/images/'.$user->display_picture_path;
        }
        else {
            $profile_picture = 'storage/asset/default.svg';
        }
        $topPosts = Post::leftJoin('user_likes', 'posts.id', '=', 'user_likes.post_id')
                ->select('posts.id', 'posts.user_id', 'posts.programming_language_id', 'posts.post_id', 'posts.post_content', DB::raw('COUNT(user_likes.id) as like_count'))
                ->where('posts.user_id', $user->id)
                ->groupBy('posts.id', 'posts.user_id', 'posts.programming_language_id', 'posts.post_id', 'posts.post_content')
                ->orderByDesc('like_count')
                ->limit(3)
                ->get();
        $totalLikeCount = UserLike::join('posts', 'user_likes.post_id', '=', 'posts.id')
                        ->where('posts.user_id', $user->id)
                        ->count();
        $totalPostLiked = UserLike::where('user_id', $user->id)->count();
        $membership = UserSubscription::where('user_id', $user->id)->first();
        return view('profile', ['user' => $user, 'top_posts' => $topPosts, 'total_like_count' => $totalLikeCount, 'total_post_like' => $totalPostLiked, 'membership' => $membership, 'profile_picture' => $profile_picture, 'archiveCount' => $archiveCount]);
    }

    public function viewEditProfile()
    {
        $archiveCount = Post::where('user_id', -1)->count();
        if (Auth::user()) {
            $userId = Auth::user()->id;
            $archiveCount = Post::where('status', 'archived')
                        ->where('user_id', $userId)
                        ->count();
        }
        $user = Auth::user();
        if ($user->display_picture_path) {
            $profile_picture = 'storage/images/'.$user->display_picture_path;
        }
        else {
            $profile_picture = 'storage/asset/default.svg';
        }
        return view('edit_profile', ['profile_picture' => $profile_picture, 'archiveCount' => $archiveCount]);
    }

    public function editProfile(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'dob' => ['required', 'date'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        else if ($request->has('new_password') && $request->new_password!="") {
            $validator = Validator::make($request->all(), [
                'new_password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $user = Auth::user();
        if ($request->has('new_password') && Auth::attempt(['email' => $user->email, 'password' => $request->old_password])) {
            $user->password = bcrypt($request->new_password);
        }
        if ($request->has('dob')) {
            $user->dob = $request->dob;
        }
        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/images'), $imageName);
            // delete previous custom image (if any and not default)
            if ($user->display_picture_path) {
                $prev = public_path('storage/images/' . $user->display_picture_path);
                if (file_exists($prev) && !str_contains(basename($prev), 'default')) {
                    @unlink($prev);
                }
            }
            $user->display_picture_path = $imageName;
        } else {
            $resetPhoto = $request->input('reset_photo');
            if ($resetPhoto && $resetPhoto !== '0') {
                // user requested reset via copying default to images earlier
                // delete previous custom image if present and not default
                if ($user->display_picture_path) {
                    $prev = public_path('storage/images/' . $user->display_picture_path);
                    if (file_exists($prev) && !str_contains(basename($prev), 'default')) {
                        @unlink($prev);
                    }
                }
                // set the copied default filename as the stored picture
                $user->display_picture_path = $resetPhoto;
            }
        }
        $user->save();
        return redirect('profile')->with('success', 'Profile updated successfully.');
    }

    public function resetProfilePicture(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // If user has a custom uploaded image, attempt to delete it
        if ($user->display_picture_path) {
            $imagePath = public_path('storage/images/' . $user->display_picture_path);
            if (file_exists($imagePath)) {
                // Avoid deleting a shared default asset by checking filename
                $base = basename($imagePath);
                $defaults = ['default.svg', 'defaultcopy.svg', 'gg--profile.png', 'gg--profile.svg'];
                if (!in_array($base, $defaults)) {
                    @unlink($imagePath);
                }
            }
        }

        // Reset to default by clearing the stored path
        $user->display_picture_path = null;
        $user->save();

        if ($request->wantsJson() || $request->acceptsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Profile photo reset to default.');
    }

    public function copyDefaultToImages(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $defaultPath = public_path('storage/asset/default.svg');
        if (!file_exists($defaultPath)) return response()->json(['error' => 'Default image missing'], 500);

        // Use fixed name for the copied default so it can be recognized: defaultcopy.svg
        $newName = 'defaultcopy.svg';
        $imagesDir = public_path('storage/images');
        if (!is_dir($imagesDir)) mkdir($imagesDir, 0755, true);
        $dest = $imagesDir . DIRECTORY_SEPARATOR . $newName;

        // Overwrite existing copy if present
        if (file_exists($dest)) {
            @unlink($dest);
        }

        if (!copy($defaultPath, $dest)) {
            return response()->json(['error' => 'Unable to copy file'], 500);
        }

        return response()->json(['success' => true, 'filename' => $newName]);
    }

    public function deleteTempPhoto(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);

        $filename = $request->input('filename');
        if (!$filename) return response()->json(['error' => 'Missing filename'], 400);

        // allow deleting files created by copyDefaultToImages (defaultcopy.svg or default_copy_*)
        if ($filename !== 'defaultcopy.svg' && !str_starts_with($filename, 'default_copy_')) {
            return response()->json(['error' => 'Invalid filename'], 400);
        }

        $path = public_path('storage/images/' . $filename);
        if (file_exists($path)) {
            @unlink($path);
        }

        return response()->json(['success' => true]);
    }
}
