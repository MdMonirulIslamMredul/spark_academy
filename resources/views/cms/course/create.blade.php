@extends('cms.cms_master')
@section('admin_content')

<section class="section">
    <div class="section-header">
        <h1>Add New Course</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.course.list') }}">Courses</a></div>
            <div class="breadcrumb-item">Add Course</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('cms.add_course_confirm') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h4>Course Information</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label><span class="text-danger">*</span>Course Category</label>
                                <select name="catID" class="form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('catID') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label><span class="text-danger">*</span>Course Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label><span class="text-danger">*</span>Course Price</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label><span class="text-danger">*</span>Image (800 x 630)</label>
                                <input type="file" name="image" class="form-control" required>
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label>Description</label>
                                <textarea class="form-control" id="editor1" name="description">{{ old('description') }}</textarea>
                            </div>

                            @include('cms.course.partials.curriculum_builder', ['chapters' => old('chapters', [])])
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="form-control btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@endsection
