@extends('layouts/contentNavbarLayout')

@section('title', 'Create Post')

@section('content')
    <div class="row g-6">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Create Post</h5>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="postTitle" class="form-label">Post Title</label>
                            <input type="text" id="postTitle" name="title" class="form-control"
                                placeholder="Enter post title" required />
                            @error('title')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="categorySelect" class="form-label">Select Category</label>
                            <select name="category_id" id="category" class="form-control" required>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="postImage" class="form-label">Upload image</label>
                            <input type="file" id="postImage" name="image" class="form-control" required />
                            @error('image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div>
                            <label for="postBody" class="form-label">Post Body</label>
                            <textarea id="postBody" name="content" class="form-control" rows="10"></textarea>
                            @error('content')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="my-3">
                            <button type="submit" class="btn btn-primary">Create Post</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page-script')
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
