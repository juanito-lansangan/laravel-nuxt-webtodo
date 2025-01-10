<?php

namespace App\Http\Controllers;

use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $search = $request->search ?? '';

        $tags = Tag::where('name', 'like', "%{$search}%")->get();

        return TagResource::collection($tags);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): TagResource
    {
        $fields = $request->validate([
            'name' => ['required', 'min:3', 'unique:tags']
        ]);

        $tag = Tag::create($fields);

        return TagResource::make($tag);
    }
}
