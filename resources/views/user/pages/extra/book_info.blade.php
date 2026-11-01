@extends('user.pages.test_master')

@push('test_style')
<style>
    .spark-book-hero {
        background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
        padding: 55px 0 65px;
        color: #ffffff;
    }
    .spark-book-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 24px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(7, 17, 38, 0.05);
    }
    .spark-sample-modal-body {
        max-height: 70vh;
        overflow-y: auto;
        padding: 30px;
        background: #f8fafc;
        border-radius: 12px;
        font-size: 16px;
        line-height: 1.8;
        color: #334155;
    }
    .spark-book-info-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        height: 50px !important;
        min-height: 50px !important;
        line-height: 1 !important;
        padding: 0 28px !important;
        border-radius: 9999px !important;
        font-family: 'Hind Siliguri', sans-serif !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-sizing: border-box;
        cursor: pointer;
        gap: 8px;
    }
    .spark-book-info-btn span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }
    .spark-book-info-outline {
        background: #ffffff !important;
        border: 2px solid #ff7a00 !important;
        color: #ff7a00 !important;
    }
    .spark-book-info-outline:hover {
        background: #ff7a00 !important;
        color: #ffffff !important;
        border-color: #ff7a00 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(255, 122, 0, 0.3);
    }
    .spark-book-info-primary {
        background: linear-gradient(135deg, #03c6bb 0%, #6366f1 100%) !important;
        border: 2px solid transparent !important;
        color: #ffffff !important;
        box-shadow: 0 4px 14px rgba(3, 198, 187, 0.3);
    }
    .spark-book-info-primary:hover {
        background: linear-gradient(135deg, #02b3a9 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(3, 198, 187, 0.45);
    }

    /* Dark Theme Visibility Overrides for Book Info */
    html.dark-theme .bg-color-white {
        background-color: #0b1120 !important;
    }
    html.dark-theme .spark-book-card {
        background: #131f37 !important;
        border-color: #243247 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }
    html.dark-theme .spark-book-card h2,
    html.dark-theme .spark-book-card h4 {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-book-card span[style*="font-size: 36px"],
    html.dark-theme .spark-book-card label {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-book-card .border-top {
        border-color: #243247 !important;
    }
    html.dark-theme .spark-book-card ul li,
    html.dark-theme .spark-book-card div[style*="line-height: 1.8"] {
        color: #cbd5e1 !important;
    }
    html.dark-theme #books_quantity {
        background: #0f172a !important;
        color: #f8fafc !important;
        border-color: #334155 !important;
    }
    html.dark-theme .spark-book-info-outline {
        background: #1e293b !important;
        color: #ff7a00 !important;
        border-color: #ff7a00 !important;
    }
    html.dark-theme .spark-book-info-outline:hover {
        background: #ff7a00 !important;
        color: #ffffff !important;
        border-color: #ff7a00 !important;
        box-shadow: 0 6px 18px rgba(255, 122, 0, 0.4) !important;
    }
    html.dark-theme .spark-sample-modal-body {
        background: #0f172a !important;
        color: #cbd5e1 !important;
    }
    html.dark-theme .modal-content {
        background: #131f37 !important;
        border-color: #243247 !important;
    }
</style>
@endpush

@section('content')
@php
    $bookImage = $book_info->image ?? 'images/bcs_prep.jpg';
    $bookName = $book_info->name ?? 'স্পার্ক একাডেমি এইচএসসি পদার্থবিজ্ঞান সহায়িকা';
    $bookPrice = $book_info->price ?? 450;
@endphp

<!-- Start Hero / Breadcrumb -->
<div class="spark-book-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">হোম</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li><a href="{{ route('user.books') }}" style="color: #94a3b8; text-decoration: none;">স্পার্ক একাডেমির বই</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #ff7a00; font-weight: 600;">{{ $bookName }}</li>
            </ul>
        </nav>
        <span class="badge mb-2 px-3 py-2" style="background: rgba(255, 122, 0, 0.16); color: #ff7a00; border: 1px solid rgba(255, 122, 0, 0.35); font-size: 13px; font-weight: 700; border-radius: 9999px;">
            <i class="feather-bookmark"></i> ইমু স্যারের স্পেশাল বই
        </span>
        <h1 class="text-white mb-2" style="font-size: clamp(26px, 3.5vw, 38px); font-weight: 800;">
            {{ $bookName }}
        </h1>
    </div>
</div>

<!-- Main Book Details & Sample Preview -->
<div class="rbt-section-gap bg-color-white">
    <div class="container">
        <div class="spark-book-card">
            <div class="row align-items-center g-5">
                <!-- Book Cover Image -->
                <div class="col-lg-4 col-md-5 text-center">
                    <div class="position-relative d-inline-block">
                        <img src="{{ asset($bookImage) }}" class="img-fluid rounded-4 shadow-lg" alt="{{ $bookName }}" style="max-height: 440px; object-fit: cover; border: 3px solid #e2e8f0;">
                    </div>
                </div>

                <!-- Book Information & Purchase Form -->
                <div class="col-lg-8 col-md-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="d-flex text-warning" style="font-size: 15px; gap: 2px;">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <span class="text-muted small font-weight-bold">(৪.৯/৫ স্টার রেটিং)</span>
                    </div>

                    <h2 style="font-weight: 800; color: #071126; font-size: clamp(24px, 2.5vw, 32px); margin-bottom: 8px;">
                        {{ $bookName }}
                    </h2>
                    <p style="color: #ff7a00; font-weight: 700; font-size: 16px; margin-bottom: 16px;">
                        লেখক: Engr. Iftekhar Hossain Emu
                    </p>

                    <div class="d-flex align-items-baseline gap-3 mb-4">
                        <span style="font-size: 36px; font-weight: 800; color: #071126; font-family: var(--font-heading);">
                            ৳{{ number_format($bookPrice, 2) }}
                        </span>
                        <span class="badge bg-success text-white" style="font-size: 12px; font-weight: 700;">
                            স্টকে আছে (ইন স্টক)
                        </span>
                    </div>

                    <!-- Action Buttons: "আর একটু পড়ে দেখুন" & "Buy Now" (Client Request Page 5) -->
                    <form method="get" action="{{ route('user.checkout') }}" class="mb-4">
                        <input type="hidden" name="productID" value="{{ $book_info->id }}">
                        
                        <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                            <label style="font-weight: 700; color: #071126; font-size: 15px;">পরিমাণ:</label>
                            <input type="number" name="quantity" id="books_quantity" value="1" min="1" max="10" class="form-control text-center" style="width: 80px; height: 48px; font-weight: 700; border-radius: 10px; border: 1.5px solid #cbd5e1;">
                        </div>

                        <div class="d-flex flex-wrap gap-3 align-items-center">
                            <!-- "আর একটু পড়ে দেখুন" Button -->
                            <button type="button" class="spark-book-info-btn spark-book-info-outline" data-bs-toggle="modal" data-bs-target="#bookSampleModal">
                                <i class="feather-book-open"></i> <span>আর একটু পড়ে দেখুন</span>
                            </button>

                            <!-- Buy Now Button -->
                            <button type="submit" class="spark-book-info-btn spark-book-info-primary">
                                <i class="feather-shopping-bag"></i> <span>এখনই অর্ডার করুন</span>
                            </button>
                        </div>
                    </form>

                    <!-- Features Checklist -->
                    <div class="pt-3 border-top">
                        <ul class="d-flex flex-column gap-2 p-0 m-0" style="list-style: none; font-size: 14.5px; color: #475569;">
                            <li><i class="feather-check-circle text-success me-2"></i> এইচএসসি ও অ্যাডমিশনের পূর্ণাঙ্গ থিওরি ও গাণিতিক সমাধান</li>
                            <li><i class="feather-check-circle text-success me-2"></i> প্রতিটি অধ্যায়ের শর্টকাট টিপস এবং বিগত বছরের বোর্ড প্রশ্ন বিশ্লেষণ</li>
                            <li><i class="feather-check-circle text-success me-2"></i> হোম ডেলিভারি: বিকাশ, নগদ ও ক্যাশ অন ডেলিভারি প্রযোজ্য</li>
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Detailed Description Tab -->
            <div class="row mt-5 pt-4 border-top">
                <div class="col-12">
                    <h4 style="font-weight: 800; color: #071126; margin-bottom: 16px;">বইয়ের বিস্তারিত বিষয়বস্তু:</h4>
                    <div style="font-size: 16px; line-height: 1.8; color: #475569;">
                        {!! $book_info->description ?? 'এই বইটি এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের পদার্থবিজ্ঞান ১ম পত্রের ভীতি দূর করে সর্বোচ্চ প্রস্তুতি নিশ্চিত করার জন্য বিশেষ ফর্মুলা, চিত্রভিত্তিক ব্যাখ্যা ও বোর্ড স্ট্যান্ডার্ড সৃজনশীল প্রশ্ন দিয়ে সাজানো হয়েছে।' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- "আর একটু পড়ে দেখুন" Interactive Sample Reader Modal (Client Request Page 5) -->
<div class="modal fade" id="bookSampleModal" tabindex="-1" aria-labelledby="bookSampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius: 20px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%); color: #ffffff; padding: 20px 28px;">
                <div class="d-flex align-items-center gap-2">
                    <i class="feather-book-open text-warning" style="font-size: 22px;"></i>
                    <h5 class="modal-title text-white mb-0" id="bookSampleModalLabel" style="font-weight: 800;">
                        আর একটু পড়ে দেখুন — {{ $bookName }}
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" style="background: #f8fafc;">
                <div class="spark-sample-modal-body">
                    <div class="text-center mb-4 pb-3 border-bottom">
                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2" style="font-weight: 800;">নমুনা পৃষ্ঠা / Sample Excerpt</span>
                        <h4 style="font-weight: 800; color: #071126;">অধ্যায় ১: ভৌতজগত ও পরিমাপ</h4>
                        <small class="text-muted">লেখক: Engr. Iftekhar Hossain Emu | স্পার্ক একাডেমি</small>
                    </div>

                    <h5 style="color: #071126; font-weight: 700;">১.১ পদার্থবিজ্ঞানের পরিসর ও বিস্ময়:</h5>
                    <p>
                        পদার্থবিজ্ঞান শুধুমাত্র সমীকরণ ও সূত্রের সমষ্টি নয়; এটি মহাবিশ্বের মূল নিয়মাবলি বোঝার এক আনন্দময় যাত্রা। একটি ক্ষুদ্রাতিক্ষুদ্র পরমাণু থেকে শুরু করে অসীম গ্যালাক্সির গতিপ্রকৃতি—সবকিছুই পদার্থবিজ্ঞানের সুনির্দিষ্ট নীতির অধীনে পরিচালিত হয়।
                    </p>

                    <h5 style="color: #071126; font-weight: 700; margin-top: 24px;">১.২ এককের আন্তর্জাতিক পদ্ধতি (SI Unit) ও মাত্রা সমীকরণ:</h5>
                    <p>
                        যেকোনো ভৌত রাশিকে নির্ভুলভাবে প্রকাশ করার জন্য আদর্শ মান প্রয়োজন। মূল সাতটি মৌলিক রাশির সমন্বয়ে গঠিত হয় লব্ধ রাশি। মাত্রা সমীকরণের সাহায্যে যেকোনো সূত্রের সঠিকতা মুহূর্তেই যাচাই করা যায়।
                    </p>
                    <div class="p-3 my-3 rounded-3" style="background: #ffffff; border-left: 4px solid #ff7a00; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
                        <strong>💡 ইমু স্যারের স্পেশাল টেকনিক:</strong> মাত্রা সমীকরণের বাম ও ডান পাশের ঘাত সমান হওয়া আবশ্যক (Principle of Homogeneity)। ভর্তি পরীক্ষায় দ্রুত অপশন টেস্ট করার এটি অন্যতম সেরা হাতিয়ার!
                    </div>

                    <h5 style="color: #071126; font-weight: 700; margin-top: 24px;">১.৩ পরিমাপে ত্রুটি ও সাবধানতা:</h5>
                    <p>
                        পরিমাপের ক্ষেত্রে চরম ত্রুটি, আপেক্ষিক ত্রুটি এবং শতকরা ত্রুটি নির্ণয় বোর্ড পরীক্ষার প্রতিটি গাণিতিক সমস্যার মূল অংশ। সূত্রের ঘাতগুলো শতকরা ত্রুটির ক্ষেত্রে গুণক হিসেবে সরাসরি যোগ হয়।
                    </p>

                    <div class="text-center mt-4 pt-3 border-top">
                        <p class="text-muted mb-3">বইটির সম্পূর্ণ ১০টি অধ্যায়ের বিস্তারিত নোট ও শর্টকাট ফর্মুলা পেতে এখনই অর্ডার করুন।</p>
                        <a href="{{ route('user.checkout', ['productID' => $book_info->id, 'quantity' => 1]) }}" class="spark-book-info-btn spark-book-info-primary px-4">
                            <span>বইটি হোম ডেলিভারিতে অর্ডার করুন</span> <i class="feather-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #ffffff;">
                <button type="button" class="rbt-btn btn-border radius-round btn-sm" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>
@endsection
