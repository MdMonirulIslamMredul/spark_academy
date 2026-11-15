@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Add New Practice Subject</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item">Create</div>
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
                    <div class="card-header">
                        <h4>Subject Information</h4>
                    </div>
                    <form action="{{ route('cms.practice_subject.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Subject Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Physics 1st Paper / পদার্থবিজ্ঞান ১ম পত্র" required>
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <select class="form-control select2" id="category_id" name="category_id">
                                    <option value="">-- Select Category (Optional) --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->cat_name ?? $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="sort_order">Display Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', 1) }}" min="0">
                            </div>

                            <div class="form-group">
                                <label for="image">Subject Cover Image (Optional)</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <div class="form-group">
                                <label for="description">Subject Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="Brief description of chapters and topics covered...">{{ old('description') }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="control-label">Status</div>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="is_active" value="1" class="custom-switch-input" checked>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Active / Published</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer bg-whitesmoke text-right">
                            <a href="{{ route('cms.practice_subjects') }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Save Subject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
