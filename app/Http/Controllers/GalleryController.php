<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\User;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $field = $request->input('field');
        $query = $request->input('query');
        $take = $request->input('take', 10);
        $skip = $request->input('skip', 0);

        if($query) {
            $galleries = $this->searchQuery($field, $query, $take, $skip);
        } else {
            $galleries = Gallery::with('images')->skip($skip)
            ->take($take)
            ->get();
        }

        $responseData = $galleries->map(function ($gallery) {
            return [
                'id' => $gallery->id,
                'name' => $gallery->name,
                'description' => $gallery->description,
                'author' => $gallery->author,
                'images' => $gallery->images->pluck('image_url')->all()
            ];
        });

        // return response()->json(['galleries' => $responseData], 200);
        return $responseData;
    }


    public function searchQuery ($field, $query, $take, $skip) {
        return Gallery::with('images')->where($field, 'LIKE', '%' . $query . '%')
            ->skip($skip)
            ->take($take)
            ->get();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGalleryRequest $request)
    {
            $author = User::findOrFail($request['user_id']);
    
            $gallery = Gallery::create([
                'name' => $request['name'],
                'description' => $request['description'],
                'user_id' => $request['user_id'],
                'author' => $author['first_name']." ".$author['last_name']
            ]);
    
            foreach ($request['images'] as $imageUrl) {
                $gallery->images()->create([
                    'image_url' => $imageUrl,
                ]);
            }
    
            return response()->json(['message' => 'Gallery created successfully'], 201);

        }

    /**
     * Display the specified resource.
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGalleryRequest $request, int $id)
    {
        // $data = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $gallery->update([
            'name' => $request['name'],
            'description' => $request['description'],
        ]);

        foreach ($request['images'] as $imageUrl) {
            $gallery->images()->update([
                'image_url' => $imageUrl,
            ]);
        }

        return response()->json(['message' => 'Gallery updated successfully'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gallery $gallery)
    {
        //
    }
}
