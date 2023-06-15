<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dataPost = Post::latest()->paginate(15);
        return response()->json($dataPost);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateData = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'image' => 'required',
            'location' => 'required',
            'jam_buka' => 'required',
            'rating' => 'required',
            'description' => 'required'
        ]);

        try {
            if ($request->file('image')) {
                $validateData['image'] = $request->file('image')->store('post-image');
            }

            $post = Post::create($validateData);

            if ($post != null) {
                return response()->json([
                    'success' => true,
                    'message' => 'Post berhasil di buat',
                    'data' => $post
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Post gagal di buat',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Err',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $post = Post::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'category' => 'required',
            'location' => 'required',
            'jam_buka' => 'required',
            'rating' => 'required',
            'description' => 'required',
        ]);

        try {
            $post = Post::findOrFail($id);

            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($post->image) {
                    Storage::delete($post->image);
                }
                // Simpan gambar baru
                $validatedData['image'] = $request->file('image')->store('post-image');
            }

            $post->update($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil di edit',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'errors' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $post = Post::findOrFail($id);

            // Hapus gambar terkait jika ada
            if ($post->image) {
                Storage::delete($post->image);
            }

            $post->delete();

            return response()->json([
                'success' => true,
                'message' => 'Post berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'errors' => $e->getMessage()
            ]);
        }
    }
}
