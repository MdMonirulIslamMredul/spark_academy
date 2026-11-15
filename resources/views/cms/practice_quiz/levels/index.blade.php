@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Levels: {{ $chapter->title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_chapters', $chapter->subject_id) }}">Chapters</a></div>
            <div class="breadcrumb-item">Levels</div>
        </div>
    </div>
    <div class="section-body">
        @if(session('status'))
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <!-- Chapter Summary Card -->
        <div class="card card-hero card-primary mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header" style="background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); padding: 22px 28px;">
                <div class="card-icon" style="color: rgba(255,255,255,0.2);"><i class="fas fa-layer-group"></i></div>
                <h4 style="font-size: 22px; font-weight: 700; color: #fff;">{{ $chapter->title }}</h4>
                <div class="card-description" style="color: rgba(255,255,255,0.9);">
                    Subject: <strong>{{ optional($chapter->subject)->title ?? 'General' }}</strong> |
                    Exam Duration: <strong>{{ $chapter->exam_duration_minutes }} Min</strong> |
                    Total Levels: <strong>{{ $levels->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Levels in this Chapter</h4>
                        <div>
                            <a href="{{ route('cms.practice_chapters', $chapter->subject_id) }}" class="btn btn-outline-secondary mr-2">
                                <i class="fas fa-arrow-left"></i> Back to Chapters
                            </a>
                            <a href="{{ route('cms.practice_chapter.level.create', $chapter->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Level
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="example1" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>Level #</th>
                                        <th>Level Title</th>
                                        <th>Questions</th>
                                        <th>Mastery Target</th>
                                        <th>Active Students</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($levels as $index => $level)
                                    <tr>
                                        <td>
                                            <span class="badge badge-primary" style="font-size: 13px;">Level {{ $level->sort_order }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $level->title }}</strong>
                                            @if($level->description)
                                                <p class="text-muted mb-0 small">{{ Str::limit($level->description, 50) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('cms.practice_quiz.questions', $level->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-list-ol"></i> {{ $level->questions_count }} Questions
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-warning text-dark font-weight-bold">
                                                {{ $level->target_repetitions }}x Consecutive
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $level->sessions_count }}</span>
                                        </td>
                                        <td>{{ $level->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('cms.practice_quiz.toggle', $level->id) }}" class="badge {{ $level->is_active ? 'badge-success' : 'badge-danger' }}" style="cursor: pointer;" title="Toggle status">
                                                {{ $level->is_active ? 'Active' : 'Inactive' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('cms.practice_quiz.questions', $level->id) }}" class="btn btn-sm btn-info mr-1" title="Manage Questions">
                                                    <i class="fas fa-question-circle"></i> Questions
                                                </a>
                                                <a href="{{ route('cms.practice_quiz.edit', $level->id) }}" class="btn btn-sm btn-warning mr-1" title="Edit Level">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cms.practice_quiz.delete', $level->id) }}" method="POST" onsubmit="return confirm('Delete this Level and all its questions?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Level">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            No levels added to this chapter yet. <a href="{{ route('cms.practice_chapter.level.create', $chapter->id) }}">Create Level 1!</a>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
