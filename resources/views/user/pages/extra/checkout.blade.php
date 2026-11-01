@extends('user.pages.test_master')

@push('test_style')
<style>
    .spark-checkout-section {
        padding: 50px 0 80px;
        background-color: #f8fafc;
        min-height: 80vh;
    }
    .spark-checkout-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 6px 20px rgba(7, 17, 38, 0.05);
        margin-bottom: 24px;
    }
    .spark-checkout-label {
        font-weight: 700;
        color: #071126;
        margin-bottom: 8px;
        font-size: 15px;
    }
    .spark-checkout-input {
        width: 100%;
        height: 52px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 18px;
        font-size: 15px;
        transition: all 0.25s ease;
        background: #ffffff;
    }
    .spark-checkout-input:focus {
        border-color: #ff7a00;
        outline: none;
        box-shadow: 0 0 0 3px rgba(255, 122, 0, 0.2);
    }
    .payment-option-label {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        cursor: pointer;
        margin-bottom: 12px;
        transition: all 0.25s ease;
        background: #ffffff;
    }
    .payment-option-label:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .payment-option-label input[type="radio"]:checked ~ .payment-option-content {
        color: #ff7a00;
        font-weight: 700;
    }
    .payment-option-label input[type="radio"]:checked {
        accent-color: #ff7a00;
    }
</style>
@endpush

@section('content')
@php
    $product_info = DB::table('books')->where('id', $pid)->first();
    $userID = Cookie::get('user_id');
    $user_info = $userID ? DB::table('users')->where('id', $userID)->first() : null;
    $bookPrice = $product_info->price ?? 450;
    $bookName = $product_info->name ?? 'ইমু স্যারের স্পেশাল পদার্থবিজ্ঞান বই';
@endphp

<!-- Start Hero Breadcrumb -->
<div class="student-hero" style="background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%); padding: 45px 0 50px;">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">হোম</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li><a href="{{ route('user.books') }}" style="color: #94a3b8; text-decoration: none;">স্পার্ক একাডেমির বই</a></li>
                <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                <li style="color: #ff7a00; font-weight: 600;">বই অর্ডার চেকআউট</li>
            </ul>
        </nav>
        <h1 class="text-white mb-1" style="font-size: clamp(24px, 3vw, 36px); font-weight: 800;">
            বই ক্রয়ের তথ্য ও চেকআউট
        </h1>
        <p style="color: #cbd5e1; font-size: 15px; margin-bottom: 0;">নিচের সহজ ফর্মটি পূরণ করে অর্ডারটি নিশ্চিত করুন।</p>
    </div>
</div>

