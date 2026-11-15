@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Add Level: {{ $chapter->title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_chapters', $chapter->subject_id) }}">Chapters</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_chapter.levels', $chapter->id) }}">Levels</a></div>
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
                        <h4>Level Information</h4>
                    </div>
                    <form action="{{ route('cms.practice_chapter.level.store', $chapter->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Level Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', 'Level ' . ($nextSortOrder ?? 1)) }}" placeholder="e.g. Level 01: বেসিক ধারণা বা প্রাথমিক সূত্র" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="sort_order">Level Sequence Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextSortOrder ?? 1) }}" min="1" required>
                                    <small class="form-text text-muted">1 for Level 1, 2 for Level 2, 3 for Level 3...</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="target_repetitions">Consecutive Mastery Repetitions <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="target_repetitions" name="target_repetitions" value="{{ old('target_repetitions', 3) }}" min="1" max="20" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Times</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Consecutive correct answers required to master each question (Default: 3).</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Level Topics / Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Topics covered in this level...">{{ old('description') }}</textarea>
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
                            <a href="{{ route('cms.practice_chapter.levels', $chapter->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right"></i> Save and Add Questions
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
