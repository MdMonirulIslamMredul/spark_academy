@extends('cms.cms_master')
@section('admin_content')

<section class="section">
    <div class="section-header">
        <h1>About Us Dynamic Settings (আমাদের সম্পর্কে)</h1>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
                <button class="close" data-dismiss="alert"><span>&times;</span></button>
                {{ session('status') }}
            </div>
        </div>
    @endif

    @php
        $isUpdate = !empty($aboutInfo->id);
        $actionUrl = $isUpdate ? url('/cms/about_info_update/' . $aboutInfo->id) : route('cms.add_about_us');
        $defaultAboutDes = $aboutInfo->aboutDes ?? 'স্পার্ক একাডেমি ইমু স্যার এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য একটি অনলাইন ও অফলাইন কার্যক্রম সম্পন্নকারী প্রতিষ্ঠান। স্পার্ক একাডেমির অনলাইন কোর্সের পাশাপাশি আছে নিজস্ব বই ।';
    @endphp

    <form action="{{ $actionUrl }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- 1. Main Header About Description -->
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h4><i class="fas fa-info-circle text-primary mr-2"></i>১. মূল পরিচিতি বর্ণনা (Main About Description)</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label><b><span class="text-danger">*</span>হেডার বর্ণনা (About Page & Homepage Header Copy)</b></label>
                            <textarea id="editor1" name="editor1" class="form-control" rows="5" required>{{ $defaultAboutDes }}</textarea>
                            <small class="form-text text-muted">এই বর্ণনাটি About Us পেজের শীর্ষে এবং হোমপেজের পরিচিতি অংশে প্রদর্শিত হয়।</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. The 3 Core Pillars (Image 1) -->
        <div class="row">
            <div class="col-12">
                <div class="card card-warning">
                    <div class="card-header">
                        <h4><i class="fas fa-th-large text-warning mr-2"></i>২. ৩টি মূল কার্যক্রম ও ফিচার কার্ড (Core 3 Pillars)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Pillar 1 -->
                            <div class="col-md-4 col-12 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-video mr-1"></i> পিলার ১: অনলাইন ও অফলাইন</h6>
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">শিরোনাম (Title)</label>
                                        <input type="text" name="pillar1_title" class="form-control" value="{{ $aboutInfo->pillar1_title ?? 'অনলাইন ও অফলাইন কার্যক্রম' }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">বিবরণ (Description)</label>
                                        <textarea name="pillar1_desc" class="form-control" rows="4" required>{{ $aboutInfo->pillar1_desc ?? 'এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য আধুনিক স্টুডিও রেকর্ডেড ক্লাস, লাইভ ইন্টারঅ্যাকশন এবং চকবাজারের কেন্দ্রে অফলাইন নিবিড় কেয়ার।' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Pillar 2 -->
                            <div class="col-md-4 col-12 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <h6 class="text-info font-weight-bold mb-3"><i class="fas fa-book-open mr-1"></i> পিলার ২: নিজস্ব বই</h6>
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">শিরোনাম (Title)</label>
                                        <input type="text" name="pillar2_title" class="form-control" value="{{ $aboutInfo->pillar2_title ?? 'স্পার্ক একাডেমির নিজস্ব বই' }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">বিবরণ (Description)</label>
                                        <textarea name="pillar2_desc" class="form-control" rows="4" required>{{ $aboutInfo->pillar2_desc ?? 'ইমু স্যারের ১০+ বছরের শিক্ষকতার অভিজ্ঞতায় রচিত বিশেষ পদার্থবিজ্ঞান সহায়িকা বই। থিওরি, শর্টকাট টেকনিক ও বোর্ড প্রশ্ন সমাধানের নির্ভরযোগ্য সংকলন।' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Pillar 3 -->
                            <div class="col-md-4 col-12 mb-3">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <h6 class="text-success font-weight-bold mb-3"><i class="fas fa-gamepad mr-1"></i> পিলার ৩: MCQ Game</h6>
                                    <div class="form-group mb-2">
                                        <label class="font-weight-bold">শিরোনাম (Title)</label>
                                        <input type="text" name="pillar3_title" class="form-control" value="{{ $aboutInfo->pillar3_title ?? 'MCQ Game Arena' }}" required>
                                    </div>
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">বিবরণ (Description)</label>
                                        <textarea name="pillar3_desc" class="form-control" rows="4" required>{{ $aboutInfo->pillar3_desc ?? 'টানা ৩ বার কারেক্ট করার গ্যামিফাইড প্র্যাকটিস সিস্টেম। ভুল করা প্রশ্নের স্পেশাল রিট্রাই মোড এবং স্ট্রিক পয়েন্টের মাধ্যমে ১০০% চ্যাপ্টার মাস্টারি।' }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Meet Our Expert Section (Image 2) -->
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h4><i class="fas fa-user-tie text-success mr-2"></i>৩. প্রধান মেন্টর পরিচিতি (Meet Our Expert Profile)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">মেন্টরের নাম (Mentor Name)</label>
                                    <input type="text" name="expert_name" class="form-control" value="{{ $aboutInfo->expert_name ?? 'Engr. Iftekhar Hossain Emu' }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">মেন্টরের পদবী/বিষয় (Subject / Role)</label>
                                    <input type="text" name="expert_role" class="form-control" value="{{ $aboutInfo->expert_role ?? 'Physics Mentor' }}" required>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">ব্যাজ লেবেল (Badge Text)</label>
                                    <input type="text" name="expert_badge" class="form-control" value="{{ $aboutInfo->expert_badge ?? 'প্রধান মেন্টর পরিচিতি' }}">
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">মেন্টরের ছবি (Mentor Avatar Photo)</label>
                                    <input type="file" name="expert_image" class="form-control">
                                    <input type="hidden" name="old_image" value="{{ $aboutInfo->expert_image ?? $aboutInfo->image ?? 'images/emu_sir.jpg' }}">
                                    @if(!empty($aboutInfo->expert_image) || !empty($aboutInfo->image))
                                        <div class="mt-2 d-flex align-items-center gap-2">
                                            <span class="text-muted small mr-2">বর্তমান ছবি:</span>
                                            <img src="{{ asset($aboutInfo->expert_image ?? $aboutInfo->image) }}" class="rounded-circle border" width="60" height="60" style="object-fit: cover; object-position: top center;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">মেন্টরের শিক্ষাগত যোগ্যতা ও অর্জনসমূহ (Credentials / Badges)</label>
                                    <textarea name="expert_credentials" class="form-control" rows="5" placeholder="প্রতি লাইনে একটি যোগ্যতা লিখুন">{{ $aboutInfo->expert_credentials ?? "B.Sc. (CUET)\nMBA (CU)\nEx-Chittagong College\nFounder of Modulus Academic & Admission Care\nPhysics Teacher (10+ years experience)" }}</textarea>
                                    <small class="form-text text-muted">প্রতিটি যোগ্যতা নতুন লাইনে (Enter চেপে) লিখুন। সাইটে এগুলো গ্রিন টিক সহ স্টাইলিশ ব্যাজ হিসেবে সাজানো হবে।</small>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">মেন্টরের বিস্তারিত ভূমিকা ও বক্তব্য (Bio / Experience Details)</label>
                                    <textarea name="expert_bio" class="form-control" rows="4">{{ $aboutInfo->expert_bio ?? 'বিগত ১০ বছরের বেশি সময় ধরে হাজারো শিক্ষার্থীকে ফিজিক্সের জটিল বিষয়গুলো সহজ ও সাবলীলভাবে শিখিয়ে এইচএসসি ও প্রকৌশল বিশ্ববিদ্যালয় ভর্তি পরীক্ষায় ঈর্ষণীয় সাফল্য অর্জনে পথপ্রদর্শন করে আসছেন। একাডেমিক ও এডমিশন একসাথে প্যারালাল প্রস্তুতির মাধ্যমে শিক্ষার্থীদের সর্বোচ্চ স্তরে পৌঁছে দেওয়াই স্পার্ক একাডেমির অঙ্গীকার।' }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-facebook text-primary mr-1"></i> ফেসবুক পেইজ লিংক (Facebook Page URL)</label>
                                    <input type="url" name="facebook_link" class="form-control" value="{{ $aboutInfo->facebook_link ?? 'https://facebook.com/sparkacademyofficial' }}" placeholder="https://facebook.com/...">
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold"><i class="fab fa-youtube text-danger mr-1"></i> ইউটিউব চ্যানেল লিংক (YouTube Channel URL)</label>
                                    <input type="url" name="youtube_link" class="form-control" value="{{ $aboutInfo->youtube_link ?? 'https://youtube.com/@sparkacademyofficial' }}" placeholder="https://youtube.com/@...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Offline Center Info -->
        <div class="row">
            <div class="col-12">
                <div class="card card-dark">
                    <div class="card-header">
                        <h4><i class="fas fa-map-marker-alt text-danger mr-2"></i>৪. অফলাইন সেন্টার ও হেল্পলাইন (Offline Address & Helpline)</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">অফলাইন ঠিকানা (Offline Address)</label>
                                    <input type="text" name="offline_address" class="form-control" value="{{ $aboutInfo->offline_address ?? 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম' }}" required>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">যোগাযোগ ফোন নম্বর (Phone / Helpline)</label>
                                    <input type="text" name="offline_phone" class="form-control" value="{{ $aboutInfo->offline_phone ?? '০১৭৬৮২৪০৩০৩' }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row mb-5">
            <div class="col-12 text-right">
                <button type="submit" class="btn btn-lg btn-success px-5 font-weight-bold shadow-sm">
                    <i class="fas fa-save mr-2"></i> সেভ এবং আপডেট করুন (Save Changes)
                </button>
            </div>
        </div>

    </form>
</section>

@endsection
