@extends('cms.cms_master')
@section('admin_content')

@php
    $company_info = DB::table('company_settings')->first();
    $isExisting = !empty($company_info->id);
    $actionUrl = $isExisting ? url('/cms/update_company_setting/' . $company_info->id) : route('company_info_set');
@endphp

<style>
    .settings-hero-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        color: #fff;
        padding: 28px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }
    .settings-hero-card::after {
        content: '';
        position: absolute;
        top: -60px;
        right: -60px;
        width: 180px;
        height: 180px;
        background: radial-gradient(circle, rgba(255, 122, 0, 0.25) 0%, rgba(255, 122, 0, 0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .logo-display-container {
        background: #071126;
        border: 2px dashed rgba(255, 122, 0, 0.35);
        border-radius: 12px;
        padding: 16px 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 90px;
        box-shadow: inset 0 2px 8px rgba(0,0,0,0.4);
    }
    .logo-display-container img {
        max-height: 65px;
        max-width: 240px;
        object-fit: contain;
    }
    .kpi-card {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        padding: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    .kpi-icon-wrap {
        width: 54px;
        height: 54px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .social-preview-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
    }
    .social-preview-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }
    .custom-nav-tabs .nav-link {
        font-size: 15px;
        font-weight: 700;
        padding: 12px 24px;
        border-radius: 10px;
        color: #475569;
        transition: all 0.2s ease;
    }
    .custom-nav-tabs .nav-link.active {
        background: #ff7a00 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(255, 122, 0, 0.35);
    }
    .input-icon-group {
        position: relative;
    }
    .input-icon-group i {
        position: absolute;
        left: 14px;
        top: 14px;
        color: #94a3b8;
        font-size: 15px;
    }
    .input-icon-group .form-control {
        padding-left: 42px;
    }
    .policy-content-preview {
        max-height: 250px;
        overflow-y: auto;
        padding: 16px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        font-size: 14px;
        line-height: 1.7;
        color: #334155;
    }
</style>

<section class="section">
    <!-- Page Header -->
    <div class="section-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1"><i class="fas fa-sliders-h text-warning mr-2"></i> Company & Brand Settings</h1>
            <p class="text-muted mb-0" style="font-size: 13.5px;">স্পার্ক একাডেমির মূল ব্র্যান্ডিং, যোগাযোগের তথ্য, পরিসংখ্যান এবং প্রাতিষ্ঠানিক পলিসি পরিচালনা করুন</p>
        </div>
        <div class="section-header-breadcrumb mt-2 mt-md-0">
            <ul class="nav nav-pills custom-nav-tabs" id="settingTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="overview-tab-btn" data-toggle="pill" href="#tab-overview" role="tab" aria-selected="true">
                        <i class="fas fa-eye mr-1"></i> এক নজরে প্রিভিউ
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="edit-tab-btn" data-toggle="pill" href="#tab-edit" role="tab" aria-selected="false">
                        <i class="fas fa-edit mr-1"></i> সেটিংস সম্পাদনা
                    </a>
                </li>
            </ul>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible show fade shadow-sm" style="border-radius: 10px;">
            <div class="alert-body d-flex align-items-center">
                <i class="fas fa-check-circle mr-2 fa-lg"></i>
                <div class="font-weight-bold">{{ session('status') }}</div>
                <button class="close ml-auto" data-dismiss="alert"><span>&times;</span></button>
            </div>
        </div>
    @endif

    <div class="tab-content" id="settingTabsContent">
        
        <!-- ========================================== -->
        <!-- TAB 1: OVERVIEW & LIVE PREVIEW             -->
        <!-- ========================================== -->
        <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
            
            <!-- Hero Identity Banner -->
            <div class="settings-hero-card mb-4">
                <div class="row align-items-center g-4">
                    <div class="col-lg-3 col-md-4 text-center text-md-left mb-3 mb-md-0">
                        <div class="logo-display-container">
                            @if(!empty($company_info->logo))
                                <img src="{{ asset($company_info->logo) }}" alt="{{ $company_info->company_name ?? 'Logo' }}">
                            @else
                                <span class="text-warning font-weight-bold">No Logo Uploaded</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5 text-center text-md-left">
                        <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 mb-2">
                            <span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 9999px; font-size: 12px;">
                                <i class="fas fa-certificate mr-1"></i> অফিশিয়াল ব্র্যান্ড প্রোফাইল
                            </span>
                            <span class="badge badge-dark px-3 py-1 text-warning font-weight-bold" style="border-radius: 9999px; font-size: 12px; background: rgba(255,122,0,0.18);">
                                HSC Physics & Science
                            </span>
                        </div>
                        <h2 class="text-white font-weight-bold mb-1" style="font-size: 26px;">{{ $company_info->company_name ?? 'Spark Academy - Emu Sir' }}</h2>
                        <p class="text-light mb-2" style="font-size: 14.5px; opacity: 0.85;">
                            <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $company_info->address ?? 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম' }}
                        </p>
                        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-start gap-3">
                            <a href="tel:{{ $company_info->phone_1 ?? '01768240303' }}" class="text-light" style="font-size: 13.5px; text-decoration: none;">
                                <i class="fas fa-phone-alt text-warning mr-1"></i> {{ $company_info->phone_1 ?? '০১৭৬৮২৪০৩০৩' }}
                            </a>
                            <span class="text-white-50">|</span>
                            <a href="mailto:{{ $company_info->email ?? 'learnwithemu@gmail.com' }}" class="text-light" style="font-size: 13.5px; text-decoration: none;">
                                <i class="fas fa-envelope text-info mr-1"></i> {{ $company_info->email ?? 'learnwithemu@gmail.com' }}
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 text-center text-md-right mt-3 mt-md-0">
                        <button class="btn btn-warning btn-lg px-4 font-weight-bold shadow-sm" onclick="$('#edit-tab-btn').tab('show');">
                            <i class="fas fa-edit mr-1"></i> তথ্য এডিট করুন
                        </button>
                    </div>
                </div>
            </div>

            <!-- Impact Metrics / Counter Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                    <div class="kpi-card">
                        <div class="kpi-icon-wrap" style="background: rgba(14, 165, 233, 0.12); color: #0ea5e9;">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold text-uppercase">মোট শিক্ষার্থী</span>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ number_format($company_info->count1 ?? 25000) }}+</h4>
                            <small class="text-info font-weight-bold">Total Learners</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                    <div class="kpi-card">
                        <div class="kpi-icon-wrap" style="background: rgba(255, 122, 0, 0.12); color: #ff7a00;">
                            <i class="fas fa-video"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold text-uppercase">কোর্স ও ক্লাস ভিডিও</span>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ number_format($company_info->count2 ?? 450) }}+</h4>
                            <small class="text-warning font-weight-bold">Courses & Videos</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                    <div class="kpi-card">
                        <div class="kpi-icon-wrap" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                            <i class="fas fa-award"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold text-uppercase">সাফল্যের হার</span>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $company_info->count3 ?? 85 }}%</h4>
                            <small class="text-success font-weight-bold">Certified / Success</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12 mb-3 mb-xl-0">
                    <div class="kpi-card">
                        <div class="kpi-icon-wrap" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                            <i class="fas fa-smile-beam"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold text-uppercase">সন্তুষ্টির মাত্রা</span>
                            <h4 class="mb-0 font-weight-bold text-dark">{{ $company_info->count4 ?? 98 }}%</h4>
                            <small class="text-primary font-weight-bold">Satisfaction Rate</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Details & Social Hub -->
            <div class="row mb-4">
                <!-- Contact Info Card -->
                <div class="col-lg-6 col-12 mb-4 mb-lg-0">
                    <div class="card card-primary h-100 shadow-sm" style="border-radius: 14px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary font-weight-bold">
                                <i class="fas fa-address-card mr-2"></i> যোগাযোগ ও হেল্পলাইন
                            </h5>
                            <span class="badge badge-light text-muted font-weight-bold">Contact Info</span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 p-3 bg-light rounded d-flex align-items-center">
                                <div class="mr-3 text-warning font-size-20" style="width: 40px; height: 40px; background: rgba(255,122,0,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block font-weight-bold">প্রধান হেল্পলাইন (Phone 1 - Call Pop-up)</small>
                                    <strong class="text-dark font-size-16">{{ $company_info->phone_1 ?? '০১৭৬৮২৪০৩০৩' }}</strong>
                                </div>
                                <a href="tel:{{ $company_info->phone_1 ?? '01768240303' }}" class="btn btn-sm btn-outline-warning rounded-pill px-3">Call</a>
                            </div>

                            <div class="mb-3 p-3 bg-light rounded d-flex align-items-center">
                                <div class="mr-3 font-size-20" style="width: 40px; height: 40px; background: rgba(37,211,102,0.12); color: #25d366; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fab fa-whatsapp"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block font-weight-bold">হোয়াটসঅ্যাপ নম্বর (WhatsApp Pop-up)</small>
                                    <strong class="text-dark font-size-16">{{ $company_info->whatsapp_number ?? $company_info->phone_1 ?? '০১৭৬৮২৪০৩০৩' }}</strong>
                                </div>
                                @php
                                    $wNum = $company_info->whatsapp_number ?? $company_info->phone_1 ?? '01768240303';
                                    $wClean = preg_replace('/[^0-9]/', '', $wNum);
                                    if (strpos($wClean, '88') !== 0) { $wClean = '88' . ltrim($wClean, '0'); }
                                @endphp
                                <a href="https://wa.me/{{ $wClean }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-success rounded-pill px-3">Chat</a>
                            </div>

                            <div class="mb-3 p-3 bg-light rounded d-flex align-items-center">
                                <div class="mr-3 text-info font-size-20" style="width: 40px; height: 40px; background: rgba(14,165,233,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block font-weight-bold">বিকল্প ফোন (Phone 2)</small>
                                    <strong class="text-dark font-size-16">{{ $company_info->phone_2 ?? '০১৫২১৩২৮৮৮৮' }}</strong>
                                </div>
                                @if(!empty($company_info->phone_2))
                                    <a href="tel:{{ $company_info->phone_2 }}" class="btn btn-sm btn-outline-info rounded-pill px-3">Call</a>
                                @endif
                            </div>

                            <div class="mb-3 p-3 bg-light rounded d-flex align-items-center">
                                <div class="mr-3 text-danger font-size-20" style="width: 40px; height: 40px; background: rgba(239,68,68,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <small class="text-muted d-block font-weight-bold">অফিশিয়াল ইমেইল (Email)</small>
                                    <strong class="text-dark font-size-15">{{ $company_info->email ?? 'learnwithemu@gmail.com' }}</strong>
                                </div>
                                <a href="mailto:{{ $company_info->email ?? 'learnwithemu@gmail.com' }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Mail</a>
                            </div>

                            <div class="p-3 bg-light rounded d-flex align-items-center">
                                <div class="mr-3 text-success font-size-20" style="width: 40px; height: 40px; background: rgba(16,185,129,0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block font-weight-bold">অফলাইন একাডেমি সেন্টার ও ঠিকানা</small>
                                    <strong class="text-dark font-size-14">{{ $company_info->address ?? 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Channels Card -->
                <div class="col-lg-6 col-12">
                    <div class="card card-warning h-100 shadow-sm" style="border-radius: 14px;">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-warning font-weight-bold">
                                <i class="fas fa-share-alt mr-2"></i> সোশ্যাল মিডিয়া ও কমিউনিটি
                            </h5>
                            <span class="badge badge-light text-muted font-weight-bold">Social Links</span>
                        </div>
                        <div class="card-body">
                            <!-- Facebook -->
                            <div class="social-preview-item">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 38px; height: 38px; background: #1877f2; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                        <i class="fab fa-facebook-f"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark font-size-14">ফেসবুক পেইজ</div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $company_info->facebook ?? 'সংযুক্ত নেই' }}</small>
                                    </div>
                                </div>
                                @if(!empty($company_info->facebook))
                                    <a href="{{ $company_info->facebook }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        ভিজিট <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                @else
                                    <span class="badge badge-light text-muted">অফলাইন</span>
                                @endif
                            </div>

                            <!-- Messenger Floating Button -->
                            <div class="social-preview-item">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 38px; height: 38px; background: #0084ff; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                        <i class="fab fa-facebook-messenger"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark font-size-14">মেসেঞ্জার চ্যাট (Messenger Pop-up)</div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $company_info->messenger_link ?? 'https://m.me/sparkacademyofficial' }}</small>
                                    </div>
                                </div>
                                <a href="{{ $company_info->messenger_link ?? 'https://m.me/sparkacademyofficial' }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    চ্যাট <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </div>

                            <!-- YouTube -->
                            <div class="social-preview-item">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 38px; height: 38px; background: #ff0000; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                        <i class="fab fa-youtube"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark font-size-14">ইউটিউব চ্যানেল</div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $company_info->youtube ?? 'সংযুক্ত নেই' }}</small>
                                    </div>
                                </div>
                                @if(!empty($company_info->youtube))
                                    <a href="{{ $company_info->youtube }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        ভিজিট <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                @else
                                    <span class="badge badge-light text-muted">অফলাইন</span>
                                @endif
                            </div>

                            <!-- Instagram -->
                            <div class="social-preview-item">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 38px; height: 38px; background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                        <i class="fab fa-instagram"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark font-size-14">ইনস্টাগ্রাম প্রোফাইল</div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $company_info->instagram ?? 'সংযুক্ত নেই' }}</small>
                                    </div>
                                </div>
                                @if(!empty($company_info->instagram))
                                    <a href="{{ $company_info->instagram }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                        ভিজিট <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                @else
                                    <span class="badge badge-light text-muted">অফলাইন</span>
                                @endif
                            </div>

                            <!-- Twitter / X -->
                            <div class="social-preview-item">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 38px; height: 38px; background: #0f172a; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px;">
                                        <i class="fab fa-twitter"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark font-size-14">টুইটার / এক্স (X)</div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $company_info->twitter ?? 'সংযুক্ত নেই' }}</small>
                                    </div>
                                </div>
                                @if(!empty($company_info->twitter))
                                    <a href="{{ $company_info->twitter }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                        ভিজিট <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                @else
                                    <span class="badge badge-light text-muted">অফলাইন</span>
                                @endif
                            </div>

                            <!-- LinkedIn -->
                            <div class="social-preview-item mb-0">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3" style="width: 38px; height: 38px; background: #0a66c2; color: #fff; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                        <i class="fab fa-linkedin-in"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark font-size-14">লিঙ্কডইন পেইজ</div>
                                        <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">{{ $company_info->linkden ?? 'সংযুক্ত নেই' }}</small>
                                    </div>
                                </div>
                                @if(!empty($company_info->linkden) && $company_info->linkden != 'null')
                                    <a href="{{ $company_info->linkden }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-info rounded-pill px-3">
                                        ভিজিট <i class="fas fa-external-link-alt ml-1"></i>
                                    </a>
                                @else
                                    <span class="badge badge-light text-muted">অফলাইন</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Details & Copyright Bar Preview -->
            <div class="card card-dark mb-4 shadow-sm" style="border-radius: 14px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark font-weight-bold">
                        <i class="fas fa-shoe-prints mr-2"></i> ওয়েবসাইট ফুটার কপিরাইট ও বিবরণ
                    </h5>
                    <span class="badge badge-dark">Footer Preview</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7 col-12 mb-3 mb-md-0">
                            <label class="font-weight-bold text-muted small text-uppercase">ফুটার বিবরণ (Footer Description)</label>
                            <div class="p-3 bg-light rounded" style="font-size: 14.5px; line-height: 1.7; color: #334155;">
                                {{ $company_info->footer_des ?? 'স্পার্ক একাডেমি ইমু স্যার এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য একটি অনলাইন ও অফলাইন কার্যক্রম সম্পন্নকারী প্রতিষ্ঠান।' }}
                            </div>
                        </div>
                        <div class="col-md-5 col-12">
                            <label class="font-weight-bold text-muted small text-uppercase">কপিরাইট ক্রেডিট (Footer Credit)</label>
                            <div class="p-3 bg-light rounded" style="font-size: 14.5px; font-weight: 600; color: #1e293b;">
                                {{ $company_info->footer_credit ?? '© ২০২৬ Spark Academy - Emu Sir. সর্বস্বত্ব সংরক্ষিত।' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Policies & Institutional Info Accordion/Tabs -->
            <div class="card card-info mb-5 shadow-sm" style="border-radius: 14px;">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-info font-weight-bold">
                        <i class="fas fa-file-contract mr-2"></i> প্রাতিষ্ঠানিক পলিসি ও ভিশন
                    </h5>
                    <span class="badge badge-info">Policies</span>
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3" id="policyTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" id="privacy-tab" data-toggle="pill" href="#policy-privacy" role="tab">
                                <i class="fas fa-user-shield mr-1"></i> গোপনীয়তা নীতি (Privacy Policy)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="mission-tab" data-toggle="pill" href="#policy-mission" role="tab">
                                <i class="fas fa-bullseye mr-1"></i> লক্ষ্য ও ভিশন (Mission & Vision)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" id="terms-tab" data-toggle="pill" href="#policy-terms" role="tab">
                                <i class="fas fa-gavel mr-1"></i> শর্তাবলী (Terms & Conditions)
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content" id="policyTabsContent">
                        <div class="tab-pane fade show active" id="policy-privacy" role="tabpanel">
                            <div class="policy-content-preview">
                                {!! $company_info->privacy_policy ?? '<p class="text-muted">কোনো গোপনীয়তা নীতি সংযুক্ত নেই।</p>' !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="policy-mission" role="tabpanel">
                            <div class="policy-content-preview">
                                {!! $company_info->mission_and_vission ?? '<p class="text-muted">কোনো লক্ষ্য ও ভিশন সংযুক্ত নেই।</p>' !!}
                            </div>
                        </div>
                        <div class="tab-pane fade" id="policy-terms" role="tabpanel">
                            <div class="policy-content-preview">
                                {!! $company_info->trams_and_condition ?? '<p class="text-muted">কোনো শর্তাবলী সংযুক্ত নেই।</p>' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- ========================================== -->
        <!-- TAB 2: EDIT & UPDATE SETTINGS FORM         -->
        <!-- ========================================== -->
        <div class="tab-pane fade" id="tab-edit" role="tabpanel">
            
            <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Section 1: Branding & Identity -->
                <div class="card card-primary mb-4 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header">
                        <h5 class="mb-0 text-primary font-weight-bold">
                            <i class="fas fa-building mr-2"></i> ১. একাডেমি ব্র্যান্ডিং ও মৌলিক তথ্য (Identity & Branding)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> একাডেমি / কোম্পানির নাম (Company Name)</label>
                                    <div class="input-icon-group">
                                        <i class="fas fa-university"></i>
                                        <input type="text" class="form-control" name="company_name" value="{{ $company_info->company_name ?? 'Spark Academy - Emu Sir' }}" required placeholder="Ex: Spark Academy - Emu Sir">
                                    </div>
                                    @error('company_name')<span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> অফিশিয়াল ইমেইল এড্রেস (Official Email)</label>
                                    <div class="input-icon-group">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" class="form-control" name="email" value="{{ $company_info->email ?? 'learnwithemu@gmail.com' }}" required placeholder="Ex: info@sparkacademy.com">
                                    </div>
                                    @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> প্রাথমিক ফোন / হেল্পলাইন ১ (Phone 1)</label>
                                    <div class="input-icon-group">
                                        <i class="fas fa-phone-alt"></i>
                                        <input type="text" class="form-control" name="phone_1" value="{{ $company_info->phone_1 ?? '01768240303' }}" required placeholder="Ex: 01768240303">
                                    </div>
                                    @error('phone_1')<span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">বিকল্প হেল্পলাইন ২ (Phone 2 - Optional)</label>
                                    <div class="input-icon-group">
                                        <i class="fas fa-mobile-alt"></i>
                                        <input type="text" class="form-control" name="phone_2" value="{{ $company_info->phone_2 ?? '' }}" placeholder="Ex: 01521328888">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-whatsapp text-success mr-1"></i> হোয়াটসঅ্যাপ নম্বর (WhatsApp Floating Button Number)</label>
                                    <div class="input-icon-group">
                                        <i class="fab fa-whatsapp text-success"></i>
                                        <input type="text" class="form-control" name="whatsapp_number" value="{{ $company_info->whatsapp_number ?? $company_info->phone_1 ?? '01768240303' }}" placeholder="Ex: 01768240303">
                                    </div>
                                    <small class="text-muted">ফ্লোটিং হোয়াটসঅ্যাপ বাটনে ক্লিক করলে এই নম্বরে সরাসরি চ্যাট শুরু হবে</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> একাডেমি ও অফলাইন সেন্টারের ঠিকানা (Center Address)</label>
                                    <div class="input-icon-group">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <input type="text" class="form-control" name="address" value="{{ $company_info->address ?? 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম' }}" required placeholder="Ex: মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম">
                                    </div>
                                    @error('address')<span class="text-danger small">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <!-- Logo Upload with Live Preview -->
                            <div class="col-12">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">একাডেমির অফিশিয়াল লোগো (Header & Footer Logo)</label>
                                    <div class="p-3 border rounded bg-light">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 col-12 text-center mb-3 mb-md-0">
                                                <div class="logo-display-container" style="width: 100%;">
                                                    <img id="logoLivePreview" src="{{ !empty($company_info->logo) ? asset($company_info->logo) : asset('frontend/assets/images/logo/logo.png') }}" alt="Preview">
                                                </div>
                                                <small class="text-muted d-block mt-2">বর্তমান প্রিভিউ (ডার্ক ব্যাকগ্রাউন্ড)</small>
                                            </div>
                                            <div class="col-md-8 col-12">
                                                <div class="custom-file mb-2">
                                                    <input type="file" name="logo" class="custom-file-input" id="logoFileInput" accept="image/*" onchange="previewLogoFile(event)">
                                                    <label class="custom-file-label" for="logoFileInput">নতুন লোগো ফাইল নির্বাচন করুন...</label>
                                                </div>
                                                <input type="hidden" name="old_image" value="{{ $company_info->logo ?? '' }}">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-info-circle text-primary"></i> <strong>সুপারিশ:</strong> স্বচ্ছ ব্যাকগ্রাউন্ড সহ হাই-রেজোলিউশন PNG বা SVG লোগো (অনুপাত প্রায় ৩:১ বা ৪:১)। আপলোড করা লোগোর আকৃতি বা রেজোলিউশন সংকুচিত হবে না।
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Social Media Channels -->
                <div class="card card-warning mb-4 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header">
                        <h5 class="mb-0 text-warning font-weight-bold">
                            <i class="fas fa-share-alt mr-2"></i> ২. সোশ্যাল মিডিয়া ও কমিউনিটি লিংক (Social Media Links)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-facebook text-primary mr-1"></i> ফেসবুক পেইজ (Facebook Page URL)</label>
                                    <input type="url" class="form-control" name="facebook" value="{{ $company_info->facebook ?? '' }}" placeholder="https://facebook.com/sparkacademyofficial">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-facebook-messenger text-primary mr-1"></i> মেসেঞ্জার চ্যাট লিংক (Messenger Floating Button URL)</label>
                                    <input type="url" class="form-control" name="messenger_link" value="{{ $company_info->messenger_link ?? 'https://m.me/sparkacademyofficial' }}" placeholder="https://m.me/sparkacademyofficial">
                                    <small class="text-muted">খালি রাখলে ফেসবুক পেইজ থেকে স্বয়ংক্রিয়ভাবে মেসেঞ্জার লিংক তৈরি হবে</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-youtube text-danger mr-1"></i> ইউটিউব চ্যানেল (YouTube Channel URL)</label>
                                    <input type="url" class="form-control" name="youtube" value="{{ $company_info->youtube ?? '' }}" placeholder="https://youtube.com/@sparkacademyofficial">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-instagram text-danger mr-1"></i> ইনস্টাগ্রাম লিংক (Instagram URL)</label>
                                    <input type="url" class="form-control" name="instagram" value="{{ $company_info->instagram ?? '' }}" placeholder="https://instagram.com/...">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-twitter text-info mr-1"></i> টুইটার / এক্স (X / Twitter URL)</label>
                                    <input type="url" class="form-control" name="twitter" value="{{ $company_info->twitter ?? '' }}" placeholder="https://twitter.com/...">
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-linkedin text-primary mr-1"></i> লিঙ্কডইন প্রোফাইল (LinkedIn URL)</label>
                                    <input type="url" class="form-control" name="linkden" value="{{ ($company_info->linkden != 'null') ? $company_info->linkden : '' }}" placeholder="https://linkedin.com/in/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Impact Counters -->
                <div class="card card-success mb-4 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header">
                        <h5 class="mb-0 text-success font-weight-bold">
                            <i class="fas fa-chart-line mr-2"></i> ৩. হোমপেজ পরিসংখ্যান ও ইমপ্যাক্ট কাউন্টার (Platform Counters)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> মোট শিক্ষার্থী (Total Learners)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-users text-primary"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="count1" value="{{ $company_info->count1 ?? 25000 }}" required>
                                    </div>
                                    <small class="text-muted">হোমপেজ ও এবাউট অংশে প্রদর্শিত সংখ্যা</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> কোর্স ও ক্লাস সংখ্যা (Courses & Videos)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-video text-warning"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="count2" value="{{ $company_info->count2 ?? 450 }}" required>
                                    </div>
                                    <small class="text-muted">মোট ভিডিও লেকচার ও কোর্স সংখ্যা</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> সফলতার হার % (Certified Students)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-award text-success"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="count3" value="{{ $company_info->count3 ?? 85 }}" required>
                                    </div>
                                    <small class="text-muted">শতাংশ হিসেবে (যেমন: 85)</small>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><span class="text-danger">*</span> সন্তুষ্টির মাত্রা % (Satisfaction Rate)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-check-circle text-info"></i></span>
                                        </div>
                                        <input type="number" class="form-control" name="count4" value="{{ $company_info->count4 ?? 98 }}" required>
                                    </div>
                                    <small class="text-muted">শতাংশ হিসেবে (যেমন: 98)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Footer Brand Text & Copyright -->
                <div class="card card-dark mb-4 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header">
                        <h5 class="mb-0 text-dark font-weight-bold">
                            <i class="fas fa-shoe-prints mr-2"></i> ৪. ফুটার ব্র্যান্ডিং ও কপিরাইট টেক্সট (Footer Details)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">ফুটার বর্ণনা (Footer Description)</label>
                                    <textarea name="footer_des" class="form-control" rows="4" placeholder="স্পার্ক একাডেমির সংক্ষিপ্ত পরিচিতি...">{{ $company_info->footer_des ?? 'স্পার্ক একাডেমি ইমু স্যার এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য একটি অনলাইন ও অফলাইন কার্যক্রম সম্পন্নকারী প্রতিষ্ঠান। স্পার্ক একাডেমির অনলাইন কোর্সের পাশাপাশি আছে নিজস্ব বই ।' }}</textarea>
                                    <small class="text-muted">ওয়েবসাইটের সব পেজের ফুটারে লোগোর নিচে প্রদর্শিত হবে</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">কপিরাইট ক্রেডিট লাইন (Footer Copyright)</label>
                                    <input type="text" class="form-control" name="footer_credit" value="{{ $company_info->footer_credit ?? '© ২০২৬ Spark Academy - Emu Sir. সর্বস্বত্ব সংরক্ষিত।' }}" placeholder="© ২০২৬ Spark Academy - Emu Sir.">
                                    <small class="text-muted">ফুটারের তলদেশে সর্বস্বত্ব সংরক্ষিত কপিরাইট লাইন</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Institutional Policies -->
                <div class="card card-info mb-4 shadow-sm" style="border-radius: 14px;">
                    <div class="card-header">
                        <h5 class="mb-0 text-info font-weight-bold">
                            <i class="fas fa-file-contract mr-2"></i> ৫. প্রাতিষ্ঠানিক পলিসি ও ভিশন (Policies & Vision)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark font-size-15"><i class="fas fa-user-shield text-info mr-1"></i> গোপনীয়তা নীতি (Privacy Policy)</label>
                            <textarea id="editor1" name="editor1" class="form-control" rows="6">{{ $company_info->privacy_policy ?? '' }}</textarea>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark font-size-15"><i class="fas fa-bullseye text-warning mr-1"></i> লক্ষ্য ও ভিশন (Mission & Vision)</label>
                            <textarea id="editor2" name="editor2" class="form-control" rows="6">{{ $company_info->mission_and_vission ?? '' }}</textarea>
                        </div>

                        <div class="form-group mb-0">
                            <label class="font-weight-bold text-dark font-size-15"><i class="fas fa-gavel text-danger mr-1"></i> ব্যবহারের শর্তাবলী (Terms & Conditions)</label>
                            <textarea id="editor3" name="editor3" class="form-control" rows="6">{{ $company_info->trams_and_condition ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="row mb-5">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-secondary btn-lg mr-2" onclick="$('#overview-tab-btn').tab('show');">
                            বাতিল করুন
                        </button>
                        <button type="submit" class="btn btn-success btn-lg px-5 font-weight-bold shadow">
                            <i class="fas fa-save mr-2"></i> সমস্ত পরিবর্তন সেভ করুন (Save All Changes)
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</section>

<script>
    function previewLogoFile(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var previewImg = document.getElementById('logoLivePreview');
                if (previewImg) {
                    previewImg.src = e.target.result;
                }
            }
            reader.readAsDataURL(input.files[0]);
            var fileName = input.files[0].name;
            var label = input.nextElementSibling;
            if (label) {
                label.innerText = fileName;
            }
        }
    }
</script>

@endsection
