@extends('user.pages.test_master')

@push('test_style')
<style>
    .subject-hero {
        background: radial-gradient(circle at 10% 20%, rgba(3, 198, 187, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.18) 0%, transparent 45%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 55px 0 65px;
        color: #ffffff;
    }
    .chapter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
    }
    .chapter-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px -5px rgba(0, 0, 0, 0.08);
        border-color: #03c6bb;
    }

    /* Dark Mode */
    html.dark-theme .subject-hero,
    [data-theme="dark"] .subject-hero,
    body.dark .subject-hero,
    body.dark-theme .subject-hero {
        background: radial-gradient(circle at 10% 20%, rgba(3, 198, 187, 0.08) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.1) 0%, transparent 45%),
                    linear-gradient(135deg, #070b14 0%, #0f172a 100%) !important;
    }
    html.dark-theme .chapter-card,
    [data-theme="dark"] .chapter-card,
    body.dark .chapter-card,
    body.dark-theme .chapter-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
    }
    html.dark-theme .text-dark-title,
    [data-theme="dark"] .text-dark-title,
    body.dark .text-dark-title,
    body.dark-theme .text-dark-title {
        color: #f8fafc !important;
    }
    html.dark-theme .text-dark-muted,
    [data-theme="dark"] .text-dark-muted,
    body.dark .text-dark-muted,
    body.dark-theme .text-dark-muted {
        color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')
<div class="subject-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">Home</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li><a href="{{ route('practice_quiz.index') }}" style="color: #94a3b8; text-decoration: none;">Practice Quizzes</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #03c6bb; font-weight: 600;">{{ $subject->title }}</li>
            </ul>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <span class="badge rounded-pill mb-2" style="background: rgba(3, 198, 187, 0.15); border: 1px solid rgba(3, 198, 187, 0.3); color: #03c6bb; padding: 6px 14px; font-weight: 600;">
                    {{ $subject->category ? ($subject->category->cat_name ?? $subject->category->name) : 'Subject Roadmap' }}
                </span>
                <h1 style="font-size: clamp(28px, 4vw, 42px); font-weight: 800; color: #ffffff;" class="mb-3">
                    {{ $subject->title }}
                </h1>
                <p style="color: #94a3b8; font-size: 16px; line-height: 1.6; max-width: 680px;" class="mb-4">
                    {{ $subject->description ?: 'অধ্যায়ভিত্তিক লেভেল গেম, সমন্বিত অধ্যায় পরীক্ষা এবং ভুলের ওপর বিশেষ গেমের মাধ্যমে ১০০% আয়ত্ত করুন।' }}
                </p>

                <div class="d-flex flex-wrap gap-4 pt-2">
                    <div>
                        <div class="text-muted small">Total Chapters</div>
                        <div class="h4 font-weight-bold text-white mb-0">{{ $subject->activeChapters->count() }}</div>
                    </div>
                    <div style="border-left: 1px solid rgba(255,255,255,0.1); padding-left: 20px;">
                        <div class="text-muted small">Mastery Method</div>
                        <div class="h4 font-weight-bold text-white mb-0">Levels &rarr; Exam &rarr; Mistake Game</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12 mt-4 mt-lg-0 text-lg-end">
                <div class="p-4 rounded-4 text-start shadow-lg d-inline-block" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); min-width: 260px;">
                    <div class="small text-muted mb-1">Subject Progress</div>
                    @php
                        $compCount = $subject->activeChapters->filter(function($c) { return optional($c->user_progress)->isCompleted(); })->count();
                        $totCount = $subject->activeChapters->count();
                        $pct = $totCount > 0 ? round(($compCount / $totCount) * 100) : 0;
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="h4 font-weight-bold text-white mb-0">{{ $compCount }}/{{ $totCount }} Chapters</span>
                        <span class="badge" style="background: #03c6bb; color: #fff;">{{ $pct }}%</span>
                    </div>
                    <div class="practice-progress-bar">
                        <div class="practice-progress-fill" style="width: {{ $pct }}%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="rbt-section-gap bg-color-white">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="text-dark-title mb-1" style="font-weight: 800; font-size: 24px;">
                    অধ্যায়ভিত্তিক রোডম্যাপ (All Chapters)
                </h3>
                <p class="text-dark-muted mb-0 small">প্রতিটি অধ্যায়ের লেভেল শেষ করুন &rarr; সমন্বিত পরীক্ষা দিন &rarr; ভুলের ওপর গেম খেলে অধ্যায় সম্পন্ন করুন।</p>
            </div>
        </div>

        <div class="row g-4">
            @forelse($subject->activeChapters as $index => $chapter)
                @php
                    $prog = $chapter->user_progress;
                    $isCompleted = $prog && $prog->isCompleted();
                    $isMistakeGame = $prog && $prog->current_stage === 'mistake_game';
                    $isExamReady = $prog && $prog->current_stage === 'exam';
                @endphp
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="chapter-card">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge rounded-pill" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700; font-size: 12px; padding: 5px 12px;">
                                Chapter {{ $chapter->sort_order ?: ($index + 1) }}
                            </span>
                            @if($isCompleted)
                                <span class="badge bg-success text-white" style="font-size: 11px;">
                                    <i class="feather-check-circle"></i> Mastered
                                </span>
                            @elseif($isMistakeGame)
                                <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 11px;">
                                    <i class="feather-alert-triangle"></i> Mistake Game
                                </span>
                            @elseif($isExamReady)
                                <span class="badge bg-info text-white" style="font-size: 11px;">
                                    <i class="feather-award"></i> Exam Ready
                                </span>
                            @else
                                <span class="badge bg-light text-muted" style="font-size: 11px;">
                                    {{ $chapter->completed_levels_count ?? 0 }}/{{ $chapter->activeLevels->count() }} Levels
                                </span>
                            @endif
                        </div>

                        <h4 class="text-dark-title mb-2" style="font-weight: 700; font-size: 18px; line-height: 1.4;">
                            <a href="{{ route('practice_quiz.chapter', $chapter->slug) }}" class="text-decoration-none text-dark-title">
                                {{ $chapter->title }}
                            </a>
                        </h4>

                        @if($chapter->description)
                            <p class="text-dark-muted small mb-4 flex-grow-1" style="line-height: 1.5;">
                                {{ Str::limit($chapter->description, 90) }}
                            </p>
                        @else
                            <div class="flex-grow-1 mb-4"></div>
                        @endif

                        <div class="d-flex align-items-center justify-content-between pt-3 border-top mb-3" style="border-color: #f1f5f9; font-size: 13px;">
                            <span class="text-dark-muted d-flex align-items-center gap-1">
                                <i class="feather-layer-group text-primary"></i> <strong>{{ $chapter->activeLevels->count() }}</strong> Levels
                            </span>
                            <span class="text-dark-muted d-flex align-items-center gap-1">
                                <i class="feather-clock text-warning"></i> <strong>{{ $chapter->exam_duration_minutes }}m</strong> Exam
                            </span>
                            <span class="text-dark-muted d-flex align-items-center gap-1">
                                <i class="feather-help-circle text-info"></i> <strong>{{ $chapter->questions_count }}</strong> Qs
                            </span>
                        </div>

                        <div class="mt-auto">
                            @if($isCompleted)
                                <a href="{{ route('practice_quiz.chapter', $chapter->slug) }}" class="btn btn-outline-success rounded-pill w-100 py-2 font-weight-bold d-flex align-items-center justify-content-center gap-2">
                                    <i class="feather-check-circle"></i> Chapter Mastered (Roadmap)
                                </a>
                            @elseif($isMistakeGame)
                                <a href="{{ route('practice_quiz.mistake_game.start', $chapter->id) }}" class="btn btn-warning text-dark rounded-pill w-100 py-2 font-weight-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="feather-alert-triangle"></i> Play Mistake Game
                                </a>
                            @elseif($isExamReady)
                                <a href="{{ route('practice_quiz.exam.start', $chapter->id) }}" class="btn btn-primary rounded-pill w-100 py-2 font-weight-bold d-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="feather-award"></i> Take Chapter Exam
                                </a>
                            @else
                                <a href="{{ route('practice_quiz.chapter', $chapter->slug) }}" class="btn btn-practice-start rounded-pill w-100 py-2 font-weight-bold d-flex align-items-center justify-content-center gap-2">
                                    <span>Enter Chapter Roadmap</span>
                                    <i class="feather-arrow-right"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No chapters available in this subject yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
