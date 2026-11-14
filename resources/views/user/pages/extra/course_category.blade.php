@extends('user.pages.test_master')

@push('test_style')
<style>
    .spark-course-hero {
        position: relative;
        background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
        padding: 65px 0 75px;
        color: #ffffff;
    }
    .spark-course-card-lg {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(7, 17, 38, 0.08);
        transition: all 0.3s ease;
    }
    .spark-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        color: #1e293b;
    }
    .spark-course-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        height: 48px !important;
        min-height: 48px !important;
        line-height: 1 !important;
        padding: 0 24px !important;
        border-radius: 9999px !important;
        font-family: 'Hind Siliguri', sans-serif !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.25s ease !important;
        box-sizing: border-box;
        cursor: pointer;
        gap: 8px;
    }
    .spark-course-btn span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    .spark-course-btn i {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        top: 0 !important;
        position: static !important;
        font-size: 15px;
        margin: 0;
        padding: 0;
    }
    .spark-course-btn-trial {
        background: #ffffff !important;
        border: 2px solid #ff7a00 !important;
        color: #ff7a00 !important;
    }
    .spark-course-btn-trial:hover {
        background: #ff7a00 !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 122, 0, 0.3);
    }
    .spark-course-btn-enter {
        background: linear-gradient(135deg, #03c6bb 0%, #6366f1 100%) !important;
        border: 2px solid transparent !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(3, 198, 187, 0.3);
    }
    .spark-course-btn-enter:hover {
        background: linear-gradient(135deg, #02b3a9 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(3, 198, 187, 0.45);
    }

    /* Dark Theme Visibility Overrides for Course Page */
    html.dark-theme .bg-color-white {
        background-color: #0b1120 !important;
    }
    html.dark-theme .spark-course-card-lg {
        background: #131f37 !important;
        border-color: #243247 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }
    html.dark-theme .spark-course-card-lg h2 {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-course-card-lg p {
        color: #cbd5e1 !important;
    }
    html.dark-theme .spark-feature-item {
        background: #1e293b !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }
    html.dark-theme .spark-feature-item span {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-course-card-lg .border-top {
        border-color: #243247 !important;
    }
    html.dark-theme .spark-course-btn-trial {
        background: #1e293b !important;
        color: #ff7a00 !important;
        border-color: #ff7a00 !important;
    }
    html.dark-theme .spark-course-btn-trial:hover {
        background: #ff7a00 !important;
        color: #ffffff !important;
        border-color: #ff7a00 !important;
        box-shadow: 0 6px 18px rgba(255, 122, 0, 0.4) !important;
    }
</style>
@endpush

@section('content')
@php
    $courseId = $course->id ?? 1;
    $coursePrice = $course->price ?? 3000;
    $courseTitle = $course->name ?? 'HSC-28 Physics 1st Paper Basic to Advance Course';
    $courseImage = $course->image ?? 'images/bcs_prep.jpg';
    $courseDescription = !empty($course->description) ? $course->description : 'পদার্থবিজ্ঞান ১ম পত্রের ১০টি অধ্যায় একদম গোড়া থেকে অ্যাডভান্স লেভেল পর্যন্ত পড়ানো হবে। ক্লাস শেষে থাকবে টপিকভিত্তিক নোট, মডেল টেস্ট এবং অধ্যায়ভিত্তিক প্রগ্রেস ট্র্যাকিং।';

    // Fetch chapters and modules dynamically for this course
    try {
        if (isset($course->chapters) && $course->chapters instanceof \Illuminate\Support\Collection) {
            $courseChapters = $course->chapters;
        } else {
            $courseChapters = DB::table('course_chapters')
                ->where('course_id', $courseId)
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();
        }

        if (isset($course->courseModules) && $course->courseModules instanceof \Illuminate\Support\Collection) {
            $courseModules = $course->courseModules;
        } else {
            $courseModules = DB::table('course_modules')->where('course_id', $courseId)->get();
        }
    } catch (\Throwable $e) {
        $courseChapters = collect();
        $courseModules = collect();
    }

    $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    $toBn = function($num) use ($bnDigits) {
        return str_replace(range(0, 9), $bnDigits, (string)$num);
    };

    $freeModulesCount = $courseModules->where('free_paid', 'free')->count();
    $freeChapterName = null;

    if ($courseChapters->isNotEmpty()) {
        $totalChaptersCount = $courseChapters->count();
        $firstFreeModule = $courseModules->firstWhere('free_paid', 'free');
        if ($firstFreeModule && !empty($firstFreeModule->chapter_id)) {
            $matchedChapter = $courseChapters->firstWhere('id', $firstFreeModule->chapter_id);
            $freeChapterName = optional($matchedChapter)->title;
        }
        $chaptersText = $toBn($totalChaptersCount) . 'টি পূর্ণাঙ্গ অধ্যায়';
    } else {
        // Legacy fallback when chapters have not been created yet
        $detectedChapters = [];
        foreach ($courseModules as $mod) {
            $title = $mod->title ?? '';
            if (preg_match('/(অধ্যায়[\s\-_–:]*[০-৯0-9]+|Chapter[\s\-_–:]*[0-9]+)/iu', $title, $matches)) {
                $chKey = trim($matches[1]);
                $detectedChapters[$chKey] = true;
                if ($mod->free_paid === 'free' && !$freeChapterName) {
                    $freeChapterName = preg_replace('/০([১-৯])/u', '$1', $chKey);
                }
            }
        }

        $totalChaptersCount = count($detectedChapters);
        if ($totalChaptersCount > 0) {
            $chaptersText = $toBn($totalChaptersCount) . 'টি পূর্ণাঙ্গ অধ্যায়';
        } elseif ($courseModules->count() > 0) {
            $chaptersText = $toBn($courseModules->count()) . 'টি পূর্ণাঙ্গ লেকচার';
        } else {
            $chaptersText = '১০টি পূর্ণাঙ্গ অধ্যায়';
        }
    }

    // Dynamic Free Trial Class Highlight
    $hasFreeLessons = ($freeModulesCount > 0);
    if ($hasFreeLessons) {
        if ($freeChapterName) {
            $freeTrialText = $freeChapterName . ' ফ্রি ট্রায়াল ক্লাস';
        } else {
            $freeTrialText = $toBn($freeModulesCount) . 'টি ফ্রি ট্রায়াল ক্লাস';
        }
    } else {
        $freeTrialText = '১০০% প্রিমিয়াম কারিকুলাম';
    }
@endphp

<!-- Start Hero Banner -->
<div class="spark-course-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <nav aria-label="breadcrumb">
                    <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                        <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">হোম</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li style="color: #ff7a00; font-weight: 600;">আমাদের কোর্স সমূহ</li>
                    </ul>
                </nav>
                <span class="badge mb-3 px-3 py-2" style="background: rgba(255, 122, 0, 0.16); color: #ff7a00; border: 1px solid rgba(255, 122, 0, 0.35); font-size: 13px; font-weight: 700; border-radius: 9999px;">
                    <i class="feather-award"></i> স্পার্ক একাডেমি ফ্ল্যাগশিপ প্রোগ্রাম
                </span>
                <h1 class="text-white mb-2" style="font-size: clamp(28px, 3.8vw, 42px); font-weight: 800; line-height: 1.3;">
                    আমাদের কোর্সসমূহ
                </h1>
                <p style="color: #cbd5e1; font-size: 17px; max-width: 680px; margin-bottom: 0;">
                    এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের পদার্থবিজ্ঞানের সম্পূর্ণ প্রস্তুতির জন্য একটি নির্ভরযোগ্য ও সেরা কোর্স।
                </p>
            </div>
            <div class="col-lg-4 col-12 mt-4 mt-lg-0 text-lg-end">
                <a href="{{ route('/') }}" class="rbt-btn btn-border radius-round" style="color: #fff; border-color: rgba(255,255,255,0.25); background: rgba(255,255,255,0.06);">
                    <span class="btn-text">হোমে ফিরে যান</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Banner -->

<!-- Main Single Course Showcase Section -->
<div class="rbt-section-gap bg-color-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="spark-course-card-lg">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-5">
                            <div class="position-relative overflow-hidden rounded-4 shadow-sm" style="border: 2px solid #e2e8f0;">
                                <img src="{{ asset($courseImage) }}" class="w-100" alt="{{ $courseTitle }}" style="height: 320px; object-fit: cover;">
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill font-weight-bold" style="font-size: 12px; font-weight: 800;">
                                        <i class="feather-star me-1"></i> একটাই পূর্ণাঙ্গ কোর্স
                                    </span>
                                </div>
                                <div class="position-absolute bottom-0 start-0 end-0 p-3 text-white" style="background: linear-gradient(0deg, rgba(7,17,38,0.92) 0%, transparent 100%);">
                                    <span style="font-size: 13px; color: #ff7a00; font-weight: 700;">ইমু স্যারের স্পেশাল কোর্স</span>
                                    <h5 class="text-white mb-0" style="font-size: 17px; font-weight: 700;">Physics 1st Paper</h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-7">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge" style="background: rgba(16, 185, 129, 0.12); color: #065f46; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                                    HSC-28 ব্যাচ
                                </span>
                                <span class="badge" style="background: rgba(255, 122, 0, 0.12); color: #ff7a00; font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">
                                    বেসিক টু অ্যাডভান্সড
                                </span>
                            </div>

                            <h2 style="font-size: clamp(24px, 2.5vw, 32px); font-weight: 800; color: #071126; margin-bottom: 12px;">
                                {{ $courseTitle }}
                            </h2>

                            <div style="color: #64748b; font-size: 16px; line-height: 1.7; margin-bottom: 24px;">
                                {!! $courseDescription !!}
                            </div>

                            <!-- Key Highlights (Dynamically Calculated from Database) -->
                            <div class="row g-2 mb-4">
                                <div class="col-md-6">
                                    <div class="spark-feature-item">
                                        <i class="feather-check-circle text-success font-weight-bold"></i>
                                        <span>{{ $chaptersText }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="spark-feature-item">
                                        @if($hasFreeLessons)
                                            <i class="feather-unlock text-warning font-weight-bold"></i>
                                            <span>{{ $freeTrialText }}</span>
                                        @else
                                            <i class="feather-award text-primary font-weight-bold"></i>
                                            <span>{{ $freeTrialText }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="spark-feature-item">
                                        <i class="feather-activity text-primary font-weight-bold"></i>
                                        <span>অধ্যায়ভিত্তিক প্রগ্রেস ট্র্যাকার</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="spark-feature-item">
                                        <i class="feather-zap text-warning font-weight-bold"></i>
                                        <span>MCQ গেম প্র্যাকটিস টেস্ট</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Course Price & Actions -->
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 pt-3 border-top">
                                <div>
                                    <span style="font-size: 13px; color: #64748b; font-weight: 600;">কোর্স ফি:</span>
                                    <div style="font-size: 32px; font-weight: 800; color: #ff7a00; font-family: var(--font-heading);">
                                        ৳{{ number_format($coursePrice, 0) }}
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    @if($hasFreeLessons)
                                        <a href="{{ route('free.lession', $courseId) }}" class="spark-course-btn spark-course-btn-trial">
                                            <i class="feather-play"></i> <span>ফ্রি ক্লাস করে দেখুন</span>
                                        </a>
                                    @endif
                                    <a href="{{ route('course.detail', $courseId) }}" class="spark-course-btn spark-course-btn-enter">
                                        <span>কোর্সে প্রবেশ করুন</span> <i class="feather-arrow-right"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
