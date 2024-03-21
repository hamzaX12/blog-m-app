<?php

namespace App\Http\Controllers;

// use App\Http\Requests\StoreComment;
use App\Http\Requests\StorePost;
use App\Models\Image;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use DeepCopy\Filter\Filter;
// use Illuminate\Http\Client\Request as ClientRequest;
// use GuzzleHttp\Psr7\Request;
// use App\Models\User;
// use Hamcrest\Core\AllOf;
// use Illuminate\Auth\Access\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
// use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\;
use Illuminate\Support\Str;


class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(
            [
                'index', 'show', 'all', 'archive', 'indexAPI', 'showAPI', 'storeAPI', 'storeCommentAPI', 'composeAPI', 'postsChartApi', "CommentChartApi", "showPostTagTime", 'PostTime'
            ]
        );
        // $this->middleware('auth')->only(['create','edit','update','destroy']);
    }
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::postWithUserAndCommentAndTagsAndImage()->get();
        return view('posts.index', [
            'posts' => $posts,
            'tab' => 'list'
        ]);
    }

    public function archive()
    {
        $posts = Post::onlyTrashed()->withCount('comments')->orderBy('updated_at', 'desc')->get();
        // we get the posts that deleted (by the soft delete)
        return view('posts.index', ['posts' => $posts, 'tab' => 'archive']);
    }

    public function all()
    {
        $posts = Post::withTrashed()->withCount('comments')->orderBy('updated_at', 'desc')->get();
        // we get the posts that not deleted yet
        // $this->authorize();
        return view('posts.index', ['posts' => $posts, 'tab' => 'all']);
    }

    public function restore($id)
    {
        $post = Post::onlyTrashed()->where('id', $id)->first();
        $this->authorize('restore', $post);
        $post->restore();
        return redirect()->route('archive')->with('status', 'the post restored successfully');
    }

    public function forcedelete($id)
    {
        // dd($id);
        $post = Post::onlyTrashed()->where('id', $id)->first();
        $this->authorize('forceDelete', $post);
        $post->forceDelete();

        // dd($post);
        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('create');
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // the only just because the _token is also existe in the object $request
        // so that we filter the input name we want
        $data = $request->only(['title', 'content']);
        $data['slug'] = Str::slug($data['title'], '-');
        $data['active'] = false;
        $data['user_id'] = $request->user()->id;
        $post = Post::create($data);
        // create <==> $fillable
        // upload a picture for the current post
        if ($request->hasFile('picture')) {

            $path = $request->file('picture')->store('posts');
            // $image = new Image(['path' => $path]);

            $post->image()->save(Image::make(['path' => $path]));
        }

        return redirect()->route('posts.show', ['post' => $post->id])->with('status', 'post was created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // 60s
        $post = Cache::remember("post-show-{$id}", 60, function () use ($id) {
            return Post::with(['comments', 'tags', 'comments.user',])->findOrFail($id); //eager way
        });
        return view('posts.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);
        // $this->authorize('post.update',$post);
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $data = Post::findOrfail($id);

        $this->authorize('update', $data);
        // $this->authorizeForUser($user,'post.update',$data);

        if ($request->hasFile('picture')) {

            $path = $request->file('picture')->store('posts');

            if ($data->image) {
                Storage::delete($data->image->path);
                $data->image->path = $path;
                $data->image->save();
            } else {
                $data->image()->save(Image::make(['path' => $path]));
            }
        }

        $data->title = $request->title;
        $data->content = $request->content;
        $data->slug = Str::slug($data->title, '-');
        $data->save();
        return redirect()->route('posts.index')->with('status', 'the post updated successfllu');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $post = Post::findOrFail($id);
        $this->authorize('delete', $post);
        $post->delete(); //or use the simple one
        // Post::destroy($id);

        return redirect()->route('posts.index')->with('success', 'the post deleted successflly');
    }
    ////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////
    // api methods

    public function showAPI($id)
    {
        // return response()->json([
        //     'post' => "this is the best way to till hi",
        //     'status' => 200
        // ]);
        // 60s

        $post = Cache::remember("post-show-{$id}", now()->addMinute(10), function () use ($id) {

            return Post::with(['comments', 'tags', 'comments.user', 'image', 'user'])->findOrFail($id); //eager way

        });

        // $imageUrl = null;
        // if ($post->image) {
        //     $imageUrl = url($post->image->path); // assuming 'url' is the attribute in your Image model storing the image URL
        // }

        return response()->json([
            'post' => $post,
            // "image" => $imageUrl,
            'status' => 200,
        ]);
    }
    public function indexAPI()
    {

        $posts = Cache::remember('posts', now()->addMinute(5), function () {
            return Post::postWithUserAndCommentAndTagsAndImage()->get();
        });

        // $posts = Post::postWithUserAndCommentAndTagsAndImage()->get();

        return response()->json([
            'post' => $posts,
            'status' => 200,
        ]);
    }
    public function storeAPI(Request $request)
    {
        // return response()->json("asda");
        // the only just because the _token is also existe in the object $request
        // so that we filter the input name we want

        $tagssnames = cache()->remember('tagssnames', now()->addMinutes(5), function () {
            // return Tag::pluck('name')->toArray();
            $tagss = Tag::all('name')->toArray();
            return  array_column($tagss, 'name');
        });


        $response = Http::withOptions(['verify' => 'C:\laragon\etc\ssl\cacert.pem'])
            ->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=AIzaSyD7cH4-N9H6z0DDsdVoQQOb4yVEG_2gxyI',
                [
                    "contents" => [
                        [
                            "parts" => [
                                [
                                    "text" => "Based on this post content, please select a tag from " . join(", ", $tagssnames) . " that is suitable for the post. The post content is: " . $request->content .
                                        "else give me an other tag but in one world"
                                ]
                            ]
                        ]
                    ]
                ]
            );




        $data = $request->only(['title', 'content']);
        $data['slug'] = Str::slug($data['title'], '-');
        $data['active'] = false;
        $data['user_id'] = $request->id;
        $post = Post::create($data);
        $tagName = '';
        if ($response->successful()) {
            $tagName = trim(json_decode($response->body())->candidates[0]->content->parts[0]->text);
            $tagName = trim($tagName, "'");
            $tagName = preg_replace('/\s+/', '_', $tagName); // Replace spaces with underscores
            if (Tag::where('name', $tagName)->first() !== null) {
                $tagId = Tag::where('name', $tagName)->pluck('id');
                $post->tags()->sync($tagId);
            } else {
                $tagM = Tag::create([
                    'name' => $tagName
                ]);
                $post->tags()->sync($tagM->id);
                // return response()->json($tagM);
            }
        }

        // create <==> $fillable
        // upload a picture for the current post
        if ($request->hasFile('picture')) {

            $path = $request->file('picture')->store('posts');
            // $image = new Image(['path' => $path]);

            $post->image()->save(Image::make(['path' => $path]));
        }
        return response()->json([
            "message" => "the post was created successfully",
            "status" => 200,
            "tag" => $tagName
        ]);
        // return redirect()->route('posts.show', ['post' => $post->id])->with('status', 'post was created');
    }
    public function storeCommentAPI(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $comment = $post->comments()->create([
            'content' => $request->content,
            'user_id' => $request->user_id
        ]);
        if ($comment) {
            $comments = $post->comments()->with('user')->get();
        }
        return response()->json([
            'messge' => 'to postman i love you so mush',
            "post_comment" => $comments
        ]);
    }
    public function composeAPI()
    {
        // if (Cache::has('mostComments') && Cache::has('UsersMostPostWriting') && Cache::has('UsersActiveInLastMonth')) {
        //     Cache::forget('mostComments');
        //     Cache::forget('UsersMostPostWriting');
        //     Cache::forget('UsersActiveInLastMonth');
        // }
        $mostComments = Cache::remember('mostComments', now()->addMinute(10), function () {
            // $mostComments = Cache::remember('mostComments', now()->addMinute(1), function () {
            return Post::MostCommented()->take(5)->get();
        });
        $UsersMostPostWriting = Cache::remember('UsersMostPostWriting', now()->addMinute(10), function () {
            return User::UsersMostPostWriting()->take(5)->get();
        });
        $UsersActiveInLastMonth = Cache::remember('UsersActiveInLastMonth', now()->addMinute(10), function () {
            return User::userActiveInLastMonth()->take(5)->get();
        });

        return response()->json([
            "postmostComments" => $mostComments,
            "UsersMostPostWriting" => $UsersMostPostWriting,
            "UsersActiveInLastMonth" => $UsersActiveInLastMonth,
            "status" => 200
        ]);
    }
    public function postsChartApi()
    {
        $usersWithPostCount = Cache::remember('countPost', now()->addMinute(10), function () {
            // $mostComments = Cache::remember('mostComments', now()->addMinute(1), function () {
            return User::withCount('posts')->get();
        });
        $filteredPosts = $usersWithPostCount->filter(function ($user) {
            return $user->posts_count > 0;
        });
        $data = [];

        foreach ($filteredPosts as $user) {
            $data[$user->name] = $user->posts_count;
        }

        return response()->json($data);
    }
    public function CommentChartApi()
    {

        $PostsWithCommentCount = Cache::remember('countComment', now()->addMinute(10), function () {
            return Post::withCount('comments')->get();
        });
        $filteredPosts = $PostsWithCommentCount->filter(function ($post) {
            return $post->comments_count > 0;
        });
        $data = [];

        foreach ($filteredPosts as $post) {
            $data[$post->slug] = $post->comments_count;
        }

        return response()->json($data);
    }
    public function showPostTagTime()
    {

        $tagsCount = Cache::remember('countTags', now()->addMinute(10), function () {
            return Tag::withCount('posts')->get();
        });
        $filteredTag = $tagsCount->filter(function ($tag) {
            return $tag->posts_count > 0;
        });
        $data = [];

        foreach ($filteredTag as $tag) {
            $data[$tag->name] = $tag->posts_count;
        }

        // $tag = Tag::withCount('posts')->get();

        return response()->json($data);
    }
    public function PostTime()
    {
        // Group posts by year and count the number of posts for each year
        // $postsByYear = $posts->groupBy(function ($post) {
        //     return $post->created_at->format('Y'); // Group by year
        // })->map->count(); // Count the number of posts for each year

        $tagsCount = Cache::remember('tagsCountMonth', now()->addMinute(10), function () {
            $posts = Post::all();
            return  $posts->groupBy(function ($post) {
                return $post->created_at->format('m'); // Group by year and month
            })->map->count(); // Count the number of posts for each month
        });


        return response()->json($tagsCount);
    }
}
