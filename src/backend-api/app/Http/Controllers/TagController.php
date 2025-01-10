<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->search ?? '';

        $tags = Tag::where('name', 'like', "%{$search}%")->get();

        return response()->json($tags, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'name' => ['required', 'min:3', 'unique:tags']
        ]);

        $tag = Tag::create($fields);

        return response()->json($tag, 200);
    }
}
