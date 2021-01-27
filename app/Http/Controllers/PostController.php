<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\{Category, Post, Tag};
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        // middleware semacam proteksi controller agar sebelum login tidak bisa langsung masuk ke controller tersebut.
        // except itu adalah method pengecualian selain method yang ada didalam middleware maka ia harus login

        // $this->middleware('auth')->except([
        //     'index',
        //     'show'
        // ]);

        // atau bisa juga di Route grup middlewarenya
    }
    public function index()
    {
        // mendapatkan semua data di table posts
        // $posts = Post::paginate(10); //kalo ga inline
        // inline

        return view('posts.index', [
            'posts' => Post::latest()->paginate(4),
        ]);
    }

    public function show(Post $post)
    {
        $posts = Post::where('category_id', $post->category_id)->latest()->limit(6)->get();
        return view('posts.show', compact('post', 'posts'));
    }

    public function create()
    {
        return view('posts.create', [
            'post' => new Post(),
            'categories' => Category::get(),
            'tags' => Tag::get(),
        ]);
    }

    public function store(PostRequest $postRequest)
    {
        // cara ke 1
        // $post = new Post;
        // $post->title = $request->title; //The first post
        // $post->slug = \Str::slug($request->title); // the-first-post
        // $post->body = $request->body;
        // buat nyimpan ke databasenya
        // $post->save();

        // cara ke 2
        // Post::create([
        //     'title' => $request->title,
        //     'slug' => \Str::slug($request->title),
        //     'body' => $request->body,
        // ]);

        // cara validasi pertama
        // $this->validate($request, [
        //     'title' => 'required|min:3',
        //     'body' => 'required',
        // ]);

        // cara validasi kedua
        // $request->validate([
        //     'title' => 'required|min:3',
        //     'body' => 'required',
        // ]);


        // cara ke 3
        // $post = $request->all();
        // $post['slug'] = \Str::slug($request->title);
        // Post::create($post);


        // cara validasi dan insert ke 4
        // tambahin argumen (Request $request) dimethod
        // $attr = $request->validate([
        //     'title' => 'required|min:3',
        //     'body' => 'required',
        // ]);

        // $attr['slug'] = \Str::slug($request->title);
        // Post::create($attr);


        // cara ke 5
        // validate dengan refactoring pertama dengan buat method
        // $attr = $this->validateRequest();

        // validate tanpa refactoring
        // $attr = request()->validate([
        //     'title' => 'required|min:3',
        //     'body' => 'required',
        // ]);

        // validasi dengan request
        $postRequest->validate([
            'thumbnail' => 'image|mimes:jpeg,png,svg,jpg|max:2048'
        ]);
        $attr = $postRequest->all();
        $slug = Str::slug(request('title'));
        $attr['slug'] = $slug;

        if (request()->file('thumbnail')) {
            $thumbnail = request()->file('thumbnail')->store("images/posts");
        } else {
            $thumbnail = null;
        }

        // Assign title to the slug
        $attr['category_id'] = request('category');
        $attr['thumbnail'] = $thumbnail;

        // create new post
        $post = auth()->user()->posts()->create($attr);

        $post->tags()->attach(request(('tags')));

        // session()->flash('error', 'The post error');
        session()->flash('success', 'The post was created!');

        // return redirect()->to('posts/create');
        return redirect('posts');
        // return back();
    }

    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post,
            'categories' => Category::get(),
            'tags' => Tag::get(),
        ]);
    }

    public function update(PostRequest $postRequest, Post $post)
    {
        $postRequest->validate([
            'thumbnail' => 'image|mimes:jpeg,png,svg,jpg|max:2048'
        ]);

        // postPolicy
        $this->authorize('update', $post);
        // kondisi agar img yg lama hilang dari storage
        if (request()->file('thumbnail')) {
            \Storage::delete($post->thumbnail);

            $thumbnail = request()->file('thumbnail')->store("images/posts");
        } else {
            $thumbnail = $post->thumbnail;
        }

        // validate refactoring dengan request
        $attr = $postRequest->all();
        $attr['category_id'] = request('category');
        $attr['thumbnail'] = $thumbnail;
        // validate dengan refactoring pertama dengan buat method
        // $attr = $this->validateRequest();

        // validate tanpa refactoring
        // $attr = request()->validate([
        //     'title' => 'required|min:3',
        //     'body' => 'required',
        // ]);

        $post->update($attr);
        $post->tags()->sync(request(('tags')));

        session()->flash('success', 'The post was updated!');
        return redirect('posts');
    }

    // METHOD REFACTORING VALIDATE PERTAMA
    // public function validateRequest()
    // {
    //     return request()->validate([
    //         'title' => 'required|min:3',
    //         'body' => 'required',
    //     ]);
    // }


    public function destroy(Post $post)
    {
        // pake can
        $this->authorize('delete', $post);
        \Storage::delete($post->thumbnail);
        $post->tags()->detach();
        $post->delete();
        session()->flash('success', 'The post was delete');
        return redirect('posts');

        // if (auth()->user()->is($post->author)) {
        //     $post->tags()->detach();
        //     $post->delete();
        //     session()->flash('success', 'The post was delete');
        //     return redirect('posts');
        // } else {
        //     session()->flash('error', 'Sorry this is not your post');
        //     return redirect('posts');
        // }

    }
}
