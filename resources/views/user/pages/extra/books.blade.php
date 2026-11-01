@extends('user.pages.test_master')

@push('test_style')
<style>
    .spark-books-hero {
        background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
        padding: 55px 0 65px;
        color: #ffffff;
    }
    .spark-book-grid-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 15px rgba(7, 17, 38, 0.04);
    }
    .spark-book-grid-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 30px rgba(7, 17, 38, 0.1);
        border-color: #ff7a00;
    }
    .spark-book-thumb {
        height: 280px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 20px;
    }
    .spark-book-thumb img {
        max-height: 100%;
        width: auto;
        object-fit: contain;
        transition: transform 0.35s ease;
        border-radius: 8px;
    }
    .spark-book-grid-card:hover .spark-book-thumb img {
        transform: scale(1.05);
    }
    .spark-book-btn-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        padding: 0 20px 24px;
        width: 100%;
    }
    .spark-book-btn {
        flex: 1;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        text-align: center !important;
        height: 46px !important;
        min-height: 46px !important;
        line-height: 1 !important;
        padding: 0 12px !important;
        border-radius: 9999px !important;
        font-family: 'Hind Siliguri', sans-serif !important;
        font-size: 15px !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1) !important;
        box-sizing: border-box;
        margin: 0;
        cursor: pointer;
    }
    .spark-book-btn span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
        letter-spacing: 0.2px;
    }
    .spark-book-btn-read {
        background: #ffffff !important;
        border: 1.5px solid #ff7a00 !important;
        color: #ff7a00 !important;
    }
    .spark-book-btn-read:hover {
        background: #ff7a00 !important;
        color: #ffffff !important;
        border-color: #ff7a00 !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(255, 122, 0, 0.3);
    }
    .spark-book-btn-order {
        background: linear-gradient(135deg, #03c6bb 0%, #6366f1 100%) !important;
        border: 1.5px solid transparent !important;
        color: #ffffff !important;
    }
    .spark-book-btn-order:hover {
        background: linear-gradient(135deg, #02b3a9 0%, #4f46e5 100%) !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(3, 198, 187, 0.4);
    }

    /* Dark Theme Visibility Overrides for Books Page */
    html.dark-theme .bg-color-white {
        background-color: #0b1120 !important;
    }
    html.dark-theme .spark-book-grid-card {
        background: #131f37 !important;
        border-color: #243247 !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
    }
    html.dark-theme .spark-book-grid-card:hover {
        border-color: #ff7a00 !important;
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.65), 0 0 18px rgba(255, 122, 0, 0.22) !important;
    }
    html.dark-theme .spark-book-thumb {
        background: #0f172a !important;
    }
    html.dark-theme .spark-book-grid-card h5,
    html.dark-theme .spark-book-grid-card h5 a {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-book-btn-read {
        background: #1e293b !important;
        color: #ff7a00 !important;
        border-color: #ff7a00 !important;
    }
    html.dark-theme .spark-book-btn-read:hover {
        background: #ff7a00 !important;
        color: #ffffff !important;
        border-color: #ff7a00 !important;
        box-shadow: 0 6px 18px rgba(255, 122, 0, 0.4) !important;
    }
    html.dark-theme .badge.bg-light.text-muted {
        background: #1e293b !important;
        color: #94a3b8 !important;
        border: 1px solid #334155 !important;
    }
</style>
@endpush

@section('content')
<!-- Start Hero Banner -->
<div class="spark-books-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">হোম</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #ff7a00; font-weight: 600;">স্পার্ক একাডেমির বই</li>
            </ul>
        </nav>
        <span class="badge mb-2 px-3 py-2" style="background: rgba(255, 122, 0, 0.16); color: #ff7a00; border: 1px solid rgba(255, 122, 0, 0.35); font-size: 13px; font-weight: 700; border-radius: 9999px;">
            <i class="feather-book"></i> নিজস্ব পাঠ্য সহায়িকা
        </span>
        <h1 class="text-white mb-2" style="font-size: clamp(28px, 3.8vw, 40px); font-weight: 800;">
            স্পার্ক একাডেমির বই সমূহ
        </h1>
        <p style="color: #cbd5e1; font-size: 16px; max-width: 650px; margin-bottom: 0;">
            ইমু স্যারের পদার্থবিজ্ঞানের পূর্ণাঙ্গ সহায়ক বই। অনলাইনে অর্ডার করুন এবং ক্যাশ অন ডেলিভারিতে সংগ্রহ করুন।
        </p>
    </div>
</div>

<!-- Books Grid Section -->
<div class="rbt-section-gap bg-color-white">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @forelse($books as $book)
                @if (!isset($book->active) || $book->active == 1)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="spark-book-grid-card">
                            <div>
                                <div class="spark-book-thumb">
                                    <a href="{{ url('/book-details/'.$book->id.'/'.$book->name) }}">
                                        <img src="{{ asset($book->image) }}" class="img-fluid" alt="{{ $book->name }}">
                                    </a>
                                </div>
                                <div class="p-4 text-center">
                                    <span class="badge bg-light text-muted mb-2" style="font-size: 12px; font-weight: 600;">ইমু স্যারের স্পেশাল বই</span>
                                    <h5 style="font-weight: 800; color: #071126; font-size: 18px; margin-bottom: 12px;">
                                        <a href="{{ url('/book-details/'.$book->id.'/'.$book->name) }}" style="color: #071126; text-decoration: none;">
                                            {{ $book->name }}
                                        </a>
                                    </h5>
                                    <div style="font-size: 24px; font-weight: 800; color: #ff7a00; font-family: var(--font-heading);">
                                        ৳{{ number_format($book->price, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="spark-book-btn-wrap">
                                <a href="{{ url('/book-details/'.$book->id.'/'.$book->name) }}" class="spark-book-btn spark-book-btn-read">
                                    <span>আর একটু পড়ুন</span>
                                </a>
                                <a href="{{ route('user.checkout', ['productID' => $book->id, 'quantity' => 1]) }}" class="spark-book-btn spark-book-btn-order">
                                    <span>অর্ডার করুন</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <!-- Fallback book card if table is empty -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="spark-book-grid-card">
                        <div>
                            <div class="spark-book-thumb">
                                <img src="{{ asset('images/logo.png') }}" class="img-fluid" alt="Physics Special Book">
                            </div>
                            <div class="p-4 text-center">
                                <span class="badge bg-light text-muted mb-2" style="font-size: 12px; font-weight: 600;">ইমু স্যারের স্পেশাল বই</span>
                                <h5 style="font-weight: 800; color: #071126; font-size: 18px; margin-bottom: 12px;">
                                    HSC Physics 1st Paper Special Guide
                                </h5>
                                <div style="font-size: 24px; font-weight: 800; color: #ff7a00; font-family: var(--font-heading);">
                                    ৳450.00
                                </div>
                            </div>
                        </div>
                        <div class="spark-book-btn-wrap">
                            <a href="#" class="spark-book-btn spark-book-btn-read">
                                <span>আর একটু পড়ুন</span>
                            </a>
                            <a href="#" class="spark-book-btn spark-book-btn-order">
                                <span>অর্ডার করুন</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
