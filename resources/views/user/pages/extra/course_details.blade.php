@extends('user.pages.test_master')

@push('frontend_style')
<style>
    .spark-chapter-video-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        margin-bottom: 10px;
        transition: all 0.2s ease;
    }
    .spark-chapter-video-item:last-child {
        margin-bottom: 0;
    }
    .spark-chapter-video-item:hover {
        border-color: #03c6bb;
        box-shadow: 0 4px 12px rgba(3, 198, 187, 0.08);
    }
    .spark-chapter-video-item.is-locked {
        background: #f8fafc;
        border-style: dashed;
        border-color: #cbd5e1;
    }
    .spark-chapter-video-title {
        font-weight: 600;
        font-size: 14.5px;
        color: #0f172a;
        margin: 0;
    }
    .spark-chapter-video-meta {
        font-size: 12px;
        color: #64748b;
    }
    html.dark-theme .spark-chapter-video-item {
        background: #131f37 !important;
        border-color: #243247 !important;
    }
    html.dark-theme .spark-chapter-video-item.is-locked {
        background: #0f172a !important;
        border-color: #334155 !important;
    }
    html.dark-theme .spark-chapter-video-title {
        color: #f8fafc !important;
    }
    html.dark-theme .spark-chapter-video-meta {
        color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')

<!-- Start Hero / Breadcrumb Area -->
<div class="student-hero" style="padding: 55px 0 65px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-10 col-12">
                <nav aria-label="breadcrumb">
                    <ul class="d-flex align-items-center gap-2 p-0 mb-3" style="list-style: none; font-size: 14px;">
                        <li><a href="{{ url('/') }}" style="color: #94a3b8; text-decoration: none;">Home</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li><a href="{{ route('course') }}" style="color: #94a3b8; text-decoration: none;">Courses</a></li>
                        <li style="color: #64748b;"><i class="feather-chevron-right"></i></li>
                        <li style="color: #03c6bb; font-weight: 600;">{{ $courseInfo->name }}</li>
                    </ul>
                </nav>
                <div class="student-hero-badge">
                    <i class="feather-award"></i> Verified Curriculum
                </div>
                <h1 class="student-hero-title mb-3" style="font-size: clamp(24px, 3.2vw, 38px); line-height: 1.4; padding-bottom: 8px;">
                    {{ $courseInfo->name }}
                </h1>
                <div class="d-flex flex-wrap align-items-center gap-4 text-white-50" style="font-size: 14px;">
                    <span><i class="feather-book-open text-primary me-1"></i> Interactive Curriculum</span>
                    <span><i class="feather-users text-primary me-1"></i> 50+ Active Students</span>
                    <span><i class="feather-clock text-primary me-1"></i> Self-Paced Learning</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb Area -->

                    {{-- <div class="d-flex align-items-center mb--20 flex-wrap rbt-course-details-feature">

                        <div class="feature-sin best-seller-badge">
                            <span class="rbt-badge-2">
                                <span class="image"><img src="{{asset('/')}}frontend/assets/images/icons/card-icon-1.png"
                                        alt="Best Seller Icon"></span> Bestseller
                            </span>
                        </div>

                        <div class="feature-sin rating">
                            <a href="#">4.8</a>
                            <a href="#"><i class="fa fa-star"></i></a>
                            <a href="#"><i class="fa fa-star"></i></a>
                            <a href="#"><i class="fa fa-star"></i></a>
                            <a href="#"><i class="fa fa-star"></i></a>
                            <a href="#"><i class="fa fa-star"></i></a>
                        </div>

                        <div class="feature-sin total-rating">
                            <a class="rbt-badge-4" href="#">215,475 rating</a>
                        </div>

                        <div class="feature-sin total-student">
                            <span>616,029 students</span>
                        </div>

                    </div>

                    <div class="rbt-author-meta mb--20">
                        <div class="rbt-avater">
                            <a href="#">
                                <img src="{{asset('/')}}frontend/assets/images/client/avatar-02.png" alt="Sophia Jaymes">
                            </a>
                        </div>
                        <div class="rbt-author-info">
                            By <a href="profile.html">Angela</a> In <a href="#">Development</a>
                        </div>
                    </div>

                    <ul class="rbt-meta">
                        <li><i class="feather-calendar"></i>Last updated 12/2024</li>
                        <li><i class="feather-globe"></i>English</li>
                        <li><i class="feather-award"></i>Certified Course</li>
                    </ul> --}}

                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Breadcrumb Area -->

<div class="rbt-course-details-area ptb--60">
    <div class="container">
        <div class="row g-5">

            <div class="col-lg-8">
                <div class="course-details-content">
                    @php
                        $isEnrolled = (optional($order)->status == 'enrolled');
                        $chaptersCollection = isset($chapters) ? $chapters : ($courseInfo->chapters ?? collect());

                        $bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
                        $toBn = function ($num) use ($bnDigits) {
                            return str_replace(range(0, 9), $bnDigits, (string) $num);
                        };

                        $totalChaptersCount = $chaptersCollection->count();
                        $totalVideosCount = $chaptersCollection->sum(function ($ch) {
                            return ($ch->videos ?? collect())->count();
                        });
                        $totalFreeVideosCount = $chaptersCollection->sum(function ($ch) {
                            return ($ch->videos ?? collect())->where('free_paid', 'free')->count();
                        });
                        $freeChaptersCount = $chaptersCollection->filter(function ($ch) {
                            return ($ch->videos ?? collect())->where('free_paid', 'free')->isNotEmpty();
                        })->count();
                        $firstFreeChapter = $chaptersCollection->first(function ($ch) {
                            return ($ch->videos ?? collect())->where('free_paid', 'free')->isNotEmpty();
                        });
                        $progressPercent = $isEnrolled
                            ? 100
                            : ($totalChaptersCount > 0 ? max(8, (int) round(($freeChaptersCount / $totalChaptersCount) * 100)) : 0);
                    @endphp

                    <!-- Chapter Completion & Progress Tracker -->
                    <div class="spark-course-progress-card p-4 rounded-4 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0; box-shadow: 0 4px 18px rgba(7, 17, 38, 0.05);">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-2">
                            <div>
                                <span class="badge" style="background: rgba(255, 122, 0, 0.12); color: #ff7a00; font-weight: 700; font-size: 12px; padding: 5px 12px; border-radius: 6px;">
                                    <i class="feather-trending-up me-1"></i> অধ্যায় ট্র্যাকার ও কারিকুলাম
                                </span>
                                <h4 class="mt-2 mb-0" style="font-weight: 800; color: #071126; font-size: 20px;">
                                    কোর্সের অধ্যায় ও লেকচারসমূহ
                                </h4>
                            </div>
                            <div class="text-end">
                                <span style="font-size: 13px; color: #64748b; font-weight: 600;">কোর্স কাঠামো:</span>
                                <div style="font-size: 18px; font-weight: 800; color: #ff7a00;">
                                    @if($totalChaptersCount === 0)
                                        কারিকুলাম শীঘ্রই আসছে
                                    @elseif($isEnrolled)
                                        {{ $toBn($totalChaptersCount) }}টি অধ্যায় • {{ $toBn($totalVideosCount) }}টি ভিডিও (আনলকড)
                                    @elseif($freeChaptersCount > 0)
                                        ফ্রি ট্রায়াল ({{ $toBn($freeChaptersCount) }}/{{ $toBn($totalChaptersCount) }} অধ্যায়)
                                    @else
                                        {{ $toBn($totalChaptersCount) }}টি অধ্যায় • {{ $toBn($totalVideosCount) }}টি ভিডিও
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="progress mt-3 mb-3" style="height: 10px; border-radius: 9999px; background: #e2e8f0;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progressPercent }}%; background: linear-gradient(90deg, #ff7a00 0%, #ea580c 100%); border-radius: 9999px;" aria-valuenow="{{ $progressPercent }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <!-- Dynamic Chapter Status Badges -->
                        @if($totalChaptersCount > 0)
                            <div class="d-flex flex-wrap gap-2 pt-1">
                                @foreach($chaptersCollection as $chIdx => $ch)
                                    @php
                                        $chVideos = $ch->videos ?? collect();
                                        $chFreeCount = $chVideos->where('free_paid', 'free')->count();
                                        $chAllFree = $chVideos->isNotEmpty() && $chFreeCount === $chVideos->count();
                                        $chHasFree = $chFreeCount > 0;
                                    @endphp
                                    <div class="d-flex align-items-center gap-1 px-2 py-1 rounded-3" style="font-size: 12px; font-weight: 600; {{ $chHasFree ? 'background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0;' : ($isEnrolled ? 'background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;' : 'background: #f8fafc; color: #94a3b8; border: 1px dashed #cbd5e1;') }}">
                                        @if($chAllFree)
                                            <i class="feather-check-circle text-success"></i> অধ্যায়-{{ $toBn($chIdx + 1) }} (ফ্রি)
                                        @elseif($chHasFree)
                                            <i class="feather-unlock text-success"></i> অধ্যায়-{{ $toBn($chIdx + 1) }} (ফ্রি প্রিভিউ)
                                        @elseif($isEnrolled)
                                            <i class="feather-play-circle text-primary"></i> অধ্যায়-{{ $toBn($chIdx + 1) }}
                                        @else
                                            <i class="feather-lock text-muted"></i> অধ্যায়-{{ $toBn($chIdx + 1) }}
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @if($totalFreeVideosCount > 0 && $firstFreeChapter)
                        <!-- Free Chapter Trial Banner -->
                        <div class="p-4 rounded-4 mb-4 d-flex flex-wrap align-items-center justify-content-between gap-3" style="background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%); color: #fff; box-shadow: 0 8px 24px rgba(7, 17, 38, 0.15);">
                            <div class="d-flex align-items-center gap-3">
                                <div style="width: 52px; height: 52px; border-radius: 14px; background: rgba(255, 122, 0, 0.2); color: #ff7a00; display: flex; align-items: center; justify-content: center; font-size: 24px; flex-shrink: 0;">
                                    <i class="feather-gift"></i>
                                </div>
                                <div>
                                    <span class="badge bg-warning text-dark mb-1" style="font-size: 11px; font-weight: 800;">উন্মুক্ত ট্রায়াল ({{ $toBn($totalFreeVideosCount) }}টি ফ্রি ক্লাস)</span>
                                    <h5 class="text-white mb-0" style="font-weight: 800; font-size: 18px;">ফ্রি ক্লাস করে দেখুন: {{ $firstFreeChapter->title }}</h5>
                                    <p class="mb-0 text-white-50" style="font-size: 13px;">
                                        {{ !empty($firstFreeChapter->description) ? $firstFreeChapter->description : 'এনরোল করার আগে ফ্রি ভিডিও লেকচারগুলো দেখে কোর্সের মান যাচাই করুন।' }}
                                    </p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('free.lession', ['id' => $courseInfo->id, 'chapter' => $firstFreeChapter->id]) }}" class="rbt-btn btn-gradient radius-round btn-sm px-4 py-2" style="font-size: 14px;">
                                    <i class="feather-play me-1"></i> ফ্রি ক্লাস দেখুন
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="rbt-course-feature-box rbt-shadow-box thuumbnail">
                        <img class="w-100" src="{{asset($courseInfo->image)}}" alt="{{ $courseInfo->name }}" style="max-height: 420px; object-fit: cover; border-radius: 12px;">
                    </div>

                    <div class="rbt-inner-onepage-navigation sticky-top mt--30">
                        <nav class="mainmenu-nav onepagenav">
                            <ul class="mainmenu">
                                <li class="current">
                                    <a href="#overview">Overview</a>
                                </li>
                                <li>
                                    <a href="#coursecontent">কোর্সের অধ্যায়সমূহ ({{ $toBn($totalChaptersCount) }})</a>
                                </li>
                            </ul>
                        </nav>
                    </div>

                    <!-- Start Course Feature Box  -->
                    <div class="rbt-course-feature-box overview-wrapper rbt-shadow-box mt--30 has-show-more" id="overview">
                        <div class="rbt-course-feature-inner has-show-more-inner-content">
                            <div class="section-title">
                                <h4 class="rbt-title-style-3">কোর্স সম্পর্কে বিস্তারিত</h4>
                            </div>
                            <p>{!! $courseInfo->description !!}</p>
                        </div>
                        <div class="rbt-show-more-btn">Show More</div>
                    </div>
                    <!-- End Course Feature Box  -->

                    <!-- Start Course Content (Dynamic Chapters & Videos) -->
                    <div class="course-content rbt-shadow-box coursecontent-wrapper mt--30" id="coursecontent">
                        <div class="rbt-course-feature-inner">
                            <div class="section-title mb-4">
                                <h4 class="rbt-title-style-3">
                                    {{ $totalChaptersCount > 0 ? $toBn($totalChaptersCount) . 'টি অধ্যায়ের সম্পূর্ণ কারিকুলাম' : 'কোর্স কারিকুলাম' }}
                                </h4>
                                <p style="color: #64748b; font-size: 14px; margin-top: 4px;">
                                    মোট {{ $toBn($totalChaptersCount) }}টি অধ্যায় ও {{ $toBn($totalVideosCount) }}টি ভিডিও লেকচার।
                                    @if(!$isEnrolled && $totalFreeVideosCount > 0)
                                        ফ্রি লেকচারগুলো এনরোল ছাড়াই দেখতে পারবেন।
                                    @elseif(!$isEnrolled)
                                        এনরোল করার পর সকল অধ্যায়ের ক্লাস আনলক হবে।
                                    @endif
                                </p>
                            </div>
                            <div class="rbt-accordion-style rbt-accordion-02 accordion" id="courseChaptersAccordion">
                                @forelse($chaptersCollection as $index => $chapter)
                                    @php
                                        $videos = $chapter->videos ?? collect();
                                        $chFreeCount = $videos->where('free_paid', 'free')->count();
                                        $chAllFree = $videos->isNotEmpty() && $chFreeCount === $videos->count();
                                        $chHasFree = $chFreeCount > 0;
                                        $isOpen = ($index === 0);
                                        $collapseKey = $chapter->id ?: ('legacy-' . $index);
                                    @endphp
                                    <div class="accordion-item card mb-3" style="border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden;">
                                        <h2 class="accordion-header card-header p-0" id="chHeading{{ $collapseKey }}">
                                            <button class="accordion-button {{ $isOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#chCollapse{{ $collapseKey }}" aria-expanded="{{ $isOpen ? 'true' : 'false' }}" aria-controls="chCollapse{{ $collapseKey }}" style="padding: 16px 20px; font-weight: 700; color: #071126; font-size: 16px;">
                                                <div class="d-flex flex-wrap align-items-center justify-content-between w-100 me-3 gap-2">
                                                    <div>
                                                        <span class="badge {{ $chHasFree ? 'bg-success' : ($isEnrolled ? 'bg-primary' : 'bg-secondary') }} me-2" style="font-size: 12px;">
                                                            অধ্যায়-{{ $toBn($index + 1) }}
                                                        </span>
                                                        <span>{{ $chapter->title }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-light text-dark border" style="font-size: 11px;">
                                                            {{ $toBn($videos->count()) }}টি লেকচার
                                                        </span>
                                                        @if($chAllFree)
                                                            <span class="badge bg-warning text-dark" style="font-size: 11px; font-weight: 800;">
                                                                <i class="feather-unlock"></i> ফ্রি ক্লাস
                                                            </span>
                                                        @elseif($chHasFree && !$isEnrolled)
                                                            <span class="badge bg-info text-dark" style="font-size: 11px; font-weight: 700;">
                                                                <i class="feather-eye"></i> ফ্রি প্রিভিউ
                                                            </span>
                                                        @elseif($isEnrolled)
                                                            <span class="badge bg-success text-white" style="font-size: 11px;">
                                                                <i class="feather-check-circle"></i> আনলকড
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light text-muted" style="font-size: 11px;">
                                                                <i class="feather-lock"></i> লকড
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="chCollapse{{ $collapseKey }}" class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}" aria-labelledby="chHeading{{ $collapseKey }}" data-bs-parent="#courseChaptersAccordion">
                                            <div class="accordion-body card-body p-4" style="background: #fafbfc;">
                                                @if(!empty($chapter->description))
                                                    <p class="text-muted mb-3" style="font-size: 13.5px;">{{ $chapter->description }}</p>
                                                @endif

                                                @if($videos->isEmpty())
                                                    <div class="p-3 text-center rounded-3" style="background: #fff; border: 1px dashed #cbd5e1;">
                                                        <i class="feather-video text-muted" style="font-size: 22px;"></i>
                                                        <p class="text-muted mb-0 mt-1" style="font-size: 13.5px;">
                                                            এই অধ্যায়ের ভিডিও লেকচার শীঘ্রই যুক্ত করা হবে।
                                                        </p>
                                                    </div>
                                                @else
                                                    @foreach($videos as $vIdx => $video)
                                                        @php
                                                            $isVideoFree = ($video->free_paid === 'free');
                                                            $canPlayVideo = ($isEnrolled || $isVideoFree);
                                                            $playUrl = $isEnrolled
                                                                ? route('paid.lession', ['id' => $courseInfo->id, 'chapter' => $chapter->id, 'video' => $video->id])
                                                                : route('free.lession', ['id' => $courseInfo->id, 'chapter' => $chapter->id, 'video' => $video->id]);
                                                        @endphp

                                                        @if($canPlayVideo)
                                                            <div class="spark-chapter-video-item">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <i class="feather-play-circle text-primary" style="font-size: 22px; flex-shrink: 0;"></i>
                                                                    <div>
                                                                        <p class="spark-chapter-video-title">{{ $video->title }}</p>
                                                                        <div class="d-flex flex-wrap align-items-center gap-2 mt-1 spark-chapter-video-meta">
                                                                            @if(!empty($video->duration))
                                                                                <span><i class="feather-clock me-1"></i>{{ $video->duration }}</span>
                                                                            @endif
                                                                            @if($isVideoFree)
                                                                                <span class="badge bg-success text-white" style="font-size: 10.5px;">ফ্রি</span>
                                                                            @else
                                                                                <span class="badge bg-primary text-white" style="font-size: 10.5px;">আনলকড</span>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a href="{{ $playUrl }}" class="rbt-btn btn-gradient radius-round btn-sm flex-shrink-0">
                                                                    <span>দেখুন</span> <i class="feather-play ms-1"></i>
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="spark-chapter-video-item is-locked">
                                                                <div class="d-flex align-items-center gap-3">
                                                                    <i class="feather-lock text-muted" style="font-size: 20px; flex-shrink: 0;"></i>
                                                                    <div>
                                                                        <p class="spark-chapter-video-title">{{ $video->title }}</p>
                                                                        <div class="d-flex flex-wrap align-items-center gap-2 mt-1 spark-chapter-video-meta">
                                                                            @if(!empty($video->duration))
                                                                                <span><i class="feather-clock me-1"></i>{{ $video->duration }}</span>
                                                                            @endif
                                                                            <span class="badge bg-secondary text-white" style="font-size: 10.5px;">পেইড</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <a href="#buyForm" class="rbt-btn btn-border radius-round btn-sm flex-shrink-0" style="font-size: 12px; padding: 6px 14px;">
                                                                    এনরোল করুন
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-4 text-center rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                        <i class="feather-book-open text-muted" style="font-size: 28px;"></i>
                                        <p class="text-muted mb-0 mt-2">কোর্সের অধ্যায় ও ভিডিও শীঘ্রই যুক্ত করা হবে।</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- End Course Content  -->

                    <!-- Start Course Feature Box  -->
                    {{-- <div class="rbt-course-feature-box rbt-shadow-box details-wrapper mt--30" id="details"> --}}
                        {{-- <div class="row g-5"> --}}
                            <!-- Start Feture Box  -->
                            {{-- <div class="col-lg-6">
                                <div class="section-title">
                                    <h4 class="rbt-title-style-3 mb--20">Requirements</h4>
                                </div>
                                <ul class="rbt-list-style-1">
                                    <li><i class="feather-check"></i>Become an advanced, confident, and modern
                                        JavaScript developer from scratch.</li>
                                    <li><i class="feather-check"></i>Have an intermediate skill level of Python
                                        programming.</li>
                                    <li><i class="feather-check"></i>Have a portfolio of various data analysis
                                        projects.</li>
                                    <li><i class="feather-check"></i>Use the numpy library to create and manipulate
                                        arrays.</li>
                                </ul>
                            </div> --}}
                            <!-- End Feture Box  -->

                            <!-- Start Feture Box  -->
                            {{-- <div class="col-lg-6">
                                <div class="section-title">
                                    <h4 class="rbt-title-style-3 mb--20">Description</h4>
                                </div>
                                <ul class="rbt-list-style-1">
                                    <li><i class="feather-check"></i>Use the Jupyter Notebook Environment.
                                        JavaScript developer from scratch.</li>
                                    <li><i class="feather-check"></i>Use the pandas module with Python to create and
                                        structure data.</li>
                                    <li><i class="feather-check"></i>Have a portfolio of various data analysis
                                        projects.</li>
                                    <li><i class="feather-check"></i>Create data visualizations using matplotlib and
                                        the seaborn.</li>
                                </ul>
                            </div> --}}
                            <!-- End Feture Box  -->
                        {{-- </div> --}}
                    {{-- </div> --}}
                    <!-- End Course Feature Box  -->

                    <!-- Start Intructor Area  -->
                    {{-- <div class="rbt-instructor rbt-shadow-box intructor-wrapper mt--30" id="intructor">
                        <div class="about-author border-0 pb--0 pt--0">
                            <div class="section-title mb--30">
                                <h4 class="rbt-title-style-3">Instructor</h4>
                            </div>
                            <div class="media align-items-center">
                                <div class="thumbnail">
                                    <a href="#">
                                        <img src="{{asset('/')}}frontend/assets/images/testimonial/testimonial-7.jpg" alt="Author Images">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <div class="author-info">
                                        <h5 class="title">
                                            <a class="hover-flip-item-wrapper" href="author.html">B.M. Rafekul Islam</a>
                                        </h5>
                                        <span class="b3 subtitle">Advanced Educator</span>
                                        <ul class="rbt-meta mb--20 mt--10">
                                            <li><i class="fa fa-star color-warning"></i>75,237 Reviews <span class="rbt-badge-5 ml--5">4.4 Rating</span></li>
                                            <li><i class="feather-users"></i>912,970 Students</li>
                                            <li><a href="#"><i class="feather-video"></i>16 Courses</a></li>
                                        </ul>
                                    </div>
                                    <div class="content">
                                        <p class="description">John is a brilliant educator, whose life was spent
                                            for computer science and love of nature.</p>

                                        <ul class="social-icon social-default icon-naked justify-content-start">
                                            <li><a href="https://www.facebook.com/">
                                                    <i class="feather-facebook"></i>
                                                </a>
                                            </li>
                                            <li><a href="https://www.twitter.com">
                                                    <i class="feather-twitter"></i>
                                                </a>
                                            </li>
                                            <li><a href="https://www.instagram.com/">
                                                    <i class="feather-instagram"></i>
                                                </a>
                                            </li>
                                            <li><a href="https://www.linkdin.com/">
                                                    <i class="feather-linkedin"></i>
                                                </a>
                                            </li>
                                        </ul>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- End Intructor Area  -->

                    <!-- Start Edu Review List  -->
                    {{-- <div class="rbt-review-wrapper rbt-shadow-box review-wrapper mt--30" id="review">
                        <div class="course-content">
                            <div class="section-title">
                                <h4 class="rbt-title-style-3">Review</h4>
                            </div>
                            <div class="row g-5 align-items-center">
                                <div class="col-lg-3">
                                    <div class="rating-box">
                                        <div class="rating-number">5.0</div>
                                        <div class="rating">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                            </svg>
                                        </div>
                                        <span class="sub-title">Course Rating</span>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="review-wrapper">
                                        <div class="single-progress-bar">
                                            <div class="rating-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 63%" aria-valuenow="63" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="value-text">63%</span>
                                        </div>

                                        <div class="single-progress-bar">
                                            <div class="rating-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 29%" aria-valuenow="29" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="value-text">29%</span>
                                        </div>

                                        <div class="single-progress-bar">
                                            <div class="rating-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 6%" aria-valuenow="6" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="value-text">6%</span>
                                        </div>

                                        <div class="single-progress-bar">
                                            <div class="rating-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="value-text">1%</span>
                                        </div>

                                        <div class="single-progress-bar">
                                            <div class="rating-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                                                    <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                                                    <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
                                                </svg>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar" role="progressbar" style="width: 1%" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="value-text">1%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <!-- End Edu Review List  -->
                        {{-- review --}}
                    {{-- <div class="about-author-list rbt-shadow-box featured-wrapper mt--30 has-show-more">
                        <div class="section-title">
                            <h4 class="rbt-title-style-3">Featured review</h4>
                        </div>
                        <div class="has-show-more-inner-content rbt-featured-review-list-wrapper">
                            <div class="rbt-course-review about-author">
                                <div class="media">
                                    <div class="thumbnail">
                                        <a href="#">
                                            <img src="{{asset('/')}}frontend/assets/images/testimonial/testimonial-3.jpg" alt="Author Images">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="author-info">
                                            <h5 class="title">
                                                <a class="hover-flip-item-wrapper" href="#">
                                                    Farjana Bawnia
                                                </a>
                                            </h5>
                                            <div class="rating">
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <p class="description">At 29 years old, my favorite compliment is being
                                                told that I look like my mom. Seeing myself in her image, like this
                                                daughter up top.</p>
                                            <ul class="social-icon social-default transparent-with-border justify-content-start">
                                                <li><a href="#">
                                                        <i class="feather-thumbs-up"></i>
                                                    </a>
                                                </li>
                                                <li><a href="#">
                                                        <i class="feather-thumbs-down"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rbt-course-review about-author">
                                <div class="media">
                                    <div class="thumbnail">
                                        <a href="#">
                                            <img src="{{asset('/')}}frontend/assets/images/testimonial/testimonial-4.jpg" alt="Author Images">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="author-info">
                                            <h5 class="title">
                                                <a class="hover-flip-item-wrapper" href="#">Razwan Islam</a>
                                            </h5>
                                            <div class="rating">
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <p class="description">My favorite compliment is being
                                                told that I look like my mom. Seeing myself in her image, like this
                                                daughter up top.</p>
                                            <ul class="social-icon social-default transparent-with-border justify-content-start">
                                                <li><a href="#">
                                                        <i class="feather-thumbs-up"></i>
                                                    </a>
                                                </li>
                                                <li><a href="#">
                                                        <i class="feather-thumbs-down"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rbt-course-review about-author">
                                <div class="media">
                                    <div class="thumbnail">
                                        <a href="#">
                                            <img src="{{asset('/')}}frontend/assets/images/testimonial/testimonial-1.jpg" alt="Author Images">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="author-info">
                                            <h5 class="title">
                                                <a class="hover-flip-item-wrapper" href="#">Babor Azom</a>
                                            </h5>
                                            <div class="rating">
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <p class="description">My favorite compliment is being
                                                told that I look like my mom. Seeing myself in her image, like this
                                                daughter up top.</p>
                                            <ul class="social-icon social-default transparent-with-border justify-content-start">
                                                <li><a href="#">
                                                        <i class="feather-thumbs-up"></i>
                                                    </a>
                                                </li>
                                                <li><a href="#">
                                                        <i class="feather-thumbs-down"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rbt-course-review about-author">
                                <div class="media">
                                    <div class="thumbnail">
                                        <a href="#">
                                            <img src="{{asset('/')}}frontend/assets/images/testimonial/testimonial-6.jpg" alt="Author Images">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="author-info">
                                            <h5 class="title">
                                                <a class="hover-flip-item-wrapper" href="#">Mohammad Ali</a>
                                            </h5>
                                            <div class="rating">
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <p class="description">My favorite compliment is being
                                                told that I look like my mom. Seeing myself in her image, like this
                                                daughter up top.</p>
                                            <ul class="social-icon social-default transparent-with-border justify-content-start">
                                                <li><a href="#">
                                                        <i class="feather-thumbs-up"></i>
                                                    </a>
                                                </li>
                                                <li><a href="#">
                                                        <i class="feather-thumbs-down"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rbt-course-review about-author">
                                <div class="media">
                                    <div class="thumbnail">
                                        <a href="#">
                                            <img src="{{asset('/')}}frontend/assets/images/testimonial/testimonial-8.jpg" alt="Author Images">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="author-info">
                                            <h5 class="title">
                                                <a class="hover-flip-item-wrapper" href="#">Sakib Al Hasan</a>
                                            </h5>
                                            <div class="rating">
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                                <a href="#"><i class="fa fa-star"></i></a>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <p class="description">My favorite compliment is being
                                                told that I look like my mom. Seeing myself in her image, like this
                                                daughter up top.</p>
                                            <ul class="social-icon social-default transparent-with-border justify-content-start">
                                                <li><a href="#">
                                                        <i class="feather-thumbs-up"></i>
                                                    </a>
                                                </li>
                                                <li><a href="#">
                                                        <i class="feather-thumbs-down"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rbt-show-more-btn">Show More</div>
                    </div> --}}
                </div>

            </div>

            <div class="col-lg-4">
                <div class="course-sidebar sticky-top rbt-shadow-box course-sidebar-top rbt-gradient-border ">
                    <div class="inner">
                        <!-- Start Viedo Wrapper  -->
                        <a class=" video-popup-with-text video-popup-wrapper sidebar-video-hidden text-center mb--15" href="javascript:void(0)">
                            <div class="video-content">
                                <img class="w-100 rbt-radius" src="{{asset($courseInfo->image)}}" alt="Video Images">
                                {{-- <div class="position-to-top">
                                    <span class="rbt-btn rounded-player-2 with-animation">
                                        <span class="play-icon"></span>
                                    </span>
                                </div>
                                {{-- <span class="play-view-text d-block color-white"><i class="feather-eye"></i> Preview this course</span> --}}
                            </div>
                        </a>
                        <!-- End Viedo Wrapper  -->
                        @if(optional($order)->status == 'enrolled')
                        <div class="p-4 rounded-3 text-center my-3" style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.3);">
                            <i class="feather-check-circle" style="font-size: 36px; color: #10b981;"></i>
                            <h5 class="mt-2 mb-1" style="font-weight: 700; color: #065f46;">You Are Enrolled!</h5>
                            <p style="color: #047857; font-size: 13px; line-height: 1.5;">You have unlimited access to all lessons and updates.</p>
                            <a href="#coursecontent" class="rbt-btn btn-gradient radius-round btn-sm w-100 mt-2">
                                <span>Go to Lessons</span>
                                <i class="feather-play ms-1"></i>
                            </a>
                        </div>
                        @else
                        <div class="content-item-content">
                            <div class="d-flex align-items-center justify-content-between my-3">
                                <span style="color: #64748b; font-size: 14px; font-weight: 600;">Course Price:</span>
                                <div style="font-family: var(--font-heading); font-size: 32px; font-weight: 800; color: #009e95;">
                                    ৳{{ number_format($courseInfo->price, 0) }}
                                </div>
                            </div>

                            <form method="get" action="{{route('user.buycourse')}}" id="buyForm">
                                <input type="hidden" name="productID" value="{{$courseInfo->id}}">
                                <button type="submit" class="rbt-btn btn-gradient icon-hover w-100 radius-round py-3 text-center">
                                    <span class="btn-text">Enroll In Course Now</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                </button>
                            </form>

                            <!-- Course Value Props -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 style="font-weight: 700; font-size: 14px; margin-bottom: 12px; color: #0f172a;">This Course Includes:</h6>
                                <ul class="d-flex flex-column gap-2 p-0 m-0" style="list-style: none; font-size: 14px; color: #475569;">
                                    <li><i class="feather-check text-success me-2"></i> Structured High-Quality Lectures</li>
                                    <li><i class="feather-check text-success me-2"></i> Real-Time Practice Quizzes</li>
                                    <li><i class="feather-check text-success me-2"></i> Full Lifetime Access</li>
                                    <li><i class="feather-check text-success me-2"></i> Mobile & Desktop Optimized</li>
                                    <li><i class="feather-check text-success me-2"></i> Verified Completion Certificate</li>
                                </ul>
                            </div>
                        </div>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@if(optional($order)->status != 'enrolled')
<!-- Start Course Action Bottom (Mobile Only) -->
<div class="rbt-course-action-bottom d-block d-lg-none" style="z-index: 999; box-shadow: 0 -4px 15px rgba(0,0,0,0.08);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6">
                <div class="section-title text-start">
                    <span class="d-block text-muted" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Course Fee</span>
                    <span class="current-price" style="font-size: 20px; font-weight: 800; color: #009e95;">৳ {{number_format($courseInfo->price, 0)}}</span>
                </div>
            </div>
            <div class="col-6 text-end">
                <a class="rbt-btn btn-gradient radius-round btn-sm px-3 py-2" href="#" onclick="event.preventDefault(); document.getElementById('buyForm').submit()">
                    <span class="btn-text">Enroll Now</span>
                    <i class="feather-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- End Course Action Bottom  -->
@endif

@endsection
