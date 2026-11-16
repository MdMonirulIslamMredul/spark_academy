@extends('user.pages.test_master')

@push('test_style')
    <style>
        /* Spark Academy Theme Colors & Layout */
        :root {
            --spark-navy: #071126;
            --spark-navy-card: #0d1e3d;
            --spark-orange: #ff7a00;
            --spark-orange-hover: #e05e00;
        }

        .mainmenu li {
            margin: 0 10px;
        }

        .rbt-header .mainmenu-nav .mainmenu>li>a {
            font-weight: 600;
        }

        /* Hero Slider Custom Styling */
        .hero-slider-wrapper {
            position: relative;
            overflow: hidden;
            background: #071126;
        }
        .hero-banner-swiper {
            width: 100%;
            position: relative;
        }
        .hero-slide-item {
            position: relative;
            min-height: 520px;
            display: flex;
            align-items: center;
            background-size: cover !important;
            background-position: center center !important;
            background-repeat: no-repeat !important;
            padding: 85px 0 95px;
        }
        .hero-slide-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(7, 17, 38, 0.95) 0%, rgba(7, 17, 38, 0.85) 55%, rgba(7, 17, 38, 0.5) 100%);
            pointer-events: none;
            z-index: 1;
        }
        .hero-slide-item .container {
            position: relative;
            z-index: 2;
        }
        .spark-hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 122, 0, 0.16);
            border: 1px solid rgba(255, 122, 0, 0.35);
            color: #ff7a00;
            font-size: 13px;
            font-weight: 700;
            padding: 6px 18px;
            border-radius: 9999px;
            margin-bottom: 20px;
            letter-spacing: 0.05em;
        }
        .spark-hero-title {
            color: #ffffff !important;
            font-size: clamp(26px, 3.8vw, 44px);
            font-weight: 800;
            line-height: 1.4;
            margin-bottom: 18px;
            max-width: 820px;
        }
        .spark-hero-desc {
            color: #cbd5e1;
            font-size: clamp(16px, 2vw, 20px);
            line-height: 1.6;
            max-width: 700px;
            margin-bottom: 35px;
            font-weight: 500;
        }

        /* 3 Core Action Buttons */
        .spark-btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
            align-items: center;
            margin-bottom: 10px;
        }
        .spark-hero-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 26px;
            border-radius: 9999px;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.28s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
        }
        .spark-btn-courses {
            background: linear-gradient(135deg, #ff7a00 0%, #ea580c 100%);
            color: #ffffff !important;
            border: 2px solid #ff7a00;
        }
        .spark-btn-courses:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 25px rgba(255, 122, 0, 0.4);
            color: #ffffff !important;
        }
        .spark-btn-books {
            background: #ffffff;
            color: #071126 !important;
            border: 2px solid #ffffff;
        }
        .spark-btn-books:hover {
            background: #f8fafc;
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 25px rgba(255, 255, 255, 0.25);
            color: #ff7a00 !important;
        }
        .spark-btn-mcq {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff !important;
            border: 2px solid rgba(255, 255, 255, 0.35);
            backdrop-filter: blur(6px);
        }
        .spark-btn-mcq:hover {
            background: #ff7a00;
            border-color: #ff7a00;
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 25px rgba(255, 122, 0, 0.35);
            color: #ffffff !important;
        }

        .hero-slider-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: rgba(7, 17, 38, 0.65);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            cursor: pointer;
            z-index: 15;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            padding: 0;
        }
        .hero-slider-nav:hover {
            background: #ff7a00;
            border-color: #ff7a00;
            color: #ffffff;
            transform: translateY(-50%) scale(1.08);
        }
        .hero-slider-prev { left: 25px; }
        .hero-slider-next { right: 25px; }
        .hero-swiper-pagination {
            position: absolute;
            bottom: 25px !important;
            left: 0;
            right: 0;
            text-align: center;
            z-index: 15;
        }
        .hero-swiper-pagination .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            margin: 0 5px !important;
            background: rgba(255, 255, 255, 0.45);
            opacity: 1;
            border-radius: 9999px;
            transition: all 0.35s ease;
            display: inline-block;
            cursor: pointer;
        }
        .hero-swiper-pagination .swiper-pagination-bullet-active {
            width: 32px;
            background: #ff7a00 !important;
            box-shadow: 0 0 12px rgba(255, 122, 0, 0.6);
        }
        @media (max-width: 768px) {
            .hero-slide-item {
                min-height: 440px;
                padding: 55px 0 70px;
            }
            .hero-slider-nav { display: none !important; }
        }

        /* 3 Offerings Cards */
        .spark-hub-section {
            padding: 60px 0 40px;
            background: #f8fafc;
        }
        .spark-hub-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 32px 26px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        }
        .spark-hub-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 30px -8px rgba(7, 17, 38, 0.12);
            border-color: #ff7a00;
        }
        .spark-hub-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
        }
        .spark-hub-icon.course-icon {
            background: rgba(255, 122, 0, 0.12);
            color: #ff7a00;
        }
        .spark-hub-icon.book-icon {
            background: rgba(79, 70, 229, 0.12);
            color: #4f46e5;
        }
        .spark-hub-icon.game-icon {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        /* Expert Section */
        .spark-expert-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }
        .spark-expert-avatar {
            width: 170px;
            height: 170px;
            border-radius: 50%;
            border: 4px solid #ff7a00;
            padding: 3px;
            object-fit: cover;
            object-position: 50% 18%;
            box-shadow: 0 8px 24px rgba(255, 122, 0, 0.25);
            background: #ffffff;
        }
        .spark-credential-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            color: #071126;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin: 4px;
            border: 1px solid #e2e8f0;
        }
        .spark-social-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            color: #fff !important;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .spark-social-fb {
            background: #1877f2;
        }
        .spark-social-yt {
            background: #ff0000;
        }
        .spark-social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
        }

        /* Offline Address Banner */
        .spark-address-card {
            background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
            color: #ffffff;
            border-radius: 20px;
            padding: 30px 36px;
            box-shadow: 0 10px 30px rgba(7, 17, 38, 0.15);
        }

        /* Review Card */
        .spark-review-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            height: 100%;
            transition: transform 0.25s ease;
        }
        .spark-review-card:hover {
            transform: translateY(-4px);
            border-color: #ff7a00;
        }

        /* Perfectly Centered Card & Action Buttons */
        .spark-card-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            height: 48px !important;
            min-height: 48px !important;
            line-height: 1 !important;
            padding: 0 20px !important;
            border-radius: 9999px !important;
            font-family: 'Hind Siliguri', sans-serif !important;
            font-size: 15.5px !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1) !important;
            box-sizing: border-box !important;
            margin: 0 !important;
            width: 100%;
            gap: 8px;
        }
        .spark-card-btn span,
        .spark-card-btn .btn-text {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
            margin: 0;
            padding: 0;
        }
        .spark-card-btn i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            line-height: 1;
            top: 0 !important;
            position: static !important;
            margin: 0;
            padding: 0;
        }
        .spark-card-btn-courses {
            background: linear-gradient(135deg, #03c6bb 0%, #6366f1 100%) !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(3, 198, 187, 0.3);
        }
        .spark-card-btn-courses:hover {
            background: linear-gradient(135deg, #02b3a9 0%, #4f46e5 100%) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(3, 198, 187, 0.45);
        }
        .spark-card-btn-books {
            background: #ffffff !important;
            color: #071126 !important;
            border: 2px solid #e2e8f0 !important;
        }
        .spark-card-btn-books:hover {
            background: #ff7a00 !important;
            color: #ffffff !important;
            border-color: #ff7a00 !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 122, 0, 0.3);
        }
        .spark-card-btn-game {
            background: #10b981 !important;
            color: #ffffff !important;
            border: none !important;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
        }
        .spark-card-btn-game:hover {
            background: #059669 !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(16, 185, 129, 0.45);
        }
        .spark-phone-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            height: 48px !important;
            min-height: 48px !important;
            line-height: 1 !important;
            padding: 0 28px !important;
            border-radius: 9999px !important;
            font-family: 'Hind Siliguri', sans-serif !important;
            font-size: 15.5px !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            background: linear-gradient(135deg, #03c6bb 0%, #a855f7 100%) !important;
            color: #ffffff !important;
            border: none !important;
            gap: 8px;
            box-shadow: 0 4px 16px rgba(3, 198, 187, 0.35);
            transition: all 0.28s ease !important;
        }
        .spark-phone-btn:hover {
            background: linear-gradient(135deg, #02b3a9 0%, #9333ea 100%) !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(3, 198, 187, 0.5);
        }
        .spark-phone-btn span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            line-height: 1;
        }
        .spark-phone-btn i {
            top: 0 !important;
            position: static !important;
            font-size: 16px;
            line-height: 1;
            margin: 0;
            padding: 0;
        }

        /* Dark Theme Visibility Overrides for Homepage */
        html.dark-theme .spark-hub-section {
            background: #0b1120 !important;
        }
        html.dark-theme .spark-hub-card {
            background: #131f37 !important;
            border-color: #243247 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
        }
        html.dark-theme .spark-hub-card:hover {
            border-color: #ff7a00 !important;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.65), 0 0 18px rgba(255, 122, 0, 0.22) !important;
        }
        html.dark-theme .spark-hub-card h4 {
            color: #f8fafc !important;
        }
        html.dark-theme .spark-hub-card p {
            color: #cbd5e1 !important;
        }
        html.dark-theme .spark-hub-card p strong {
            color: #f8fafc !important;
        }
        html.dark-theme .spark-btn-books,
        html.dark-theme .spark-card-btn-books {
            background: #1e293b !important;
            color: #ff7a00 !important;
            border: 2px solid #ff7a00 !important;
        }
        html.dark-theme .spark-btn-books:hover,
        html.dark-theme .spark-card-btn-books:hover {
            background: #ff7a00 !important;
            color: #ffffff !important;
            border-color: #ff7a00 !important;
            box-shadow: 0 6px 18px rgba(255, 122, 0, 0.4) !important;
        }
        html.dark-theme section#about-spark {
            background: #0b1120 !important;
        }
        html.dark-theme #about-spark .title {
            color: #f8fafc !important;
        }
        html.dark-theme #about-spark p {
            color: #cbd5e1 !important;
        }
        html.dark-theme .spark-expert-card {
            background: #131f37 !important;
            border-color: #243247 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
        }
        html.dark-theme .spark-expert-avatar {
            background: #1e293b !important;
            border-color: #ff7a00 !important;
        }
        html.dark-theme .spark-expert-card h4,
        html.dark-theme .spark-expert-card h5 {
            color: #f8fafc !important;
        }
        html.dark-theme .spark-expert-card p {
            color: #cbd5e1 !important;
        }
        html.dark-theme .spark-credential-badge {
            background: #1e293b !important;
            color: #f8fafc !important;
            border-color: #334155 !important;
        }
        html.dark-theme section#reviews-section {
            background: #0b1120 !important;
        }
        html.dark-theme #reviews-section .title {
            color: #f8fafc !important;
        }
        html.dark-theme #reviews-section p {
            color: #94a3b8 !important;
        }
        html.dark-theme .spark-review-card {
            background: #131f37 !important;
            border-color: #243247 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
        }
        html.dark-theme .spark-review-card:hover {
            border-color: #ff7a00 !important;
            box-shadow: 0 14px 30px rgba(0, 0, 0, 0.65), 0 0 18px rgba(255, 122, 0, 0.22) !important;
        }
        html.dark-theme .spark-review-card p {
            color: #cbd5e1 !important;
        }
        html.dark-theme .spark-review-card h6 {
            color: #f8fafc !important;
        }
        html.dark-theme .spark-review-card small,
        html.dark-theme .spark-review-card span {
            color: #94a3b8 !important;
        }
        html.dark-theme .spark-review-card .border-top {
            border-color: #243247 !important;
        }
        html.dark-theme .badge.bg-light.text-muted {
            background: #1e293b !important;
            color: #94a3b8 !important;
            border: 1px solid #334155 !important;
        }
    </style>
