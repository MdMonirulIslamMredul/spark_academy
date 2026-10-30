@extends('cms.cms_master')
@section('admin_content')

<style>
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
</style>

<section class="section">
    <!-- Page Header -->
    <div class="section-header d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h1 class="mb-1"><i class="fas fa-edit text-warning mr-2"></i> Update Company & Brand Settings</h1>
            <p class="text-muted mb-0" style="font-size: 13.5px;">স্পার্ক একাডেমির প্রোফাইল ও সেটিংসের তথ্য আপডেট করুন</p>
        </div>
        <div class="section-header-breadcrumb mt-2 mt-md-0">
            <a href="{{ route('admin_setting') }}" class="btn btn-outline-primary font-weight-bold">
                <i class="fas fa-arrow-left mr-1"></i> এক নজরে প্রিভিউ দেখুন
            </a>
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

    <form action="{{ url('/cms/update_company_setting/' . $company_setting->id) }}" method="POST" enctype="multipart/form-data">
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
                                <input type="text" class="form-control" name="company_name" value="{{ $company_setting->company_name }}" required placeholder="Ex: Spark Academy - Emu Sir">
                            </div>
                            @error('company_name')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><span class="text-danger">*</span> অফিশিয়াল ইমেইল এড্রেস (Official Email)</label>
                            <div class="input-icon-group">
                                <i class="fas fa-envelope"></i>
                                <input type="email" class="form-control" name="email" value="{{ $company_setting->email }}" required placeholder="Ex: info@sparkacademy.com">
                            </div>
                            @error('email')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><span class="text-danger">*</span> প্রাথমিক ফোন / হেল্পলাইন ১ (Phone 1)</label>
                            <div class="input-icon-group">
                                <i class="fas fa-phone-alt"></i>
                                <input type="text" class="form-control" name="phone_1" value="{{ $company_setting->phone_1 }}" required placeholder="Ex: 01768240303">
                            </div>
                            @error('phone_1')<span class="text-danger small">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold">বিকল্প হেল্পলাইন ২ (Phone 2 - Optional)</label>
                            <div class="input-icon-group">
                                <i class="fas fa-mobile-alt"></i>
                                <input type="text" class="form-control" name="phone_2" value="{{ $company_setting->phone_2 }}" placeholder="Ex: 01521328888">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fab fa-whatsapp text-success mr-1"></i> হোয়াটসঅ্যাপ নম্বর (WhatsApp Floating Button Number)</label>
                            <div class="input-icon-group">
                                <i class="fab fa-whatsapp text-success"></i>
                                <input type="text" class="form-control" name="whatsapp_number" value="{{ $company_setting->whatsapp_number ?? $company_setting->phone_1 ?? '01768240303' }}" placeholder="Ex: 01768240303">
                            </div>
                            <small class="text-muted">ফ্লোটিং হোয়াটসঅ্যাপ বাটনে ক্লিক করলে এই নম্বরে সরাসরি চ্যাট শুরু হবে</small>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><span class="text-danger">*</span> একাডেমি ও অফলাইন সেন্টারের ঠিকানা (Center Address)</label>
                            <div class="input-icon-group">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" class="form-control" name="address" value="{{ $company_setting->address }}" required placeholder="Ex: মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম">
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
                                            <img id="logoLivePreview" src="{{ !empty($company_setting->logo) ? asset($company_setting->logo) : asset('frontend/assets/images/logo/logo.png') }}" alt="Preview">
                                        </div>
                                        <small class="text-muted d-block mt-2">বর্তমান প্রিভিউ (ডার্ক ব্যাকগ্রাউন্ড)</small>
                                    </div>
                                    <div class="col-md-8 col-12">
                                        <div class="custom-file mb-2">
                                            <input type="file" name="logo" class="custom-file-input" id="logoFileInput" accept="image/*" onchange="previewLogoFile(event)">
                                            <label class="custom-file-label" for="logoFileInput">নতুন লোগো ফাইল নির্বাচন করুন...</label>
                                        </div>
                                        <input type="hidden" name="old_image" value="{{ $company_setting->logo }}">
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
                            <input type="url" class="form-control" name="facebook" value="{{ $company_setting->facebook }}" placeholder="https://facebook.com/sparkacademyofficial">
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fab fa-facebook-messenger text-primary mr-1"></i> মেসেঞ্জার চ্যাট লিংক (Messenger Floating Button URL)</label>
                            <input type="url" class="form-control" name="messenger_link" value="{{ $company_setting->messenger_link ?? 'https://m.me/sparkacademyofficial' }}" placeholder="https://m.me/sparkacademyofficial">
                            <small class="text-muted">খালি রাখলে ফেসবুক পেইজ থেকে স্বয়ংক্রিয়ভাবে মেসেঞ্জার লিংক তৈরি হবে</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fab fa-youtube text-danger mr-1"></i> ইউটিউব চ্যানেল (YouTube Channel URL)</label>
                            <input type="url" class="form-control" name="youtube" value="{{ $company_setting->youtube }}" placeholder="https://youtube.com/@sparkacademyofficial">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fab fa-instagram text-danger mr-1"></i> ইনস্টাগ্রাম লিংক (Instagram URL)</label>
                            <input type="url" class="form-control" name="instagram" value="{{ $company_setting->instagram }}" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fab fa-twitter text-info mr-1"></i> টুইটার / এক্স (X / Twitter URL)</label>
                            <input type="url" class="form-control" name="twitter" value="{{ $company_setting->twitter }}" placeholder="https://twitter.com/...">
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold"><i class="fab fa-linkedin text-primary mr-1"></i> লিঙ্কডইন প্রোফাইল (LinkedIn URL)</label>
                            <input type="url" class="form-control" name="linkden" value="{{ ($company_setting->linkden != 'null') ? $company_setting->linkden : '' }}" placeholder="https://linkedin.com/in/...">
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
                                <input type="number" class="form-control" name="count1" value="{{ $company_setting->count1 }}" required>
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
                                <input type="number" class="form-control" name="count2" value="{{ $company_setting->count2 }}" required>
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
                                <input type="number" class="form-control" name="count3" value="{{ $company_setting->count3 }}" required>
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
                                <input type="number" class="form-control" name="count4" value="{{ $company_setting->count4 }}" required>
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
                            <textarea name="footer_des" class="form-control" rows="4" placeholder="স্পার্ক একাডেমির সংক্ষিপ্ত পরিচিতি...">{{ $company_setting->footer_des }}</textarea>
                            <small class="text-muted">ওয়েবসাইটের সব পেজের ফুটারে লোগোর নিচে প্রদর্শিত হবে</small>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="form-group">
                            <label class="font-weight-bold">কপিরাইট ক্রেডিট লাইন (Footer Copyright)</label>
                            <input type="text" class="form-control" name="footer_credit" value="{{ $company_setting->footer_credit }}" placeholder="© ২০২৬ Spark Academy - Emu Sir.">
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
                    <textarea id="editor1" name="editor1" class="form-control" rows="6">{{ $company_setting->privacy_policy }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold text-dark font-size-15"><i class="fas fa-bullseye text-warning mr-1"></i> লক্ষ্য ও ভিশন (Mission & Vision)</label>
                    <textarea id="editor2" name="editor2" class="form-control" rows="6">{{ $company_setting->mission_and_vission }}</textarea>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold text-dark font-size-15"><i class="fas fa-gavel text-danger mr-1"></i> ব্যবহারের শর্তাবলী (Terms & Conditions)</label>
                    <textarea id="editor3" name="editor3" class="form-control" rows="6">{{ $company_setting->trams_and_condition }}</textarea>
                </div>
            </div>
        </div>

        <!-- Submit Button Bar -->
        <div class="row mb-5">
            <div class="col-12 text-right">
                <a href="{{ route('admin_setting') }}" class="btn btn-secondary btn-lg mr-2">
                    বাতিল করুন
                </a>
                <button type="submit" class="btn btn-success btn-lg px-5 font-weight-bold shadow">
                    <i class="fas fa-save mr-2"></i> সমস্ত পরিবর্তন সেভ করুন (Save All Changes)
                </button>
            </div>
        </div>

    </form>
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
