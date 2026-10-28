@extends('user.pages.test_master')

@push('test_style')
<style>
    .category-hero-banner {
        position: relative;
        overflow: hidden;
        min-height: 290px;
        padding: 65px 0 75px;
        color: #ffffff;
        background: #0f172a;
    }
    .category-hero-carousel {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        pointer-events: none;
    }
    .category-hero-carousel .carousel-inner,
    .category-hero-carousel .carousel-item {
        width: 100%;
        height: 100%;
    }
    .category-hero-carousel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        filter: brightness(0.65) contrast(1.08);
        transform: scale(1.02);
    }
    .category-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at 85% 20%, rgba(3, 198, 187, 0.28) 0%, transparent 60%),
                    linear-gradient(135deg, rgba(15, 23, 42, 0.94) 0%, rgba(15, 23, 42, 0.82) 55%, rgba(15, 23, 42, 0.95) 100%);
        backdrop-filter: blur(1px);
        z-index: 1;
    }

    /* Course Category Cards - Bigger, Prominent, Premium */
    .modern-cat-grid-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 22px;
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        text-decoration: none;
        box-shadow: 0 4px 15px -2px rgba(0, 0, 0, 0.04);
        position: relative;
    }
    .modern-cat-grid-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 35px -5px rgba(0, 0, 0, 0.1), 0 8px 16px -6px rgba(0, 0, 0, 0.05);
        border-color: #03c6bb;
    }
    .modern-cat-img-box {
        width: 100%;
        height: 190px;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 20px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .modern-cat-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modern-cat-grid-card:hover .modern-cat-img-box img {
        transform: scale(1.06);
    }
    .modern-cat-title {
        font-size: 19px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
        line-height: 1.4;
        transition: color 0.25s ease;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 54px;
    }
    .modern-cat-grid-card:hover .modern-cat-title {
        color: #03c6bb;
    }
    .modern-cat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: rgba(3, 198, 187, 0.1);
        color: #009e95;
        border-radius: 9999px;
        font-size: 13.5px;
        font-weight: 600;
        margin-top: auto;
        transition: all 0.2s ease;
    }
    .modern-cat-grid-card:hover .modern-cat-badge {
        background: #03c6bb;
        color: #ffffff;
    }
    .modern-cat-footer {
        width: 100%;
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 14px;
        font-weight: 600;
        color: #64748b;
        transition: color 0.2s ease;
    }
    .modern-cat-grid-card:hover .modern-cat-footer {
        color: #03c6bb;
    }
    .modern-cat-footer i {
        transition: transform 0.25s ease;
    }
    .modern-cat-grid-card:hover .modern-cat-footer i {
        transform: translateX(4px);
        color: #03c6bb;
    }

    /* Dark Mode Theme Support */
    html.dark-theme .bg-color-white,
    [data-theme="dark"] .bg-color-white,
    body.dark .bg-color-white {
        background: #0b1120 !important;
    }
    html.dark-theme .modern-cat-grid-card,
    [data-theme="dark"] .modern-cat-grid-card,
    body.dark .modern-cat-grid-card {
        background: #1e293b !important;
        border-color: #334155 !important;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.4) !important;
    }
    html.dark-theme .modern-cat-grid-card:hover,
    [data-theme="dark"] .modern-cat-grid-card:hover,
    body.dark .modern-cat-grid-card:hover {
        border-color: #03c6bb !important;
        box-shadow: 0 16px 30px -5px rgba(0, 0, 0, 0.6) !important;
    }
    html.dark-theme .modern-cat-img-box,
    [data-theme="dark"] .modern-cat-img-box,
    body.dark .modern-cat-img-box {
        background: #0f172a !important;
    }
    html.dark-theme .modern-cat-title,
    [data-theme="dark"] .modern-cat-title,
    body.dark .modern-cat-title {
        color: #f8fafc !important;
    }
    html.dark-theme .modern-cat-footer,
    [data-theme="dark"] .modern-cat-footer,
    body.dark .modern-cat-footer {
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }
    html.dark-theme .text-muted,
    [data-theme="dark"] .text-muted {
        color: #94a3b8 !important;
    }
    html.dark-theme h3,
    [data-theme="dark"] h3,
    html.dark-theme h2,
    [data-theme="dark"] h2 {
        color: #f8fafc !important;
    }
