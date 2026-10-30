@extends('user.pages.test_master')

@push('test_style')
<style>
    .spark-about-hero {
        background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
        padding: 65px 0 75px;
        color: #ffffff;
    }
    .spark-about-pillar {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 30px 24px;
        height: 100%;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    }
    .spark-about-pillar:hover {
        transform: translateY(-6px);
        border-color: #ff7a00;
        box-shadow: 0 16px 30px rgba(7, 17, 38, 0.08);
    }
    .spark-expert-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(7, 17, 38, 0.06);
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
        justify-content: center;
        gap: 8px;
        height: 44px;
        line-height: 1;
        padding: 0 22px;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 14.5px;
        color: #fff !important;
        text-decoration: none;
        transition: all 0.25s ease;
    }
    .spark-social-fb {
        background: #1877f2;
    }
    .spark-social-fb:hover {
        background: #0d65d9;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(24, 119, 242, 0.4);
    }
    .spark-social-yt {
        background: #ff0000;
    }
    .spark-social-yt:hover {
        background: #cc0000;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(255, 0, 0, 0.4);
    }
    .spark-address-card {
        background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
        color: #ffffff;
        border-radius: 20px;
        padding: 32px 36px;
        box-shadow: 0 10px 30px rgba(7, 17, 38, 0.15);
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

    /* Dark Theme Visibility Overrides for About Us */
    html.dark-theme .bg-color-white {
        background-color: #0b1120 !important;
    }
    html.dark-theme .spark-about-pillar {
        background: #131f37 !important;
        border-color: #243247 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }
    html.dark-theme .spark-about-pillar:hover {
        border-color: #ff7a00 !important;
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.65), 0 0 18px rgba(255, 122, 0, 0.22) !important;
    }
    html.dark-theme .spark-about-pillar h5 {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-about-pillar p {
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
    html.dark-theme .spark-expert-card h3,
    html.dark-theme .spark-expert-card h4 {
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
    html.dark-theme .spark-review-card {
        background: #131f37 !important;
        border-color: #243247 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }
    html.dark-theme .spark-review-card:hover {
        border-color: #ff7a00 !important;
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.65), 0 0 18px rgba(255, 122, 0, 0.22) !important;
    }
    html.dark-theme .spark-review-card h6 {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-review-card p {
        color: #cbd5e1 !important;
    }
    html.dark-theme .spark-review-card small {
        color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')
@php
    $aboutInfo = $aboutInfo ?? DB::table('about_infos')->first();

    $aboutDesc = !empty($aboutInfo->aboutDes) 
        ? $aboutInfo->aboutDes 
        : 'স্পার্ক একাডেমি ইমু স্যার এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য একটি অনলাইন ও অফলাইন কার্যক্রম সম্পন্নকারী প্রতিষ্ঠান। স্পার্ক একাডেমির অনলাইন কোর্সের পাশাপাশি আছে নিজস্ব বই ।';

    $p1_title = !empty($aboutInfo->pillar1_title) ? $aboutInfo->pillar1_title : 'অনলাইন ও অফলাইন কার্যক্রম';
    $p1_desc = !empty($aboutInfo->pillar1_desc) ? $aboutInfo->pillar1_desc : 'এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য আধুনিক স্টুডিও রেকর্ডেড ক্লাস, লাইভ ইন্টারঅ্যাকশন এবং চকবাজারের কেন্দ্রে অফলাইন নিবিড় কেয়ার।';

    $p2_title = !empty($aboutInfo->pillar2_title) ? $aboutInfo->pillar2_title : 'স্পার্ক একাডেমির নিজস্ব বই';
    $p2_desc = !empty($aboutInfo->pillar2_desc) ? $aboutInfo->pillar2_desc : 'ইমু স্যারের ১০+ বছরের শিক্ষকতার অভিজ্ঞতায় রচিত বিশেষ পদার্থবিজ্ঞান সহায়িকা বই। থিওরি, শর্টকাট টেকনিক ও বোর্ড প্রশ্ন সমাধানের নির্ভরযোগ্য সংকলন।';

    $p3_title = !empty($aboutInfo->pillar3_title) ? $aboutInfo->pillar3_title : 'MCQ Game Arena';
    $p3_desc = !empty($aboutInfo->pillar3_desc) ? $aboutInfo->pillar3_desc : 'টানা ৩ বার কারেক্ট করার গ্যামিফাইড প্র্যাকটিস সিস্টেম। ভুল করা প্রশ্নের স্পেশাল রিট্রাই মোড এবং স্ট্রিক পয়েন্টের মাধ্যমে ১০০% চ্যাপ্টার মাস্টারি।';

    $expertName = !empty($aboutInfo->expert_name) ? $aboutInfo->expert_name : 'Engr. Iftekhar Hossain Emu';
    $expertRole = !empty($aboutInfo->expert_role) ? $aboutInfo->expert_role : 'Physics Mentor';
    $expertBadge = !empty($aboutInfo->expert_badge) ? $aboutInfo->expert_badge : 'প্রধান মেন্টর পরিচিতি';
    $expertBio = !empty($aboutInfo->expert_bio) ? $aboutInfo->expert_bio : 'বিগত ১০ বছরের বেশি সময় ধরে হাজারো শিক্ষার্থীকে ফিজিক্সের জটিল বিষয়গুলো সহজ ও সাবলীলভাবে শিখিয়ে এইচএসসি ও প্রকৌশল বিশ্ববিদ্যালয় ভর্তি পরীক্ষায় ঈর্ষণীয় সাফল্য অর্জনে পথপ্রদর্শন করে আসছেন। একাডেমিক ও এডমিশন একসাথে প্যারালাল প্রস্তুতির মাধ্যমে শিক্ষার্থীদের সর্বোচ্চ স্তরে পৌঁছে দেওয়াই স্পার্ক একাডেমির অঙ্গীকার।';

    $rawCredentials = !empty($aboutInfo->expert_credentials) 
        ? $aboutInfo->expert_credentials 
        : "B.Sc. (CUET)\nMBA (CU)\nEx-Chittagong College\nFounder of Modulus Academic & Admission Care\nPhysics Teacher (10+ years experience)";
    $credentialsList = array_filter(array_map('trim', preg_split('/[\r\n|]+/', $rawCredentials)));

    $expertImg = !empty($aboutInfo->expert_image) ? asset($aboutInfo->expert_image) : asset('images/emu_sir.jpg');
    $fbLink = !empty($aboutInfo->facebook_link) ? $aboutInfo->facebook_link : 'https://facebook.com/sparkacademyofficial';
    $ytLink = !empty($aboutInfo->youtube_link) ? $aboutInfo->youtube_link : 'https://youtube.com/@sparkacademyofficial';

    $offlineAddress = !empty($aboutInfo->offline_address) ? $aboutInfo->offline_address : 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম';
    $offlinePhone = !empty($aboutInfo->offline_phone) ? $aboutInfo->offline_phone : '০১৭৬৮২৪০৩০৩';
    $cleanPhone = preg_replace('/[^0-9]/', '', $offlinePhone);
    if (empty($cleanPhone)) {
        $cleanPhone = '01768240303';
    }
    $waPhone = (strpos($cleanPhone, '88') === 0) ? $cleanPhone : ('88' . ltrim($cleanPhone, '0'));

    try {
        $spark_reviews = DB::table('testimonials')->where('active', 1)->get();
    } catch (\Throwable $e) {
        $spark_reviews = collect();
    }
    if ($spark_reviews->isEmpty()) {
        $spark_reviews = collect([
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

<!-- Start Hero / Breadcrumb Area -->
<div class="spark-about-hero">
    <div class="container text-center">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center justify-content-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">হোম</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #ff7a00; font-weight: 600;">আমাদের সম্পর্কে জানুন</li>
            </ul>
        </nav>
        <span class="badge mb-3 px-3 py-2" style="background: rgba(255, 122, 0, 0.16); color: #ff7a00; border: 1px solid rgba(255, 122, 0, 0.35); font-size: 13px; font-weight: 700; border-radius: 9999px;">
            <i class="feather-info"></i> SPARK ACADEMY • ইমু স্যার
        </span>
        <h1 class="text-white mb-3" style="font-size: clamp(30px, 4vw, 44px); font-weight: 800;">
            আমাদের সম্পর্কে <span style="color: #ff7a00;">জানুন</span>
        </h1>
        <div class="mx-auto mb-0 spark-about-desc-content" style="max-width: 780px; font-size: 18px; color: #cbd5e1; line-height: 1.8; font-weight: 500;">
            {!! $aboutDesc !!}
        </div>
    </div>
</div>
<!-- End Hero Area -->

<!-- Start Core Pillars Section -->
<div class="rbt-section-gapTop bg-color-white" style="padding-bottom: 30px;">
    <div class="container">
        <div class="row g-4 justify-content-center mb-5">
            <div class="col-lg-4 col-md-6 col-12">
                <div class="spark-about-pillar">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle" style="width: 56px; height: 56px; background: rgba(255, 122, 0, 0.12); color: #ff7a00; font-size: 24px;">
                        <i class="feather-video"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #071126; margin-bottom: 12px; font-size: 19px;">{{ $p1_title }}</h5>
                    <p style="color: #64748b; font-size: 14.5px; line-height: 1.7; margin: 0;">
                        {{ $p1_desc }}
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="spark-about-pillar">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle" style="width: 56px; height: 56px; background: rgba(79, 70, 229, 0.12); color: #4f46e5; font-size: 24px;">
                        <i class="feather-book-open"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #071126; margin-bottom: 12px; font-size: 19px;">{{ $p2_title }}</h5>
                    <p style="color: #64748b; font-size: 14.5px; line-height: 1.7; margin: 0;">
                        {{ $p2_desc }}
                    </p>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-12">
                <div class="spark-about-pillar">
                    <div class="d-inline-flex align-items-center justify-content-center mb-3 rounded-circle" style="width: 56px; height: 56px; background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 24px;">
                        <i class="feather-zap"></i>
                    </div>
                    <h5 style="font-weight: 700; color: #071126; margin-bottom: 12px; font-size: 19px;">{{ $p3_title }}</h5>
                    <p style="color: #64748b; font-size: 14.5px; line-height: 1.7; margin: 0;">
                        {{ $p3_desc }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Meet Our Expert Section (Client PDF Page 3) -->
        <div class="row align-items-center justify-content-center mb-5">
            <div class="col-lg-11 col-12">
                <div class="spark-expert-card">
                    <div class="row align-items-center g-4">
                        <div class="col-lg-4 text-center">
                            <img src="{{ $expertImg }}" class="spark-expert-avatar" alt="{{ $expertName }}">
                            <h4 class="mt-3 mb-1" style="font-weight: 800; color: #071126; font-size: 22px;">{{ $expertName }}</h4>
                            <span style="color: #ff7a00; font-weight: 700; font-size: 15px;">{{ $expertRole }}</span>
                        </div>
                        <div class="col-lg-8">
                            <span class="subtitle" style="color: #ff7a00; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 0.08em; background: rgba(255, 122, 0, 0.1); padding: 4px 14px; border-radius: 9999px;">
                                {{ $expertBadge }}
                            </span>
                            <h3 class="mt-2 mb-3" style="font-weight: 800; color: #071126; font-size: clamp(22px, 2.5vw, 28px);">
                                Meet Our Expert
                            </h3>
                            <div class="d-flex flex-wrap mb-3">
                                @foreach($credentialsList as $cred)
                                    <span class="spark-credential-badge"><i class="feather-check-circle text-success"></i> {{ $cred }}</span>
                                @endforeach
                            </div>
                            <p style="color: #64748b; font-size: 15px; line-height: 1.7; margin-bottom: 22px;">
                                {{ $expertBio }}
                            </p>
                            
                            <!-- Social Channels (Client Request Page 3: ফেসবুক ও ইউটিউব এর লিঙ্ক থাকবে) -->
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span style="font-weight: 700; color: #071126; font-size: 14px; margin-right: 6px;">সোশ্যাল মিডিয়ায় যুক্ত থাকুন:</span>
                                @if(!empty($fbLink))
                                <a href="{{ $fbLink }}" target="_blank" rel="noopener" class="spark-social-btn spark-social-fb">
                                    <i class="fab fa-facebook-f"></i> <span>ফেসবুক পেইজ</span>
                                </a>
                                @endif
                                @if(!empty($ytLink))
                                <a href="{{ $ytLink }}" target="_blank" rel="noopener" class="spark-social-btn spark-social-yt">
                                    <i class="fab fa-youtube"></i> <span>ইউটিউব চ্যানেল</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Offline Center Address Banner (Client PDF Page 3) -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-11 col-12">
                <div class="spark-address-card d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 54px; height: 54px; border-radius: 14px; background: rgba(255, 122, 0, 0.2); color: #ff7a00; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                            <i class="feather-map-pin"></i>
                        </div>
                        <div>
                            <h5 class="text-white mb-1" style="font-weight: 700; font-size: 19px;">অফলাইন ঠিকানাঃ</h5>
                            <p class="mb-0 text-white-50" style="font-size: 16px;">
                                <strong>{{ $offlineAddress }}</strong>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        @if(!empty($waPhone))
                        <a href="https://wa.me/{{ $waPhone }}" target="_blank" class="btn btn-outline-light rounded-pill px-4 py-2 font-weight-bold d-inline-flex align-items-center gap-2" style="font-size: 14.5px;">
                            <i class="feather-message-circle"></i> হোয়াটসঅ্যাপ
                        </a>
                        @endif
                        @if(!empty($offlinePhone))
                        <a href="tel:{{ $cleanPhone }}" class="spark-phone-btn">
                            <i class="feather-phone"></i> <span>{{ $offlinePhone }}</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Reviews Section (Client PDF Page 3: রিভিউ কয়েকটা আমি ছবি তুলে দিলাম) -->
        <div class="row justify-content-center text-center mt-5 mb-4">
            <div class="col-lg-8">
                <span class="subtitle" style="color: #ff7a00; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; background: rgba(255, 122, 0, 0.1); padding: 5px 16px; border-radius: 9999px;">
                    শিক্ষার্থীদের অভিজ্ঞতা
                </span>
                <h3 class="mt-2" style="font-weight: 800; color: #071126; font-size: clamp(24px, 2.8vw, 32px);">
                    শিক্ষার্থীদের <span style="color: #ff7a00;">মূল্যবান রিভিউ</span>
                </h3>
                <p style="color: #64748b; font-size: 16px;">ইমু স্যারের ক্লাস এবং স্পার্ক একাডেমির দিকনির্দেশনা নিয়ে শিক্ষার্থীদের অভিজ্ঞতা ও মতামত</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center mb-5">
            @foreach($spark_reviews as $review)
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="spark-review-card">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #ff7a00 0%, #ff9e42 100%); color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                {{ mb_substr($review->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0" style="font-weight: 700; color: #071126; font-size: 16px;">{{ $review->name }}</h6>
                                <small class="text-muted" style="font-size: 13px;">{{ $review->designation }}</small>
                            </div>
                        </div>
                        <div class="mb-2" style="color: #f59e0b; font-size: 14px;">
                            @for($i = 0; $i < ($review->rating ?? 5); $i++)
                                <i class="fas fa-star"></i>
                            @endfor
                        </div>
                        <p style="color: #475569; font-size: 14.5px; line-height: 1.7; margin-bottom: 0;">
                            "{{ $review->description }}"
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

@endsection
