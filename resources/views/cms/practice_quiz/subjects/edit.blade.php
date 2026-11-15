@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Edit Practice Subject</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item">Edit</div>
        </div>
    </div>
    <div class="section-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Edit Subject: {{ $subject->title }}</h4>
                        <a href="{{ route('cms.practice_chapters', $subject->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-folder"></i> Manage Chapters ({{ $subject->chapters()->count() }})
                        </a>
                    </div>
                    <form action="{{ route('cms.practice_subject.update', $subject->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Subject Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $subject->title) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control select2" id="category_id" name="category_id">
                                    <option value="">-- Select Category (Optional) --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $subject->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->cat_name ?? $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sort_order">Display Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $subject->sort_order) }}" min="0">
                            </div>

                            <div class="form-group">
                                <label for="image">Subject Cover Image</label>
                                @if($subject->image)
                                    <div class="mb-2">
                                        <img src="{{ asset($subject->image) }}" alt="Cover" style="height: 60px; border-radius: 8px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label for="description">Subject Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $subject->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="control-label">Status</div>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="is_active" value="1" class="custom-switch-input" {{ old('is_active', $subject->is_active) ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Active / Published</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer bg-whitesmoke text-right">
                            <a href="{{ route('cms.practice_subjects') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Update Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
