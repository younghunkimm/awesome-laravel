<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;

class BlogController extends Controller
{
    /**
     * 
     */
    public function __construct()
    {
        $this->authorizeResource(Blog::class, 'blog');
    }

    /**
     * 블로그 목록
     */
    public function index()
    {
        return view('blogs.index', [
            'blogs' => Blog::with('user')->paginate(5),
        ]);
    }

    /**
     * 블로그 생성 폼
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * 블로그 생성
     */
    public function store(StoreBlogRequest $request)
    {
        $user = $request->user();

        $user->blogs()->create($request->validated());

        return to_route('dashboard.blogs');
    }

    /**
     * 블로그
     */
    public function show(Blog $blog)
    {
        return view('blogs.show', [
            'blog' => $blog
        ]);
    }

    /**
     * 블로그 수정 폼
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', [
            'blog' => $blog
        ]);
    }

    /**
     * 블로그 수정
     */
    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $blog->update($request->validated());

        return to_route('dashboard.blogs');
    }

    /**
     * 블로그 삭제
     */
    public function destroy(Blog $blog)
    {
        $blog->delete();

        return to_route('dashboard.blogs');
    }
}
