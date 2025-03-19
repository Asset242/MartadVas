<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function index()
  {
    $posts = Post::with('category')->get();
    $categories = Category::all();
    return view('tables.tables-basic', compact('posts', 'categories'));
  }

  public function store(Request $request)
  { 
    // Validate the incoming request data
    $request->validate([
      'title' => 'required|string|max:255',
      'content' => 'required|string',
      'category_id' => 'required|exists:categories,id', // Assuming you have categories
      'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
    ]);

    // Handle image upload if present
    $imagePath = null;
    if ($request->hasFile('image')) {
      // Store the image in the 'public/posts' directory
      $imagePath = $request->file('image')->store('public/posts');
    }

    // Create a new post and save it to the database
    Post::create([
      'title' => $request->title,
      'content' => $request->content,
      'category_id' => $request->category_id,
      'image' => $imagePath, // Save the image path in the database
    ]);

    // Redirect back with a success message
    return redirect()->route('tables-basic')->with('success', 'Post created successfully!');
  }
  public function update(Request $request, Post $post)
  {
      // Validate the incoming request data
      $request->validate([
          'title' => 'string|max:255',
          'content' => 'string',
          'category_id' => 'exists:categories,id',
          'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
      ]);

      // Handle image upload if present
      if ($request->hasFile('image')) {
          // Delete the old image if it exists
          if ($post->image) {
              \Storage::delete($post->image);
          }

          // Store the new image
          $imagePath = $request->file('image')->store('public/posts');
      } else {
          $imagePath = $post->image; // Keep the existing image if no new image is uploaded
      }

      // Update the post with the new data
      $post->update([
          'title' => $request->title,
          'content' => $request->content,
          'category_id' => $request->category_id,
          'image' => $imagePath,
      ]);

      // Redirect back with a success message
      return redirect()->route('tables-basic', $post->id)->with('success', 'Post updated successfully!');
  }
  public function destroy(Post $post)
  {
      // Delete the post
      $post->delete();

      // Redirect back with a success message
      return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
  }
}