</style>
@endpush

@section('content')
@php
    $info = \App\Models\SliderInfo::with('images')->orderBy('id', 'desc')->first();
    
    $featuredCats = DB::table('course_categories')
        ->whereIn('rank', [1, 2, 3, 4])
        ->orderBy('rank')
        ->orderBy('id', 'desc')
        ->take(4)
        ->get();

    $courseCounts = DB::table('courses')
        ->select('catID', DB::raw('count(*) as total'))
        ->groupBy('catID')
        ->pluck('total', 'catID');
@endphp

<!-- Start Hero Banner with Featured Highlights Background Slider -->
<div class="category-hero-banner">
    @if($info && $info->images && $info->images->count() > 0)
        <!-- Featured Highlights Background Carousel -->
        <div id="categoryHeroCarousel" class="carousel slide carousel-fade category-hero-carousel" data-bs-ride="carousel" data-bs-interval="4500">
            <div class="carousel-inner h-100">
                @foreach ($info->images as $index => $image)
                    <div class="carousel-item h-100 {{ $index == 0 ? 'active' : '' }}">
                        <img src="{{ asset($image->image) }}" class="d-block w-100 h-100" alt="{{ $info->title ?? 'Featured Highlights' }}">
                    </div>
                @endforeach
            </div>
        </div>
        <div class="category-hero-overlay"></div>
    @else
        <div class="category-hero-overlay"></div>
    @endif

    <div class="container position-relative" style="z-index: 2;">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <nav aria-label="breadcrumb">
                    <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                        <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">Home</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li style="color: #03c6bb; font-weight: 600;">Course Categories</li>
                    </ul>
                </nav>
                <div class="student-hero-badge mb-3 d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill" style="background: rgba(3, 198, 187, 0.15); border: 1px solid rgba(3, 198, 187, 0.35); color: #2dd4bf; font-size: 13px; font-weight: 700;">
                    <i class="feather-book"></i> Comprehensive Course Programs
                </div>
                <h1 class="student-hero-title mb-2 text-white" style="font-size: clamp(28px, 4vw, 42px); font-weight: 800; line-height: 1.25;">
                    Explore Our Academic & Job Prep Categories
                </h1>
                <p class="student-hero-desc mb-0" style="color: #cbd5e1; font-size: 16px; max-width: 650px;">
                    Find top-tier courses, video lessons, and mock test preparation for government and competitive exams.
                </p>
            </div>
            <div class="col-lg-4 col-12 mt-4 mt-lg-0 text-lg-end">
                <a href="{{ route('/') }}" class="rbt-btn btn-border radius-round" style="color: #fff; border-color: rgba(255,255,255,0.25); background: rgba(255,255,255,0.06); backdrop-filter: blur(4px);">
                    <span class="btn-text">Back to Home</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Banner -->

