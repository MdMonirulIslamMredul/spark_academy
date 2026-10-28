@extends('user.pages.test_master')

@section('content')

@php
$userID = Cookie::get('user_id');
$userName = Cookie::get('user_name') ?: 'Student';
$userQ = DB::table('users')->where('id', $userID)->first();

$attendExams = DB::table('results')
    ->join('exams', 'results.examID', 'exams.id')
    ->select('results.*', 'exams.exam_name')
    ->where('results.userID', optional($userQ)->id)
    ->latest()
    ->get();

$courseOrders = DB::table('courses_order')
    ->where('phone', optional($userQ)->phone)
    ->orWhere('email', optional($userQ)->email)
    ->orderBy('id', 'desc')
    ->get(['pid']);
$courseIds = $courseOrders->pluck('pid');
$courses = DB::table('courses')
    ->whereIn('id', $courseIds)
    ->get();
@endphp

<!-- Start Hero / Breadcrumb Area -->
<div class="student-hero" style="padding: 50px 0 60px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 col-12">
                <nav aria-label="breadcrumb">
                    <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                        <li><a href="{{ route('/') }}" style="color: #94a3b8; text-decoration: none;">Home</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li><a href="{{ route('user.myaccount.dashboard') }}" style="color: #94a3b8; text-decoration: none;">Dashboard</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li><a href="{{ route('user.myaccount.profile') }}" style="color: #94a3b8; text-decoration: none;">Profile</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li style="color: #03c6bb; font-weight: 600;">Edit Settings</li>
                    </ul>
                </nav>
                <div class="student-hero-badge">
                    <i class="feather-settings"></i> Student Account
                </div>
                <h1 class="student-hero-title mb-2" style="font-size: clamp(26px, 3.5vw, 38px);">
                    Account Settings
                </h1>
                <p class="student-hero-desc mb-0">
                    Update your profile details, shipping address, or change account password.
                </p>
            </div>
            <div class="col-lg-4 col-12 mt-4 mt-lg-0 text-lg-end">
                <a href="{{ route('user.myaccount.profile') }}" class="rbt-btn btn-border radius-round" style="color: #fff; border-color: rgba(255,255,255,0.25);">
                    <span class="btn-text">Back to Profile</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Area -->

<div class="rbt-section-gap bg-color-white">
    <div class="container">
        <div class="row g-4">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 col-md-4 col-12">
                @include('user.pages.account.sidebar')
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-9 col-md-8 col-12">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                        <i class="feather-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="p-4 p-md-5 rounded-4 shadow-sm" style="background: #ffffff; border: 1px solid #e2e8f0;">
                    <div class="pb-3 mb-4 border-bottom">
                        <h3 class="mb-1" style="font-weight: 800; color: #0f172a; font-size: 22px;">Edit Profile Information</h3>
                        <p class="text-muted mb-0" style="font-size: 14px;">Modify your name, delivery address, or set a new account password.</p>
                    </div>

                    <form action="{{ url('/update-userprofile/'.optional($userQ)->id) }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6 col-12">
                                <label for="name" class="form-label" style="font-weight: 600; color: #334155; font-size: 14px;">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="feather-user"></i></span>
                                    <input type="text" id="name" name="name" class="form-control border-start-0 py-2" value="{{ optional($userQ)->name }}" required style="font-size: 14px; border-radius: 0 10px 10px 0;">
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <label for="phone" class="form-label" style="font-weight: 600; color: #334155; font-size: 14px;">Phone Number (Locked)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="feather-phone"></i></span>
                                    <input type="text" id="phone" class="form-control border-start-0 py-2 bg-light text-muted" value="{{ optional($userQ)->phone }}" readonly style="font-size: 14px; border-radius: 0 10px 10px 0;">
                                </div>
                                <span class="text-muted" style="font-size: 11px;">Primary login identifier cannot be edited.</span>
                            </div>

                            <div class="col-12">
                                <label for="address" class="form-label" style="font-weight: 600; color: #334155; font-size: 14px;">Mailing / Delivery Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="feather-map-pin"></i></span>
                                    <input type="text" id="address" name="address" class="form-control border-start-0 py-2" value="{{ optional($userQ)->address }}" placeholder="e.g. House #12, Road #4, Dhanmondi, Dhaka" style="font-size: 14px; border-radius: 0 10px 10px 0;">
                                </div>
                            </div>

                            <div class="col-12 pt-3 border-top">
                                <h5 class="mb-3" style="font-weight: 700; color: #0f172a; font-size: 16px;">
                                    <i class="feather-lock me-2 text-primary"></i> Change Password
                                </h5>
                                <div class="col-md-6 col-12">
                                    <label for="password" class="form-label" style="font-weight: 600; color: #334155; font-size: 14px;">New Password (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="feather-key"></i></span>
                                        <input type="password" id="password" name="password" class="form-control border-start-0 py-2" placeholder="Leave blank to keep unchanged" style="font-size: 14px; border-radius: 0 10px 10px 0;">
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mt-4">
                                <button type="submit" class="rbt-btn btn-gradient radius-round">
                                    <span class="btn-text">Save Changes</span>
                                    <span class="btn-icon"><i class="feather-check"></i></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
