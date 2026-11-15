@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Chapters: {{ $subject->title }}</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_subjects') }}">Practice Subjects</a></div>
            <div class="breadcrumb-item">Chapters</div>
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

        <!-- Subject Overview Card -->
        <div class="card card-hero card-primary mb-4" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header" style="background: linear-gradient(135deg, #03c6bb 0%, #4f46e5 100%); padding: 22px 28px;">
                <div class="card-icon" style="color: rgba(255,255,255,0.2);"><i class="fas fa-book"></i></div>
                <h4 style="font-size: 22px; font-weight: 700; color: #fff;">{{ $subject->title }}</h4>
                <div class="card-description" style="color: rgba(255,255,255,0.9);">
                    Category: <strong>{{ $subject->category ? ($subject->category->cat_name ?? $subject->category->name) : 'General' }}</strong> |
                    Total Chapters: <strong>{{ $chapters->count() }}</strong>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Chapters in this Subject</h4>
                        <div>
                            <a href="{{ route('cms.practice_subjects') }}" class="btn btn-outline-secondary mr-2">
                                <i class="fas fa-arrow-left"></i> Back to Subjects
                            </a>
                            <a href="{{ route('cms.practice_chapter.create', $subject->id) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add New Chapter
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="example1" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Chapter Title</th>
                                        <th>Levels</th>
                                        <th>Questions</th>
                                        <th>Exam Duration</th>
                                        <th>Pass Mark</th>
                                        <th>Streak Target</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($chapters as $index => $chapter)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $chapter->title }}</strong>
                                            @if($chapter->description)
                                                <p class="text-muted mb-0 small">{{ Str::limit($chapter->description, 50) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('cms.practice_chapter.levels', $chapter->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-layer-group"></i> {{ $chapter->levels_count }} Levels
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $chapter->questions_count }} Questions</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light text-dark font-weight-bold">
                                                <i class="fas fa-clock text-warning"></i> {{ $chapter->exam_duration_minutes }} Min
                                            </span>
                                        </td>
                                        <td>
                                            @if($chapter->exam_pass_mark_percent > 0)
                                                <span class="badge badge-info">{{ $chapter->exam_pass_mark_percent }}%</span>
                                            @else
                                                <span class="badge badge-light text-muted">None</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-warning text-dark font-weight-bold">
                                                {{ $chapter->target_repetitions }}x Consecutive
                                            </span>
                                        </td>
                                        <td>{{ $chapter->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('cms.practice_chapter.toggle', ['subjectId' => $subject->id, 'chapterId' => $chapter->id]) }}" class="badge {{ $chapter->is_active ? 'badge-success' : 'badge-danger' }}" style="cursor: pointer;" title="Toggle status">
                                                {{ $chapter->is_active ? 'Active' : 'Inactive' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('cms.practice_chapter.levels', $chapter->id) }}" class="btn btn-sm btn-info mr-1" title="Manage Levels">
                                                    <i class="fas fa-layer-group"></i> Levels
                                                </a>
                                                <a href="{{ route('cms.practice_chapter.edit', ['subjectId' => $subject->id, 'chapterId' => $chapter->id]) }}" class="btn btn-sm btn-warning mr-1" title="Edit Chapter">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cms.practice_chapter.delete', ['subjectId' => $subject->id, 'chapterId' => $chapter->id]) }}" method="POST" onsubmit="return confirm('Delete this Chapter and all its levels?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Chapter">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4 text-muted">
                                            No chapters created in this subject yet. <a href="{{ route('cms.practice_chapter.create', $subject->id) }}">Create the first Chapter!</a>
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