@endpush

@section('content')
    @php
        // 4 Specific Bengali Hero Slides from Client PDF (Page 1)
        $spark_slides = [
            [
                'badge' => 'SPARK ACADEMY • স্বপ্ন বাস্তবায়নে',
                'title' => 'শুধু স্বপ্ন দেখানোই নয়, স্বপ্ন বাস্তবায়নেই আমাদের কাজ',
                'desc'  => 'যদি তুমি হও সত্যিকারের স্বপ্নবাজ',
                'image' => 'images/bcs_prep.jpg',
            ],
            [
                'badge' => 'HSC SPECIAL PREPARATION',
                'title' => 'তোমার HSC প্রস্তুতিকে সর্বোচ্চ গুছানো ও প্রাণবন্ত করতে',
                'desc'  => 'যুক্ত হয়ে যাও স্পার্ক একাডেমিতে',
                'image' => 'images/bank_prep.jpg',
            ],
            [
                'badge' => 'ACADEMIC + ADMISSION CARE',
                'title' => 'একাডেমিক ও এডমিশন একসাথে প্যারালাল প্রস্তুতির জন্য',
                'desc'  => 'সাথে আছে স্পার্ক একাডেমি',
                'image' => 'images/primary_prep.jpg',
            ],
            [
                'badge' => 'ONLINE COURSE & SPECIAL BOOK',
                'title' => 'অনলাইন কোর্সের সাথে ইমু স্যারের স্পেশাল বই',
                'desc'  => 'তোমাকে এগিয়ে নিবে দুর্দান্ত গতিতে',
                'image' => 'images/govt_job_prep.jpg',
            ],
        ];

        $testimonials = DB::table('testimonials')->get();
        if ($testimonials->isEmpty()) {
            $testimonials = collect([
                (object)[
                    'name' => 'রাফসান আহমেদ',
                    'designation' => 'HSC পরীক্ষার্থী',
                    'description' => 'ইমু স্যারের ফিজিক্স ক্লাসের প্রতিটি টপিক একদম ক্রিস্টাল ক্লিয়ার। বিশেষ করে শর্টকাট টেকনিক ও প্র্যাকটিস টেস্টগুলো দুর্দান্ত!',
                    'rating' => 5
                ],
                (object)[
                    'name' => 'ফারিহা তানজিম',
                    'designation' => 'বিজ্ঞান বিভাগ',
                    'description' => 'স্পার্ক একাডেমির বই আর অনলাইন লেকচার একসাথে ফলো করার পর ফিজিক্সের ভীতি পুরোপুরি কেটে গেছে।',
                    'rating' => 5
                ],
                (object)[
                    'name' => 'তানভীর হাসান',
                    'designation' => 'CUET Aspirant',
                    'description' => 'বেসিক থেকে অ্যাডভান্স লেভেলের কনসেপ্ট এমন চমৎকারভাবে আগে কোথাও শিখিনি। MCQ গেম খেলে প্র্যাকটিস করার অভিজ্ঞতা অনন্য!',
                    'rating' => 5
                ],
            ]);
        }
    @endphp

    <!-- Start Hero Slider Area -->
    <div class="hero-slider-wrapper">
        <div class="hero-banner-swiper swiper">
            <div class="swiper-wrapper">
                @foreach ($spark_slides as $slide)
                    <div class="swiper-slide">
                        <div class="hero-slide-item student-hero"
                            style="background: linear-gradient(90deg, rgba(7, 17, 38, 0.94) 0%, rgba(7, 17, 38, 0.84) 55%, rgba(7, 17, 38, 0.45) 100%), url('{{ asset($slide['image']) }}') center/cover no-repeat;">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-10 col-md-12">
                                        <div class="inner text-start">
                                            <div class="spark-hero-badge">
                                                <i class="feather-award"></i> {{ $slide['badge'] }}
                                            </div>
                                            <h1 class="spark-hero-title">
                                                {{ $slide['title'] }}
                                            </h1>
                                            <p class="spark-hero-desc">
                                                {{ $slide['desc'] }}
                                            </p>

                                            <!-- 3 Primary Action Buttons Requested by Client -->
                                            <div class="spark-btn-group">
                                                <a class="spark-hero-btn spark-btn-courses" href="{{ route('course') }}">
                                                    <i class="feather-book-open"></i> আমাদের কোর্স সমূহ
                                                </a>
                                                <a class="spark-hero-btn spark-btn-books" href="{{ route('user.books') }}">
                                                    <i class="feather-bookmark"></i> স্পার্ক একাডেমির বই
                                                </a>
                                                <a class="spark-hero-btn spark-btn-mcq" href="{{ route('practice_quiz.index') }}">
                                                    <i class="feather-zap"></i> MCQ Game
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Navigation Controls -->
            <button type="button" class="hero-slider-nav hero-slider-prev" aria-label="Previous Slide">
                <i class="feather-chevron-left"></i>
            </button>
            <button type="button" class="hero-slider-nav hero-slider-next" aria-label="Next Slide">
                <i class="feather-chevron-right"></i>
            </button>

            <!-- Pagination Dots -->
            <div class="hero-swiper-pagination"></div>
        </div>
    </div>
    <!-- End Hero Slider Area -->

    <!-- Start 3 Core Offerings Hub (Detailed Direct Access Cards) -->
    <section class="spark-hub-section">
        <div class="container">
            <div class="row g-4">
                <!-- Card 1: Our Courses -->
                <div class="col-lg-4 col-md-6">
                    <div class="spark-hub-card">
                        <div>
                            <div class="spark-hub-icon course-icon">
                                <i class="feather-play-circle"></i>
                            </div>
                            <span class="badge bg-warning text-dark mb-2" style="font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">ফ্ল্যাগশিপ কোর্স</span>
                            <h4 style="font-size: 21px; font-weight: 800; color: #071126; margin-bottom: 12px;">আমাদের কোর্স সমূহ</h4>
                            <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
                                <strong>HSC-28 Physics 1st Paper Basic to Advance Course</strong> — ১০টি পূর্ণাঙ্গ অধ্যায়, ফ্রি ট্রায়াল ক্লাস এবং চ্যাপ্টার প্রগ্রেস ট্র্যাকার সহ।
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('course') }}" class="spark-card-btn spark-card-btn-courses">
                                <span class="btn-text">কোর্সে প্রবেশ করুন</span> <i class="feather-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Books -->
                <div class="col-lg-4 col-md-6">
                    <div class="spark-hub-card">
                        <div>
                            <div class="spark-hub-icon book-icon">
                                <i class="feather-book"></i>
                            </div>
                            <span class="badge bg-info text-white mb-2" style="font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">ইমু স্যারের স্পেশাল বই</span>
                            <h4 style="font-size: 21px; font-weight: 800; color: #071126; margin-bottom: 12px;">স্পার্ক একাডেমির বই</h4>
                            <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
                                অনলাইন কোর্সের পরিপূরক স্পেশাল পদার্থবিজ্ঞান বই। ঘরে বসেই বিকাশ, নগদ বা ক্যাশ অন ডেলিভারিতে অর্ডার করুন এবং নমুনা পৃষ্ঠা পড়ে দেখুন।
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('user.books') }}" class="spark-card-btn spark-card-btn-books">
                                <span class="btn-text">বই দেখুন ও অর্ডার করুন</span> <i class="feather-shopping-bag ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3: MCQ Game -->
                <div class="col-lg-4 col-md-12">
                    <div class="spark-hub-card" style="border-top: 4px solid #10b981;">
                        <div>
                            <div class="spark-hub-icon game-icon">
                                <i class="feather-zap"></i>
                            </div>
                            <span class="badge bg-success text-white mb-2" style="font-weight: 700; font-size: 12px; padding: 4px 10px; border-radius: 6px;">গ্যামিফাইড প্র্যাকটিস</span>
                            <h4 style="font-size: 21px; font-weight: 800; color: #071126; margin-bottom: 12px;">MCQ Game Arena</h4>
                            <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 20px;">
                                টানা ৩ বার কারেক্ট না হওয়া পর্যন্ত গেম চলবে! অধ্যায়ভিত্তিক লেভেল, ভুল করা প্রশ্নের স্পেশাল রিট্রাই মোড এবং স্ট্রিক বোনাস পয়েন্ট সিস্টেম।
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('practice_quiz.index') }}" class="spark-card-btn spark-card-btn-game">
                                <span class="btn-text">MCQ Game খেলুন</span> <i class="feather-play ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End 3 Core Offerings Hub -->

    <!-- Start About Us / আমাদের সম্পর্কে জানুন & Meet Our Expert -->
    @php
        $aboutInfo = $aboutInfo ?? DB::table('about_infos')->first();
    @endphp
    <section class="rbt-section-gap bg-color-white" id="about-spark">
        <div class="container">
            <!-- Section Header -->
            <div class="row justify-content-center text-center mb--40">
                <div class="col-lg-8">
                    <span class="subtitle" style="color: #ff7a00; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: rgba(255, 122, 0, 0.1); padding: 5px 16px; border-radius: 9999px;">
                        স্পার্ক একাডেমি পরিচিতি
                    </span>
                    <h2 class="title mt--15" style="font-size: clamp(26px, 3vw, 36px); font-weight: 800; color: #071126;">
                        আমাদের সম্পর্কে <span style="color: #ff7a00;">জানুন</span>
                    </h2>
                    <div style="color: #475569; font-size: 18px; line-height: 1.8; margin-top: 15px; font-weight: 500;">
                        {!! !empty($aboutInfo->aboutDes) ? $aboutInfo->aboutDes : 'স্পার্ক একাডেমি ইমু স্যার এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য একটি অনলাইন ও অফলাইন কার্যক্রম সম্পন্নকারী প্রতিষ্ঠান। স্পার্ক একাডেমির অনলাইন কোর্সের পাশাপাশি আছে নিজস্ব বই ।' !!}
                    </div>
                </div>
            </div>

            <!-- Meet Our Expert: Engr. Iftekhar Hossain Emu -->
            <div class="row align-items-center justify-content-center mt--20">
                <div class="col-lg-10">
                    <div class="spark-expert-card">
                        <div class="row align-items-center g-4">
                            <div class="col-md-4 text-center">
                                <img src="{{ !empty($aboutInfo->expert_image) ? asset($aboutInfo->expert_image) : asset('images/emu_sir.jpg') }}" class="spark-expert-avatar" alt="{{ $aboutInfo->expert_name ?? 'Engr. Iftekhar Hossain Emu' }}">
                                <h4 class="mt-3 mb-1" style="font-weight: 800; color: #071126; font-size: 22px;">{{ $aboutInfo->expert_name ?? 'Engr. Iftekhar Hossain Emu' }}</h4>
                                <span style="color: #ff7a00; font-weight: 700; font-size: 15px;">{{ $aboutInfo->expert_role ?? 'Physics Mentor' }}</span>
                            </div>
                            <div class="col-md-8">
                                <span class="subtitle" style="color: #ff7a00; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; background: rgba(255, 122, 0, 0.1); padding: 4px 14px; border-radius: 9999px; display: inline-block; margin-bottom: 10px;">
                                    {{ $aboutInfo->expert_badge ?? 'প্রধান মেন্টর পরিচিতি' }}
                                </span>
                                <h5 style="color: #071126; font-weight: 700; margin-bottom: 14px;">
                                    <i class="feather-award text-warning me-2"></i> Meet Our Expert
                                </h5>
                                <div class="d-flex flex-wrap mb-3">
                                    @php
                                        $rawCredsHome = !empty($aboutInfo->expert_credentials)
                                            ? $aboutInfo->expert_credentials
                                            : "B.Sc. (CUET)\nMBA (CU)\nEx-Chittagong College\nFounder of Modulus Academic & Admission Care\nPhysics Teacher (10+ years experience)";
                                        $credsListHome = array_filter(array_map('trim', preg_split('/[\r\n|]+/', $rawCredsHome)));
                                    @endphp
                                    @foreach($credsListHome as $c)
                                        <span class="spark-credential-badge"><i class="feather-check-circle text-success"></i> {{ $c }}</span>
                                    @endforeach
                                </div>
                                <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 22px;">
                                    {{ $aboutInfo->expert_bio ?? 'বিগত ১০ বছরের বেশি সময় ধরে হাজারো শিক্ষার্থীকে ফিজিক্সের জটিল বিষয়গুলো সহজ ও সাবলীলভাবে শিখিয়ে এইচএসসি ও প্রকৌশল বিশ্ববিদ্যালয় ভর্তি পরীক্ষায় ঈর্ষণীয় সাফল্য অর্জনে পথপ্রদর্শন করে আসছেন। একাডেমিক ও এডমিশন একসাথে প্যারালাল প্রস্তুতির মাধ্যমে শিক্ষার্থীদের সর্বোচ্চ স্তরে পৌঁছে দেওয়াই স্পার্ক একাডেমির অঙ্গীকার।' }}
                                </p>
                                
                                <!-- Social Channels -->
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <span style="font-weight: 700; color: #071126; font-size: 14px; margin-right: 6px;">যুক্ত থাকুন:</span>
                                    @if(!empty($aboutInfo->facebook_link))
                                    <a href="{{ $aboutInfo->facebook_link }}" target="_blank" rel="noopener" class="spark-social-btn spark-social-fb">
                                        <i class="fab fa-facebook-f"></i> <span>ফেসবুক পেইজ</span>
                                    </a>
                                    @endif
                                    @if(!empty($aboutInfo->youtube_link))
                                    <a href="{{ $aboutInfo->youtube_link }}" target="_blank" rel="noopener" class="spark-social-btn spark-social-yt">
                                        <i class="fab fa-youtube"></i> <span>ইউটিউব চ্যানেল</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offline Center Address Banner -->
            <div class="row justify-content-center mt--40">
                <div class="col-lg-10">
                    <div class="spark-address-card d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(255, 122, 0, 0.2); color: #ff7a00; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                                <i class="feather-map-pin"></i>
                            </div>
                            <div>
                                <h5 class="text-white mb-1" style="font-weight: 700; font-size: 18px;">অফলাইন সেন্টার ও ঠিকানা</h5>
                                <p class="mb-0 text-white-50" style="font-size: 15px;">
                                    <strong>{{ $aboutInfo->offline_address ?? 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম' }}</strong>
                                </p>
                            </div>
                        </div>
                        <div>
                            @php
                                $homePhone = $aboutInfo->offline_phone ?? '০১৭৬৮২৪০৩০৩';
                                $homeCleanPhone = preg_replace('/[^0-9]/', '', $homePhone) ?: '01768240303';
                            @endphp
                            <a href="tel:{{ $homeCleanPhone }}" class="spark-phone-btn">
                                <i class="feather-phone me-1"></i> <span>{{ $homePhone }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <!-- End About Us -->

    <!-- Start Student Reviews / রিভিউ ছবি ও অভিজ্ঞতা -->
    <section class="rbt-section-gap bg-color-extra2" id="reviews-section">
        <div class="container">
            <div class="row justify-content-center text-center mb--40">
                <div class="col-lg-8">
                    <span class="subtitle" style="color: #ff7a00; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: rgba(255, 122, 0, 0.1); padding: 5px 16px; border-radius: 9999px;">
                        শিক্ষার্থীদের অভিজ্ঞতা
                    </span>
                    <h2 class="title mt--15" style="font-size: clamp(26px, 3vw, 36px); font-weight: 800; color: #071126;">
                        শিক্ষার্থীদের <span style="color: #ff7a00;">মূল্যবান রিভিউ</span>
                    </h2>
                    <p style="color: #64748b; font-size: 16px;">ইমু স্যারের ক্লাস এবং স্পার্ক একাডেমির দিকনির্দেশনা নিয়ে শিক্ষার্থীদের অভিজ্ঞতা ও মতামত</p>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($testimonials as $review)
                    <div class="col-lg-4 col-md-6">
                        <div class="spark-review-card">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex text-warning" style="font-size: 14px; gap: 2px;">
                                    @for($i = 0; $i < ($review->rating ?? 5); $i++)
                                        <i class="fas fa-star"></i>
                                    @endfor
                                </div>
                                <span class="badge bg-light text-muted" style="font-size: 11px; font-weight: 600;"><i class="feather-check text-success"></i> ভেরিফাইড শিক্ষার্থী</span>
                            </div>
                            <p style="color: #334155; font-size: 15px; line-height: 1.7; font-style: italic; margin-bottom: 20px;">
                                "{{ $review->description }}"
                            </p>
                            <div class="d-flex align-items-center gap-3 pt-3 border-top">
                                <div style="width: 44px; height: 44px; border-radius: 50%; background: #071126; color: #ff7a00; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 17px;">
                                    {{ mb_substr($review->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 style="font-weight: 700; color: #071126; margin-bottom: 2px; font-size: 16px;">{{ $review->name }}</h6>
                                    <span style="color: #94a3b8; font-size: 13px;">{{ $review->designation }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </section>
    <!-- End Student Reviews -->

    <!-- Swiper Initialization Script for Hero Slider -->
    @push('frontend_script')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof Swiper !== 'undefined') {
                    new Swiper('.hero-banner-swiper', {
                        slidesPerView: 1,
                        loop: true,
                        speed: 800,
                        autoplay: {
                            delay: 5500,
                            disableOnInteraction: false,
                        },
                        navigation: {
                            nextEl: '.hero-slider-next',
                            prevEl: '.hero-slider-prev',
                        },
                        pagination: {
                            el: '.hero-swiper-pagination',
                            clickable: true,
                        },
                    });
                }
            });
        </script>
    @endpush
@endsection
