@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('content')
    <div class="row g-6">
        <div class="col-md-12">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <h5 class="card-header">Edit post</h5>
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="form-label">Post title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" />
                        </div>
                        <div class="mb-4">
                            <label for="exampleFormControlSelect1" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="form-select" >
                              @foreach($categories as $category)
                                  <option value="{{ $category->id }}" {{ $category->id == old('category_id', $post->category_id) ? 'selected' : '' }}>
                                      {{ $category->name }}
                                  </option>
                              @endforeach
                          </select>
                        </div>
                        <div class="mb-4">
                            <label for="postImage" class="form-label">Upload image</label>
                            <input type="file" name="image" id="image" class="form-control" >
                            @if($post->image)
                                <p>Change Image:</p>
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" class="img-thumbnail" width="150">
                            @endif
                        </div>
                        <div>
                            <label for="exampleFormControlTextarea1" class="form-label">Post Body</label>
                            <textarea class="form-control" name="content" id="postBody" rows="3">{{ old('content', $post->content) }}</textarea>
                        </div>
                        <div class="my-3">
                          <button type="submit" class="btn btn-primary mt-3">Edit Post</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')
    <script src="https://cdn.tiny.cloud/1/v0t2gv0edmoyfbxormelf38fonv4x8jfh53cnbau5kbg2uph/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#postBody', // Matches the textarea ID
                apiKey: 'v0t2gv0edmoyfbxormelf38fonv4x8jfh53cnbau5kbg2uph',
                plugins: 'lists link image preview',
                toolbar: 'undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                menubar: false, // Disable the menubar for a simpler UI
                height: 400, // Set a comfortable editor height
                readonly: false,
            });
        });
    </script>
@endsection