<!-- Main Categories Section -->
<div class="rbt-section-gap bg-color-white">
    <div class="container">
        <!-- Section Header for Top Specializations -->
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 pb-2 border-bottom">
            <div>
                <h3 class="mb-1" style="font-weight: 800; color: #0f172a; font-size: 26px;">Top Specializations</h3>
                <p class="text-muted mb-0" style="font-size: 15px;">Select your field of study to view available modules and preparations.</p>
            </div>
            <div class="mt-2 mt-sm-0">
                <span class="badge px-3 py-2 rounded-pill" style="background: rgba(3, 198, 187, 0.12); color: #009e95; font-size: 13px; font-weight: 700;">
                    <i class="feather-star"></i> Featured Programs
                </span>
            </div>
        </div>

        <!-- Bigger 4-Column Grid for Top Specializations -->
        <div class="row g-4">
            @foreach ($featuredCats as $course)
                @php
                    $cCount = $courseCounts[$course->id] ?? 0;
                @endphp
                <div class="col-xl-3 col-lg-3 col-md-6 col-12">
                    <a href="{{ route('cat', $course->id) }}" class="modern-cat-grid-card">
                        <div class="modern-cat-img-box">
                            <img src="{{ asset($course->image) }}" alt="{{ $course->name }}" onerror="this.onerror=null; this.src='{{ asset('frontend/assets/images/course-cat-default.jpg') }}';">
                        </div>
                        <h4 class="modern-cat-title">{{ $course->name }}</h4>
                        <span class="modern-cat-badge">
                            <i class="feather-book-open"></i> {{ $cCount }} Courses
                        </span>
                        <div class="modern-cat-footer">
                            <span>View Details</span>
                            <i class="feather-arrow-right"></i>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Secondary Categories (Rank 5-8) -->
        @php
            $secondaryCats = DB::table('course_categories')
                ->whereIn('rank', [5, 6, 7, 8])
                ->orderBy('rank')
                ->orderBy('id', 'desc')
                ->take(4)
                ->get();
        @endphp

        @if($secondaryCats->count() > 0)
            <div class="row mt-5 pt-4">
                <div class="col-12 mb-4 pb-2 border-bottom">
                    <h3 style="font-weight: 800; color: #0f172a; font-size: 24px;">Additional Programs</h3>
                    <p class="text-muted mb-0" style="font-size: 15px;">Broaden your skills with secondary certification and prep courses.</p>
                </div>
                @foreach ($secondaryCats as $data)
                    @php
                        $cCount = $courseCounts[$data->id] ?? 0;
                    @endphp
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-4">
                        <a href="{{ route('cat', $data->id) }}" class="modern-cat-grid-card">
                            <div class="modern-cat-img-box">
                                <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" onerror="this.onerror=null; this.src='{{ asset('frontend/assets/images/course-cat-default.jpg') }}';">
                            </div>
                            <h4 class="modern-cat-title">{{ $data->name }}</h4>
                            <span class="modern-cat-badge">
                                <i class="feather-book-open"></i> {{ $cCount }} Courses
                            </span>
                            <div class="modern-cat-footer">
                                <span>View Details</span>
                                <i class="feather-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Other Special & Govt Exam Categories -->
        @php
            $otherCats = DB::table('course_categories')
                ->whereNotIn('rank', [1, 2, 3, 4, 5, 6, 7, 8])
                ->orderBy('rank')
                ->orderBy('id', 'desc')
                ->get();
        @endphp

        @if($otherCats->count() > 0)
            <div class="row mt-5 pt-4 border-top">
                <div class="col-12 text-center mb-5">
                    <span class="badge px-3 py-2 rounded-pill text-uppercase" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700; letter-spacing: 0.5px;">More Opportunities</span>
                    <h2 class="mt-2" style="font-weight: 800; color: #0f172a; font-size: 28px;">Other Govt & Competitive Jobs</h2>
                    <p class="text-muted" style="max-width: 600px; margin: 0 auto; font-size: 15px;">Discover specialized preparation courses targeting specific government departments.</p>
                </div>
                @foreach ($otherCats as $data)
                    @php
                        $cCount = $courseCounts[$data->id] ?? 0;
                    @endphp
                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-4">
                        <a href="{{ route('cat', $data->id) }}" class="modern-cat-grid-card">
                            <div class="modern-cat-img-box">
                                <img src="{{ asset($data->image) }}" alt="{{ $data->name }}" onerror="this.onerror=null; this.src='{{ asset('frontend/assets/images/course-cat-default.jpg') }}';">
                            </div>
                            <h4 class="modern-cat-title">{{ $data->name }}</h4>
                            <span class="modern-cat-badge">
                                <i class="feather-book-open"></i> {{ $cCount }} Courses
                            </span>
                            <div class="modern-cat-footer">
                                <span>View Details</span>
                                <i class="feather-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
