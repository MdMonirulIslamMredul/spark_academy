@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Edit Chapter: {{ $chapter->title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_chapters', $subject->id) }}">Chapters</a></div>
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
                        <h4>Edit Chapter</h4>
                        <a href="{{ route('cms.practice_chapter.levels', $chapter->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-layer-group"></i> Manage Levels ({{ $chapter->levels()->count() }})
                        </a>
                    </div>
                    <form action="{{ route('cms.practice_chapter.update', ['subjectId' => $subject->id, 'chapterId' => $chapter->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Chapter Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $chapter->title) }}" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="sort_order">Chapter Sequence Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $chapter->sort_order) }}" min="1" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="target_repetitions">Streak Mastery Target <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="target_repetitions" name="target_repetitions" value="{{ old('target_repetitions', $chapter->target_repetitions) }}" min="1" max="20" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">x in a row</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="exam_duration_minutes">Chapter Exam Duration <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="exam_duration_minutes" name="exam_duration_minutes" value="{{ old('exam_duration_minutes', $chapter->exam_duration_minutes) }}" min="1" max="180" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Minutes</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="exam_pass_mark_percent">Optional Exam Pass Mark (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="exam_pass_mark_percent" name="exam_pass_mark_percent" value="{{ old('exam_pass_mark_percent', $chapter->exam_pass_mark_percent) }}" min="0" max="100">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Chapter Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $chapter->description) }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="control-label">Status</div>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="is_active" value="1" class="custom-switch-input" {{ old('is_active', $chapter->is_active) ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Active / Published</span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer bg-whitesmoke text-right">
                            <a href="{{ route('cms.practice_chapters', $subject->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Update Chapter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
