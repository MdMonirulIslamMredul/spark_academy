@extends('user.pages.test_master')

@push('test_style')
<style>
    .chapter-hero {
        background: radial-gradient(circle at 10% 20%, rgba(3, 198, 187, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.18) 0%, transparent 45%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 50px 0 60px;
        color: #ffffff;
    }
    .roadmap-timeline {
        position: relative;
        padding-left: 30px;
    }
    .roadmap-timeline::before {
        content: '';
        position: absolute;
        top: 20px;
        bottom: 20px;
        left: 8px;
        width: 4px;
        background: #e2e8f0;
        border-radius: 4px;
    }
    .roadmap-step {
        position: relative;
        margin-bottom: 30px;
    }
    .roadmap-step-dot {
        position: absolute;
        left: -30px;
        top: 20px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #94a3b8;
        border: 4px solid #ffffff;
        box-shadow: 0 0 0 2px #cbd5e1;
        transition: all 0.3s ease;
    }
    .roadmap-step.active .roadmap-step-dot {
        background: #03c6bb;
        box-shadow: 0 0 0 3px rgba(3, 198, 187, 0.4);
    }
    .roadmap-step.completed .roadmap-step-dot {
        background: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.3);
    }
    .roadmap-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 24px 28px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
    }
    .roadmap-card.is-active {
        border-color: #03c6bb;
        box-shadow: 0 10px 25px -5px rgba(3, 198, 187, 0.15);
    }
    .roadmap-card.is-locked {
        background: #f8fafc;
        border-color: #e2e8f0;
        opacity: 0.75;
    }
    .roadmap-card.is-completed {
        border-color: #10b981;
        background: linear-gradient(180deg, #f0fdf4 0%, #ffffff 40%);
    }

    /* Dark Mode */
    html.dark-theme .chapter-hero,
    [data-theme="dark"] .chapter-hero,
    body.dark .chapter-hero,
    body.dark-theme .chapter-hero {
        background: radial-gradient(circle at 10% 20%, rgba(3, 198, 187, 0.08) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.1) 0%, transparent 45%),
                    linear-gradient(135deg, #070b14 0%, #0f172a 100%) !important;
    }
    html.dark-theme .roadmap-timeline::before,
    [data-theme="dark"] .roadmap-timeline::before,
    body.dark .roadmap-timeline::before,
    body.dark-theme .roadmap-timeline::before {
        background: #334155 !important;
    }
    html.dark-theme .roadmap-card,
    [data-theme="dark"] .roadmap-card,
    body.dark .roadmap-card,
    body.dark-theme .roadmap-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
    }
    html.dark-theme .roadmap-card.is-locked,
    [data-theme="dark"] .roadmap-card.is-locked,
    body.dark .roadmap-card.is-locked,
    body.dark-theme .roadmap-card.is-locked {
        background: #0f172a !important;
        border-color: #1e293b !important;
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
<div class="chapter-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">Home</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li><a href="{{ route('practice_quiz.index') }}" style="color: #94a3b8; text-decoration: none;">Practice Quizzes</a></li>
                @if($chapter->subject)
                    <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                    <li><a href="{{ route('practice_quiz.subject', $chapter->subject->slug) }}" style="color: #94a3b8; text-decoration: none;">{{ $chapter->subject->title }}</a></li>
                @endif
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #03c6bb; font-weight: 600;">{{ $chapter->title }}</li>
            </ul>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <span class="badge rounded-pill mb-2" style="background: rgba(3, 198, 187, 0.15); border: 1px solid rgba(3, 198, 187, 0.3); color: #03c6bb; padding: 6px 14px; font-weight: 600;">
                    {{ optional($chapter->subject)->title ?? 'Chapter Roadmap' }}
                </span>
                <h1 style="font-size: clamp(26px, 3.5vw, 38px); font-weight: 800; color: #ffffff;" class="mb-3">
                    {{ $chapter->title }}
                </h1>
                <p style="color: #94a3b8; font-size: 15px; line-height: 1.6; max-width: 680px;" class="mb-0">
                    {{ $chapter->description ?: 'ধাপভিত্তিক প্রস্তুতি: লেভেলগুলো ৩ বার টানা সঠিক উত্তর দিয়ে শেষ করুন &rarr; সমন্বিত পরীক্ষা দিন &rarr; ভুলের ওপর গেম খেলে পুরো অধ্যায় সম্পন্ন করুন।' }}
                </p>
            </div>
            <div class="col-lg-4 col-12 mt-4 mt-lg-0 text-lg-end">
                @php
                    $isChapterDone = $progress && $progress->isCompleted();
                @endphp
                @if($isChapterDone)
                    <div class="p-3 rounded-4 d-inline-block text-start bg-success text-white shadow-lg" style="min-width: 240px;">
                        <div class="d-flex align-items-center gap-3">
                            <div style="font-size: 36px;">🏆</div>
                            <div>
                                <div class="small text-white-50">Status</div>
                                <div class="font-weight-bold" style="font-size: 16px;">অধ্যায় ১০০% সম্পন্ন!</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-3 rounded-4 d-inline-block text-start shadow-lg" style="background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.15); backdrop-filter: blur(12px); min-width: 240px;">
                        <div class="small text-muted mb-1">Chapter Target</div>
                        <div class="h5 font-weight-bold text-white mb-2">{{ $chapter->target_repetitions }}x Consecutive Correct</div>
                        <div class="small" style="color: #03c6bb;"><i class="feather-award"></i> Exam: {{ $chapter->exam_duration_minutes }} Min</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="rbt-section-gap bg-color-white">
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-4" role="alert">
                <i class="feather-alert-triangle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-4" role="alert">
                <i class="feather-check-circle me-2"></i> {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">
                <div class="roadmap-timeline">

                    <!-- ==========================================
                         PART 1: LEVELS (1..N)
                         Client: একটা সাবজেক্ট এর প্রত্যেক অধ্যায়ে কয়েকটা করে লেভেল থাকবে
                         ========================================== -->
                    @foreach($chapter->activeLevels as $idx => $level)
                        @php
                            $stepClass = $level->is_completed ? 'completed' : ($level->is_unlocked ? 'active' : '');
                            $cardClass = $level->is_completed ? 'is-completed' : ($level->is_unlocked ? 'is-active' : 'is-locked');
                        @endphp
                        <div class="roadmap-step {{ $stepClass }}">
                            <div class="roadmap-step-dot"></div>
                            <div class="roadmap-card {{ $cardClass }}">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge rounded-pill" style="background: {{ $level->is_completed ? '#10b981' : '#4f46e5' }}; color: #ffffff; font-weight: 700; font-size: 13px; padding: 5px 14px;">
                                            ধাপ {{ $idx + 1 }}: Level {{ $level->sort_order }}
                                        </span>
                                        @if($level->is_completed)
                                            <span class="badge bg-success text-white" style="font-size: 11px;"><i class="feather-check-circle"></i> সম্পন্ন</span>
                                        @elseif($level->is_unlocked)
                                            <span class="badge bg-info text-white" style="font-size: 11px;">চলমান ধাপ</span>
                                        @else
                                            <span class="badge bg-secondary text-white" style="font-size: 11px;"><i class="feather-lock"></i> লক করা</span>
                                        @endif
                                    </div>
                                    <div class="text-muted small">
                                        <span class="me-3"><i class="feather-help-circle text-primary"></i> <strong>{{ $level->questions_count }}</strong> Questions</span>
                                        <span><i class="feather-repeat text-warning"></i> <strong>{{ $level->target_repetitions }}x</strong> টানা সঠিক</span>
                                    </div>
                                </div>

                                <h4 class="text-dark-title mb-2" style="font-weight: 700; font-size: 19px;">
                                    {{ $level->title }}
                                </h4>

                                @if($level->description)
                                    <p class="text-dark-muted small mb-3">{{ $level->description }}</p>
                                @endif

                                @if($level->active_session)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1 small font-weight-bold">
                                            <span style="color: #03c6bb;"><i class="feather-clock"></i> গেম চলমান ({{ $level->active_session->mastered_questions }}/{{ $level->active_session->total_questions }} মাস্টারড)</span>
                                            <span>{{ $level->active_session->progress_percent }}%</span>
                                        </div>
                                        <div class="practice-progress-bar">
                                            <div class="practice-progress-fill" style="width: {{ $level->active_session->progress_percent }}%;"></div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-3 d-flex flex-wrap gap-2 justify-content-end align-items-center">
                                    @if($level->is_completed)
                                        <a href="{{ route('practice_quiz.start', ['id' => $level->id, 'restart' => 1]) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 font-weight-bold">
                                            <i class="feather-rotate-ccw"></i> আবার খেলুন
                                        </a>
                                        <a href="{{ route('practice_quiz.show', $level->slug) }}" class="btn btn-sm btn-light rounded-pill px-3 py-2">
                                            বিস্তারিত ও রিভিউ
                                        </a>
                                    @elseif($level->is_unlocked)
                                        <a href="{{ route('practice_quiz.start', $level->id) }}" class="btn btn-primary rounded-pill px-4 py-2 font-weight-bold shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(90deg, #03c6bb, #4f46e5);">
                                            <i class="feather-play-circle"></i>
                                            <span>{{ $level->active_session ? 'Resume Level' : 'Start Level ' . $level->sort_order }}</span>
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-secondary rounded-pill px-3 py-2 disabled" disabled>
                                            <i class="feather-lock me-1"></i> আগের লেভেল শেষ করুন
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- ==========================================
                         PART 2: TIMED CHAPTER EXAM
                         Client: ধরি তিনটা লেভেল, তিন লেভেল শেষ করার পর একটা পরীক্ষা থাকবে, তিন লেভেলের প্রশ্নগুলো নিয়ে
                         ========================================== -->
                    @php
                        $examCompleted = $latestExamAttempt && $latestExamAttempt->status === 'submitted';
                        $examStepClass = $examCompleted ? 'completed' : ($examUnlocked ? 'active' : '');
                        $examCardClass = $examCompleted ? 'is-completed' : ($examUnlocked ? 'is-active' : 'is-locked');
                    @endphp
                    <div class="roadmap-step {{ $examStepClass }}">
                        <div class="roadmap-step-dot" style="border-radius: 4px;"></div>
                        <div class="roadmap-card {{ $examCardClass }}" style="border-left: 5px solid #4f46e5 !important;">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill" style="background: #4f46e5; color: #ffffff; font-weight: 700; font-size: 13px; padding: 5px 14px;">
                                        অধ্যায় পরীক্ষা (Chapter Exam)
                                    </span>
                                    @if($examCompleted)
                                        <span class="badge bg-success text-white" style="font-size: 11px;"><i class="feather-check"></i> সম্পন্ন</span>
                                    @elseif($examUnlocked)
                                        <span class="badge bg-primary text-white" style="font-size: 11px;">পরীক্ষার জন্য প্রস্তুত</span>
                                    @else
                                        <span class="badge bg-secondary text-white" style="font-size: 11px;"><i class="feather-lock"></i> সব লেভেল শেষ হলে আনলক হবে</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    <span class="me-3"><i class="feather-clock text-warning"></i> <strong>{{ $chapter->exam_duration_minutes }}</strong> Minutes</span>
                                    <span><i class="feather-help-circle text-primary"></i> <strong>সকল লেভেলের সমন্বিত প্রশ্ন</strong></span>
                                </div>
                            </div>

                            <h4 class="text-dark-title mb-2" style="font-weight: 700; font-size: 19px;">
                                {{ $chapter->title }} - সমন্বিত মডেল পরীক্ষা
                            </h4>
                            <p class="text-dark-muted small mb-3">
                                এই অধ্যায়ের সব লেভেলের প্রশ্ন একত্রে নিয়ে নির্ধারিত সময়ের পরীক্ষা। নির্ভুল উত্তর দিয়ে সরাসরি অধ্যায় সমাপ্ত করুন।
                            </p>

                            @if($latestExamAttempt && $latestExamAttempt->status === 'submitted')
                                <div class="p-3 rounded-4 mb-3" style="background: rgba(79, 70, 229, 0.08); border: 1px solid rgba(79, 70, 229, 0.2);">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                        <div>
                                            <div class="small text-muted">সর্বশেষ পরীক্ষার ফলাফল</div>
                                            <div class="h5 font-weight-bold mb-0 text-dark-title">
                                                স্কোর: <span class="text-primary">{{ $latestExamAttempt->score_percent }}%</span>
                                                ({{ $latestExamAttempt->correct_count }} সঠিক, {{ $latestExamAttempt->wrong_count }} ভুল, {{ $latestExamAttempt->unanswered_count }} অনুত্তরিত)
                                            </div>
                                        </div>
                                        <div>
                                            @if(($latestExamAttempt->wrong_count + $latestExamAttempt->unanswered_count) === 0)
                                                <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="feather-check-circle"></i> ১০০% নির্ভুল!</span>
                                            @else
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill font-weight-bold">
                                                    <i class="feather-alert-triangle"></i> {{ $latestExamAttempt->wrong_count + $latestExamAttempt->unanswered_count }}টি প্রশ্ন ভুল/অনুত্তরিত
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3 d-flex flex-wrap gap-2 justify-content-end align-items-center">
                                @if($examUnlocked)
                                    @if($examCompleted)
                                        <a href="{{ route('practice_quiz.exam.result', $latestExamAttempt->id) }}" class="btn btn-sm btn-outline-info rounded-pill px-3 py-2">
                                            <i class="feather-file-text"></i> পরীক্ষার ফলাফল ও রিভিউ
                                        </a>
                                        <a href="{{ route('practice_quiz.exam.start', $chapter->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 font-weight-bold" onclick="return confirm('নতুন করে এই পরীক্ষাটি আবার শুরু করবেন?');">
                                            <i class="feather-rotate-ccw"></i> পরীক্ষাটি পুনরায় দিন
                                        </a>
                                    @else
                                        <a href="{{ route('practice_quiz.exam.start', $chapter->id) }}" class="btn btn-primary rounded-pill px-4 py-2 font-weight-bold shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(90deg, #4f46e5, #06b6d4);">
                                            <i class="feather-award"></i>
                                            <span>Start Chapter Exam ({{ $chapter->exam_duration_minutes }} Min)</span>
                                        </a>
                                    @endif
                                @else
                                    <button class="btn btn-sm btn-secondary rounded-pill px-3 py-2 disabled" disabled>
                                        <i class="feather-lock me-1"></i> সকল লেভেল শেষ হলে আনলক হবে
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================
                         PART 3: MISTAKE RECOVERY GAME
                         Client: এরপর ভুল করলে শুধু ভুলের উপর গেইম , এর মাধ্যমেই অধ্যায় শেষ
                         ========================================== -->
                    @php
                        $hasMistakes = $latestExamAttempt && ($latestExamAttempt->wrong_count + $latestExamAttempt->unanswered_count) > 0;
                        $mistakeCompleted = $mistakeSession && $mistakeSession->status === 'completed';
                        $mistakeStepClass = ($isChapterDone || $mistakeCompleted) ? 'completed' : ($hasMistakes ? 'active' : '');
                        $mistakeCardClass = ($isChapterDone || $mistakeCompleted) ? 'is-completed' : ($hasMistakes ? 'is-active' : 'is-locked');
                    @endphp
                    <div class="roadmap-step {{ $mistakeStepClass }}">
                        <div class="roadmap-step-dot" style="background: #f59e0b;"></div>
                        <div class="roadmap-card {{ $mistakeCardClass }}" style="border-left: 5px solid #f59e0b !important;">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge rounded-pill" style="background: #f59e0b; color: #1e293b; font-weight: 800; font-size: 13px; padding: 5px 14px;">
                                        ভুলের ওপর স্পেশাল গেম (Mistake Recovery Game)
                                    </span>
                                    @if($isChapterDone)
                                        <span class="badge bg-success text-white" style="font-size: 11px;"><i class="feather-check-circle"></i> অধ্যায় সম্পন্ন</span>
                                    @elseif($hasMistakes)
                                        <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 11px;"><i class="feather-zap"></i> অ্যাকশন প্রয়োজন</span>
                                    @else
                                        <span class="badge bg-secondary text-white" style="font-size: 11px;"><i class="feather-lock"></i> পরীক্ষায় ভুল থাকলে প্রযোজ্য</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    <i class="feather-repeat text-warning"></i> <strong>৩ বার টানা কারেক্ট</strong> করে অধ্যায় শেষ করুন
                                </div>
                            </div>

                            <h4 class="text-dark-title mb-2" style="font-weight: 700; font-size: 19px;">
                                ভুল প্রশ্নের সমাধান ও স্থায়ী মেমোরি গেম
                            </h4>
                            <p class="text-dark-muted small mb-3">
                                পরীক্ষায় ভুল হওয়া প্রশ্নগুলো নিয়ে তাৎক্ষণিক গেম। প্রতিটি ভুল প্রশ্নের টানা ৩ বার সঠিক উত্তর দেওয়ার মাধ্যমেই অধ্যায়টি ১০০% সম্পন্ন হবে।
                            </p>

                            @if($hasMistakes && !$isChapterDone)
                                <div class="alert alert-warning py-2 px-3 small rounded-3 mb-3 d-flex align-items-center justify-content-between">
                                    <span>
                                        <i class="feather-alert-triangle me-1"></i> আপনার পরীক্ষায় <strong>{{ $latestExamAttempt->wrong_count + $latestExamAttempt->unanswered_count }}টি</strong> প্রশ্ন ভুল/অনুত্তরিত রয়েছে।
                                    </span>
                                    <span class="badge bg-dark text-white">{{ $chapter->target_repetitions }}x streak target</span>
                                </div>
                            @endif

                            <div class="mt-3 d-flex flex-wrap gap-2 justify-content-end align-items-center">
                                @if($hasMistakes && !$isChapterDone)
                                    <a href="{{ route('practice_quiz.mistake_game.start', $chapter->id) }}" class="btn btn-warning text-dark rounded-pill px-4 py-2 font-weight-bold shadow-sm d-inline-flex align-items-center gap-2">
                                        <i class="feather-zap"></i>
                                        <span>ভুলের ওপর গেম শুরু করুন (Play Mistake Game)</span>
                                    </a>
                                @elseif($isChapterDone)
                                    <span class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 disabled">
                                        <i class="feather-check-circle me-1"></i> অধ্যায় সফলভাবে সম্পন্ন হয়েছে!
                                    </span>
                                @else
                                    <button class="btn btn-sm btn-secondary rounded-pill px-3 py-2 disabled" disabled>
                                        <i class="feather-lock me-1"></i> পরীক্ষা দেওয়ার পর ভুল থাকলে চালু হবে
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- ==========================================
                         PART 4: CHAPTER COMPLETED
                         Client: এর মাধ্যমেই অধ্যায় শেষ
                         ========================================== -->
                    @if($isChapterDone)
                        <div class="roadmap-step completed">
                            <div class="roadmap-step-dot" style="background: #10b981;"></div>
                            <div class="p-4 rounded-4 text-center shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff;">
                                <div style="font-size: 52px;" class="mb-2">🏆</div>
                                <h3 class="font-weight-bold text-white mb-2">অভিনন্দন! অধ্যায়টি ১০০% আয়ত্ত হয়েছে</h3>
                                <p class="text-white-50 mb-4" style="max-width: 500px; margin-inline: auto;">
                                    আপনি এই অধ্যায়ের প্রতিটি লেভেল, সমন্বিত পরীক্ষা এবং সব ভুল প্রশ্নের ৩ বার টানা সঠিক সমাধান করে অধ্যায় সম্পন্ন করেছেন।
                                </p>
                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    @if($chapter->subject)
                                        <a href="{{ route('practice_quiz.subject', $chapter->subject->slug) }}" class="btn btn-light rounded-pill px-4 py-2 font-weight-bold text-dark">
                                            <i class="feather-arrow-left me-1"></i> পরবর্তী অধ্যায়ে যান
                                        </a>
                                    @endif
                                    <a href="{{ route('user.myaccount.dashboard') }}" class="btn btn-outline-light rounded-pill px-4 py-2">
                                        <i class="feather-grid me-1"></i> ড্যাশবোর্ড
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
