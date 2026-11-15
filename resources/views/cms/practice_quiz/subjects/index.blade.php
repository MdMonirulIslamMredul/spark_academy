@extends('cms.cms_master')
@section('admin_content')
<section class="section">
    <div class="section-header">
        <h1>Practice Subjects</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('/cms') }}">Dashboard</a></div>
            <div class="breadcrumb-item"><a href="{{ route('cms.practice_quizzes') }}">Practice Quizzes</a></div>
            <div class="breadcrumb-item">Subjects</div>
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>All Practice Subjects</h4>
                        <a href="{{ route('cms.practice_subject.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New Subject
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="example1" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject Title</th>
                                        <th>Category</th>
                                        <th>Chapters</th>
                                        <th>Total Levels</th>
                                        <th>Total Questions</th>
                                        <th>Order</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subjects as $index => $subject)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $subject->title }}</strong>
                                            @if($subject->description)
                                                <p class="text-muted mb-0 small">{{ Str::limit($subject->description, 60) }}</p>
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->category)
                                                <span class="badge badge-info">{{ $subject->category->cat_name ?? $subject->category->name }}</span>
                                            @else
                                                <span class="badge badge-light text-muted">General</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('cms.practice_chapters', $subject->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-folder"></i> {{ $subject->chapters_count }} Chapters
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary">{{ $subject->levels_count ?? 0 }} Levels</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-primary">{{ $subject->questions_count ?? 0 }} Questions</span>
                                        </td>
                                        <td>{{ $subject->sort_order }}</td>
                                        <td>
                                            <a href="{{ route('cms.practice_subject.toggle', $subject->id) }}" class="badge {{ $subject->is_active ? 'badge-success' : 'badge-danger' }}" style="cursor: pointer;" title="Toggle status">
                                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('cms.practice_chapters', $subject->id) }}" class="btn btn-sm btn-info mr-1" title="Manage Chapters">
                                                    <i class="fas fa-list-ul"></i> Chapters
                                                </a>
                                                <a href="{{ route('cms.practice_subject.edit', $subject->id) }}" class="btn btn-sm btn-warning mr-1" title="Edit Subject">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('cms.practice_subject.delete', $subject->id) }}" method="POST" onsubmit="return confirm('Delete this Subject and all its Chapters & Levels?');" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete Subject">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4 text-muted">
                                            No practice subjects created yet. <a href="{{ route('cms.practice_subject.create') }}">Create the first Subject!</a>
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
