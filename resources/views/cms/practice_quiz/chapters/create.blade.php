@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Add Chapter: {{ $subject->title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_chapters', $subject->id) }}">Chapters</a></div>
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
                        <h4>Chapter Information</h4>
                    </div>
                    <form action="{{ route('cms.practice_chapter.store', $subject->id) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Chapter Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="e.g. Chapter 01: ভৌত জগত ও পরিমাপ" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="sort_order">Chapter Sequence Order <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $nextSortOrder ?? 1) }}" min="1" required>
                                    <small class="form-text text-muted">Order of chapter within this subject (1, 2, 3...).</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="target_repetitions">Streak Mastery Target <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="target_repetitions" name="target_repetitions" value="{{ old('target_repetitions', 3) }}" min="1" max="20" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">x in a row</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Number of consecutive correct answers required to master each question (Default: 3).</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="exam_duration_minutes">Chapter Exam Duration <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="exam_duration_minutes" name="exam_duration_minutes" value="{{ old('exam_duration_minutes', 15) }}" min="1" max="180" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Minutes</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Countdown timer for the final chapter exam.</small>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="exam_pass_mark_percent">Optional Exam Pass Mark (%)</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="exam_pass_mark_percent" name="exam_pass_mark_percent" value="{{ old('exam_pass_mark_percent', 0) }}" min="0" max="100">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">Set 0 for no pass requirement (any mistakes lead directly to the Mistake Game).</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Chapter Description / Guidelines</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief outline of concepts covered in this chapter...">{{ old('description') }}</textarea>
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
                            <a href="{{ route('cms.practice_chapters', $subject->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check"></i> Save Chapter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