<div class="spark-checkout-section">
    <div class="container">
        <form method="post" action="{{ url('/buybook/pay') }}">
            @csrf
            <input type="hidden" name="pid" value="{{ optional($product_info)->id }}">
            <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
            <input type="hidden" name="price" value="{{ $bookPrice }}">

            <div class="row g-4">
                <!-- Delivery & Customer Information (Client Specified: নাম, মোবাইল নাম্বার, ঠিকানা) -->
                <div class="col-lg-7">
                    <div class="spark-checkout-card">
                        <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(255, 122, 0, 0.12); color: #ff7a00; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="feather-user-check"></i>
                            </div>
                            <div>
                                <h4 style="font-weight: 800; color: #071126; margin: 0; font-size: 20px;">ডেলিভারি তথ্য</h4>
                                <small class="text-muted">বইটি আপনার ঠিকানায় পৌঁছানোর জন্য তথ্য দিন</small>
                            </div>
                        </div>

                        <!-- 1. নাম -->
                        <div class="mb-4">
                            <label class="spark-checkout-label">
                                নামঃ <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', optional($user_info)->name) }}" class="spark-checkout-input" placeholder="আপনার পুরো নাম লিখুন" required>
                            @error('name')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 2. মোবাইল নাম্বার -->
                        <div class="mb-4">
                            <label class="spark-checkout-label">
                                মোবাইল নাম্বারঃ <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', optional($user_info)->phone) }}" class="spark-checkout-input" placeholder="০১XXXXXXXXX" required>
                            @error('phone')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- 3. ঠিকানা -->
                        <div class="mb-3">
                            <label class="spark-checkout-label">
                                ঠিকানাঃ <span class="text-danger">*</span>
                            </label>
                            <textarea name="address" rows="3" class="spark-checkout-input" style="height: auto; padding: 12px 18px;" placeholder="আপনার পূর্ণাঙ্গ ঠিকানা (বাড়ি/রোড, এলাকা, থানা, জেলা) লিখুন" required>{{ old('address', optional($user_info)->address) }}</textarea>
                            @error('address')
                                <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- Order Summary & Payment Method (Client Specified: বিকাশ, নগদ, ক্যাশ অন ডেলিভারি) -->
                <div class="col-lg-5">
                    <div class="spark-checkout-card">
                        <div class="d-flex align-items-center gap-3 pb-3 mb-4 border-bottom">
                            <div style="width: 44px; height: 44px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                <i class="feather-shopping-bag"></i>
                            </div>
                            <div>
                                <h4 style="font-weight: 800; color: #071126; margin: 0; font-size: 20px;">অর্ডার সারসংক্ষেপ</h4>
                                <small class="text-muted">বইয়ের বিবরণ ও পেমেন্ট অপশন</small>
                            </div>
                        </div>

                        <!-- Book Item Summary -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            <div>
                                <h6 class="mb-1" style="font-weight: 700; color: #071126; font-size: 15px;">{{ $bookName }}</h6>
                                <small class="text-muted">পরিমাণ: {{ $quantity ?? 1 }}টি</small>
                            </div>
                            <div style="font-weight: 800; color: #071126; font-size: 16px;">
                                ৳{{ number_format($bookPrice * ($quantity ?? 1), 2) }}
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between py-2 border-bottom mb-4">
                            <span style="font-weight: 700; color: #071126; font-size: 17px;">সর্বমোট মূল্য:</span>
                            <span style="font-weight: 800; color: #ff7a00; font-size: 24px; font-family: var(--font-heading);">
                                ৳{{ number_format($bookPrice * ($quantity ?? 1), 2) }}
                            </span>
                        </div>

                        <!-- 3 Payment Options Requested by Client -->
                        <h6 style="font-weight: 800; color: #071126; margin-bottom: 14px; font-size: 15px;">
                            পেমেন্ট মেথড নির্বাচন করুন: <span class="text-danger">*</span>
                        </h6>

                        <!-- Option 1: বিকাশ -->
                        <label class="payment-option-label">
                            <input type="radio" name="radio" value="bkash" required checked>
                            <div class="payment-option-content d-flex align-items-center justify-content-between w-100">
                                <span><i class="feather-smartphone text-danger me-2"></i> বিকাশ (bKash)</span>
                                <span class="badge bg-light text-muted">সেন্ড মানি</span>
                            </div>
                        </label>

                        <!-- Option 2: নগদ -->
                        <label class="payment-option-label">
                            <input type="radio" name="radio" value="nagad" required>
                            <div class="payment-option-content d-flex align-items-center justify-content-between w-100">
                                <span><i class="feather-dollar-sign text-warning me-2"></i> নগদ (Nagad)</span>
                                <span class="badge bg-light text-muted">সেন্ড মানি</span>
                            </div>
                        </label>

                        <!-- Option 3: ক্যাশ অন ডেলিভারি -->
                        <label class="payment-option-label">
                            <input type="radio" name="radio" value="cash" required>
                            <div class="payment-option-content d-flex align-items-center justify-content-between w-100">
                                <span><i class="feather-truck text-success me-2"></i> ক্যাশ অন ডেলিভারি (Cash on Delivery)</span>
                                <span class="badge bg-light text-muted">হাতে পেয়ে মূল্য পরিশোধ</span>
                            </div>
                        </label>

                        <button type="submit" class="rbt-btn btn-gradient w-100 radius-round py-3 mt-3 text-center" style="font-weight: 700; font-size: 16px;">
                            <span>অর্ডার কনফার্ম করুন</span> <i class="feather-arrow-right ms-1"></i>
                        </button>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
