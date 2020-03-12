<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store()
    {

        $data = request()->validate([
            'caption' => ['required', 'string'],
            'image' => ['required', 'image']
        ]);

        $imagePath = request('image')->store('/uploads', 'public');

        // $image = Image::make(public_path("storage/{$imagePath}"))->fit(1200, 1200);
        // $image = Image::make(public_path("storage/{$imagePath}"))->fit(820, 740);
        // $image = Image::make(public_path("storage/{$imagePath}"))->resize(300, 300);
        // $image = Image::make(public_path("storage/{$imagePath}"))->resize(1200, null);
        $image = Image::make(public_path("storage/{$imagePath}"))->widen(600, function ($constraint) { $constraint->upsize(); });
        $image->save();

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $imagePath
        ]);

        return redirect('/profile/' . auth()->user()->username);
        // return redirect()->route('profile.index', ['user' => auth()->user()]);

    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }
}
