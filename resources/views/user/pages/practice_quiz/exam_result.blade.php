@extends('user.pages.test_master')

@push('test_style')
<style>
    .result-hero {
        background: radial-gradient(circle at 10% 20%, rgba(3, 198, 187, 0.15) 0%, transparent 40%),
                    radial-gradient(circle at 90% 80%, rgba(79, 70, 229, 0.18) 0%, transparent 45%),
                    linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 50px 0 60px;
        color: #ffffff;
    }
    .result-stat-card {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        backdrop-filter: blur(10px);
    }
    .review-q-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
    }
    .review-q-card.is-correct-card {
        border-left: 5px solid #10b981 !important;
    }
    .review-q-card.is-wrong-card {
        border-left: 5px solid #ef4444 !important;
    }
    .review-opt {
        padding: 10px 16px;
        border-radius: 10px;
        margin-bottom: 8px;
        font-size: 14.5px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .review-opt.is-right {
        background: #ecfdf5 !important;
        border-color: #10b981 !important;
        color: #065f46 !important;
        font-weight: 700;
    }
    .review-opt.is-user-wrong {
        background: #fef2f2 !important;
        border-color: #ef4444 !important;
        color: #991b1b !important;
        font-weight: 700;
    }

    /* Dark Mode */
    html.dark-theme .review-q-card,
    [data-theme="dark"] .review-q-card,
    body.dark .review-q-card,
    body.dark-theme .review-q-card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark-theme .review-opt,
    [data-theme="dark"] .review-opt,
    body.dark .review-opt,
    body.dark-theme .review-opt {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
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
<div class="result-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">Home</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li><a href="{{ route('practice_quiz.chapter', $attempt->chapter->slug) }}" style="color: #94a3b8; text-decoration: none;">{{ $attempt->chapter->title }}</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #03c6bb; font-weight: 600;">Exam Result</li>
            </ul>
        </nav>

        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <span class="badge rounded-pill mb-2" style="background: rgba(3, 198, 187, 0.15); border: 1px solid rgba(3, 198, 187, 0.3); color: #03c6bb; padding: 6px 14px; font-weight: 600;">
                    Chapter Exam Report
                </span>
                <h1 style="font-size: clamp(26px, 3.5vw, 38px); font-weight: 800; color: #ffffff;" class="mb-2">
                    {{ $attempt->chapter->title }}
                </h1>
                <p style="color: #94a3b8; font-size: 15px;" class="mb-4">
                    পরীক্ষার বিস্তারিত ফলাফল ও প্রশ্নভিত্তিক সমাধান নিচে দেওয়া হলো।
                </p>

                <!-- Stats Grid -->
                <div class="row g-3">
                    <div class="col-sm-3 col-6">
                        <div class="result-stat-card">
                            <div class="small text-white-50">Total Score</div>
                            <div class="h3 font-weight-bold text-white mb-0" style="color: #03c6bb !important;">
                                {{ $attempt->score_percent }}%
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="result-stat-card">
                            <div class="small text-white-50">Correct</div>
                            <div class="h3 font-weight-bold text-success mb-0">
                                {{ $attempt->correct_count }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="result-stat-card">
                            <div class="small text-white-50">Wrong</div>
                            <div class="h3 font-weight-bold text-danger mb-0">
                                {{ $attempt->wrong_count }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-6">
                        <div class="result-stat-card">
                            <div class="small text-white-50">Unanswered</div>
                            <div class="h3 font-weight-bold text-warning mb-0">
                                {{ $attempt->unanswered_count }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12 mt-4 mt-lg-0 text-lg-end">
                <a href="{{ route('practice_quiz.chapter', $attempt->chapter->slug) }}" class="btn btn-outline-light rounded-pill px-4 py-2 font-weight-bold">
                    <i class="feather-map me-1"></i> অধ্যায় রোডম্যাপ
                </a>
            </div>
        </div>
    </div>
</div>

<div class="rbt-section-gap bg-color-white py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-12">

                <!-- Client Requirement Card: ভুলের উপর গেইম -->
                @if($mistakeCount > 0)
                    <div class="card p-4 rounded-4 mb-5 shadow-sm" style="border: 2px solid #f59e0b; background: linear-gradient(135deg, rgba(245, 158, 11, 0.1) 0%, rgba(239, 68, 68, 0.08) 100%);">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 54px; height: 54px; background: #f59e0b; color: #1e293b; font-size: 24px;">
                                    ⚡
                                </div>
                                <div>
                                    <h4 class="mb-1 font-weight-bold text-dark-title" style="font-size: 20px;">
                                        পরবর্তী ধাপ: ভুলের ওপর বিশেষ গেম ({{ $mistakeCount }}টি প্রশ্ন)
                                    </h4>
                                    <p class="text-dark-muted small mb-0">
                                        পরীক্ষায় ভুল/অনুত্তরিত প্রশ্নগুলো নিয়ে বিশেষ মেমোরি গেম। প্রতিটি ভুল প্রশ্নে <strong>টানা ৩ বার কারেক্ট</strong> করে অধ্যায় সম্পন্ন করুন।
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('practice_quiz.mistake_game.start', $attempt->chapter_id) }}" class="btn btn-warning text-dark font-weight-bold rounded-pill px-4 py-3 shadow-sm d-inline-flex align-items-center gap-2" style="font-size: 15px;">
                                    <i class="feather-zap"></i>
                                    <span>ভুলের ওপর গেম শুরু করুন (Play Mistake Game)</span>
                                </a>
                                <a href="{{ route('practice_quiz.exam.start', $attempt->chapter_id) }}" class="btn btn-outline-secondary rounded-pill px-3 py-3" onclick="return confirm('নতুন করে পরীক্ষাটি আবার দিবেন?');">
                                    <i class="feather-rotate-ccw"></i> আবার পরীক্ষা দিন
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card p-4 rounded-4 mb-5 shadow-sm text-center" style="border: 2px solid #10b981; background: linear-gradient(135deg, rgba(16, 185, 129, 0.12) 0%, #ffffff 100%);">
                        <div style="font-size: 48px;" class="mb-2">🏆</div>
                        <h4 class="font-weight-bold text-success mb-1">অসাধারণ! পরীক্ষায় কোনো ভুল নেই!</h4>
                        <p class="text-muted small mb-3">আপনি সবগুলো প্রশ্নের সঠিক উত্তর দিয়েছেন। এই অধ্যায়টি শতভাগ সফলতার সাথে সম্পন্ন হয়েছে।</p>
                        <div>
                            <a href="{{ route('practice_quiz.chapter', $attempt->chapter->slug) }}" class="btn btn-success rounded-pill px-4 py-2 font-weight-bold">
                                অধ্যায় রোডম্যাপে যান
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Questions Detailed Review -->
                <h4 class="text-dark-title font-weight-bold mb-4">
                    <i class="feather-book-open text-primary me-2"></i> প্রশ্নভিত্তিক সমাধান ও ব্যাখ্যা (All Questions Review)
                </h4>

                @foreach($attempt->answers as $index => $ans)
                    @php 
                        $q = $ans->question; 
                        $isCorrect = (bool)$ans->is_correct;
                    @endphp
                    @if($q)
                    <div class="review-q-card {{ $isCorrect ? 'is-correct-card' : 'is-wrong-card' }}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge rounded-pill" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700; font-size: 12px; padding: 4px 10px;">
                                প্রশ্ন {{ $index + 1 }}
                            </span>
                            @if($isCorrect)
                                <span class="badge bg-success text-white" style="font-size: 12px;"><i class="feather-check-circle"></i> সঠিক উত্তর</span>
                            @else
                                <span class="badge bg-danger text-white" style="font-size: 12px;"><i class="feather-x-circle"></i> ভুল / অনুত্তরিত</span>
                            @endif
                        </div>

                        <h5 class="text-dark-title mb-3" style="font-weight: 700; font-size: 17px; line-height: 1.5;">
                            {{ $q->question }}
                        </h5>

                        @if(!empty($q->image))
                            <div class="text-center my-3">
                                <img src="{{ str_starts_with($q->image, 'http') ? $q->image : asset($q->image) }}" alt="উদ্দীপক/চিত্র" class="img-fluid rounded-3" style="max-height: 200px;">
                            </div>
                        @endif

                        <div class="options-review mb-3">
                            @php
                                $opts = [
                                    1 => $q->optn1,
                                    2 => $q->optn2,
                                    3 => $q->optn3,
                                    4 => $q->optn4,
                                    5 => $q->optn5,
                                ];
                                $letters = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E'];
                            @endphp
                            @foreach($opts as $optNum => $optText)
                                @if(!empty($optText))
                                    @php
                                        $isRightAns = ((int)$q->right_ans === (int)$optNum);
                                        $isUserChosen = ((int)$ans->selected_option === (int)$optNum);
                                        $optClass = '';
                                        if ($isRightAns) {
                                            $optClass = 'is-right';
                                        } elseif ($isUserChosen && !$isRightAns) {
                                            $optClass = 'is-user-wrong';
                                        }
                                    @endphp
                                    <div class="review-opt {{ $optClass }}">
                                        <span class="badge bg-light text-dark font-weight-bold" style="width: 24px; height: 24px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
                                            {{ $letters[$optNum] }}
                                        </span>
                                        <span class="flex-grow-1">{{ $optText }}</span>
                                        @if($isRightAns)
                                            <span class="badge bg-success text-white small">সঠিক উত্তর</span>
                                        @elseif($isUserChosen)
                                            <span class="badge bg-danger text-white small">আপনার উত্তর</span>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        @if(!empty($q->explanation))
                            <div class="p-3 rounded-3 mt-2" style="background: rgba(3, 198, 187, 0.08); border-left: 4px solid #03c6bb;">
                                <div class="small font-weight-bold text-primary mb-1 d-flex align-items-center gap-1">
                                    <i class="feather-book-open"></i> ব্যাখ্যা ও বিশ্লেষণ:
                                </div>
                                <div class="small text-dark-title" style="line-height: 1.6;">
                                    {!! $q->explanation !!}
                                </div>
                            </div>
                        @endif
                    </div>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
</div>
@endsection
