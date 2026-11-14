@push('frontend_style')
<style>
    /* ==========================================================
       CUSTOM SECURE VIDEO PLAYER (Disables all YouTube Overlays)
       ========================================================== */
    .custom-player-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        height: 0;
        background: #000000;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        user-select: none;
        -webkit-user-select: none;
    }

    .custom-player-wrapper:-webkit-full-screen {
        width: 100vw !important;
        height: 100vh !important;
        padding-bottom: 0 !important;
        border-radius: 0 !important;
    }
    .custom-player-wrapper:fullscreen {
        width: 100vw !important;
        height: 100vh !important;
        padding-bottom: 0 !important;
        border-radius: 0 !important;
    }

    /* Video Frame Container (With Overscan / Crop to eliminate all YouTube edge elements) */
    .cp-video-frame {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        pointer-events: none;
    }

    .cp-video-frame iframe {
        position: absolute;
        top: -48px;
        left: 0;
        width: 100%;
        height: calc(100% + 96px);
        border: 0;
        pointer-events: none;
    }

    /* Top Shield - Completely masks and blocks any YouTube top title or icons */
    .cp-top-shield {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 64px;
        background: #000000;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.85);
        z-index: 15;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 18px;
        pointer-events: auto;
        color: #ffffff;
        transition: opacity 0.3s ease;
    }

    .cp-top-left {
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 65%;
        overflow: hidden;
    }

    .cp-shield-badge {
        background: #007A55;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .cp-title-text {
        font-size: 14.5px;
        font-weight: 700;
        color: #f8fafc;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .cp-watermark-tag {
        font-size: 12px;
        font-weight: 600;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 6px;
        background: rgba(15, 23, 42, 0.7);
        padding: 3px 10px;
        border-radius: 9999px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        white-space: nowrap;
    }

    /* Full Click Overlay - Intercepts all clicks on the player */
    .cp-click-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10;
        cursor: pointer;
        pointer-events: auto;
        background: transparent;
    }

    /* Center Play/Pause Ripple Bubble */
    .cp-center-bubble {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.7);
        width: 72px;
        height: 72px;
        background: rgba(0, 122, 85, 0.85);
        backdrop-filter: blur(4px);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        opacity: 0;
        pointer-events: none;
        transition: transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.25s ease;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
    }

    .cp-center-bubble.active {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }

    /* Double Tap Skip Indicators */
    .cp-skip-feedback {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.75);
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .cp-skip-feedback.left { left: 15%; }
    .cp-skip-feedback.right { right: 15%; }
    .cp-skip-feedback.show { opacity: 1; }

    /* Floating Student Watermark (Anti-Screen Recording) */
    .cp-floating-watermark {
        position: absolute;
        bottom: 75px;
        right: 25px;
        z-index: 14;
        color: rgba(255, 255, 255, 0.25);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.5px;
        pointer-events: none;
        transition: all 1s ease;
    }

    /* Custom Controls Bar at Bottom (Pure Solid Black with Dark Backdrop Shadow) */
    .cp-controls-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 20;
        background: #000000;
        box-shadow: 0 -15px 35px rgba(0, 0, 0, 0.98);
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        padding: 12px 18px 14px;
        pointer-events: auto;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    /* Auto-hide controls when idle */
    .custom-player-wrapper.hide-controls .cp-controls-bar,
    .custom-player-wrapper.hide-controls .cp-top-shield {
        opacity: 0;
        pointer-events: none;
    }
    .custom-player-wrapper.hide-controls {
        cursor: none;
    }

    /* Progress Timeline */
    .cp-timeline-container {
        position: relative;
        height: 18px;
        display: flex;
        align-items: center;
        cursor: pointer;
        margin-bottom: 6px;
    }

    .cp-timeline-track {
        position: relative;
        width: 100%;
        height: 5px;
        background: rgba(255, 255, 255, 0.22);
        border-radius: 4px;
        overflow: visible;
        transition: height 0.15s ease;
    }

    .cp-timeline-container:hover .cp-timeline-track {
        height: 8px;
    }

    .cp-timeline-buffer {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0%;
        background: rgba(255, 255, 255, 0.4);
        border-radius: 4px;
    }

    .cp-timeline-played {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #007A55 0%, #10b981 100%);
        border-radius: 4px;
    }

    .cp-timeline-thumb {
        position: absolute;
        top: 50%;
        left: 0%;
        width: 14px;
        height: 14px;
        background: #ffffff;
        border: 2px solid #007A55;
        border-radius: 50%;
        transform: translate(-50%, -50%) scale(0);
        transition: transform 0.15s ease;
        box-shadow: 0 0 6px rgba(0, 0, 0, 0.5);
    }

    .cp-timeline-container:hover .cp-timeline-thumb {
        transform: translate(-50%, -50%) scale(1);
    }

    .cp-timeline-hover-time {
        position: absolute;
        bottom: 22px;
        left: 0;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.85);
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 7px;
        border-radius: 4px;
        pointer-events: none;
        display: none;
        white-space: nowrap;
    }

    /* Controls Row */
    .cp-controls-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cp-controls-left,
    .cp-controls-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .cp-btn {
        background: transparent;
        color: #ffffff;
        border: none;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        font-size: 18px;
        transition: all 0.2s ease;
        padding: 0;
    }

    .cp-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        color: #10b981;
    }

    .cp-seek-btn {
        position: relative;
        font-size: 16px;
    }

    .cp-seek-btn .cp-seek-text {
        position: absolute;
        font-size: 9px;
        font-weight: 800;
        line-height: 1;
        bottom: 5px;
    }

    /* Volume Slider Group */
    .cp-volume-group {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cp-volume-slider-wrapper {
        width: 0;
        overflow: hidden;
        transition: width 0.25s ease;
        display: flex;
        align-items: center;
    }

    .cp-volume-group:hover .cp-volume-slider-wrapper,
    .cp-volume-slider-wrapper:focus-within {
        width: 75px;
    }

    .cp-volume-slider {
        width: 70px;
        height: 4px;
        accent-color: #007A55;
        cursor: pointer;
    }

    /* Time Display */
    .cp-time-display {
        font-size: 13px;
        font-weight: 600;
        color: #cbd5e1;
        display: flex;
        align-items: center;
        gap: 5px;
        font-variant-numeric: tabular-nums;
    }
    .cp-time-separator {
        color: #64748b;
    }

    /* Speed Dropdown Menu */
    .cp-speed-menu-container {
        position: relative;
    }

    .cp-speed-btn {
        font-size: 13px;
        font-weight: 700;
        border-radius: 6px;
        width: auto;
        padding: 4px 10px;
        height: 32px;
        background: rgba(255, 255, 255, 0.1);
    }

    .cp-speed-dropdown {
        position: absolute;
        bottom: 42px;
        right: 0;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 8px;
        padding: 6px 0;
        display: none;
        flex-direction: column;
        min-width: 105px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        z-index: 30;
    }

    .cp-speed-dropdown.show {
        display: flex;
    }

    .cp-speed-item {
        padding: 6px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #cbd5e1;
        cursor: pointer;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .cp-speed-item:hover {
        background: rgba(0, 122, 85, 0.15);
        color: #34d399;
    }

    .cp-speed-item.active {
        color: #10b981;
        font-weight: 700;
        background: rgba(0, 122, 85, 0.2);
    }

    /* Sidebar Active Lecture & Chapter Description */
    .video-link.active-lecture {
        background: rgba(3, 198, 187, 0.12) !important;
        border-radius: 8px;
        color: #03c6bb !important;
        font-weight: 700;
    }

    .video-link.active-lecture .text,
    .video-link.active-lecture i {
        color: #03c6bb !important;
    }

    .spark-chapter-meta-desc {
        font-size: 12.5px;
        color: #64748b;
        margin-bottom: 10px;
    }

    html.dark-theme .spark-chapter-meta-desc {
        color: #94a3b8;
    }

    .locked-video-link {
        opacity: 0.78;
        cursor: pointer;
    }

    .locked-video-link:hover,
    .locked-video-link.active-locked {
        opacity: 1;
        background: rgba(245, 158, 11, 0.1) !important;
        border-radius: 8px;
    }

    .spark-paid-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: rgba(245, 158, 11, 0.16);
        color: #d97706;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 9999px;
    }

    html.dark-theme .spark-paid-pill {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    .cp-locked-overlay {
        position: absolute;
        inset: 0;
        z-index: 25;
        background: rgba(7, 17, 38, 0.94);
        backdrop-filter: blur(8px);
        display: none;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 24px;
        color: #ffffff;
    }

    .cp-locked-overlay.show {
        display: flex;
    }

    .cp-locked-card {
        max-width: 480px;
    }

    .cp-locked-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(245, 158, 11, 0.18);
        color: #fbbf24;
        border: 1px solid rgba(251, 191, 36, 0.4);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        margin-bottom: 14px;
    }

    .cp-locked-card h4 {
        color: #ffffff;
        font-weight: 800;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .cp-locked-card p {
        color: #cbd5e1;
        font-size: 14px;
        line-height: 1.6;
        margin-bottom: 16px;
    }

    .spark-unlock-sidebar-card {
        margin-top: 18px;
        padding: 16px;
        border-radius: 12px;
        background: linear-gradient(135deg, #071126 0%, #0d1e3d 100%);
        color: #ffffff;
        border: 1px solid rgba(255, 122, 0, 0.35);
    }

    .spark-unlock-sidebar-card h6 {
        color: #ffffff;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .spark-unlock-sidebar-card p {
        color: #cbd5e1;
        font-size: 12.5px;
        margin-bottom: 12px;
    }

    @media (max-width: 991px) {
        .rbt-lesson-rightsidebar.lesson-video {
            order: -1;
        }
        .cp-top-shield {
            height: 48px;
            padding: 0 12px;
        }
        .cp-top-left {
            max-width: 72%;
        }
        .cp-watermark-tag {
            display: none;
        }
        .cp-controls-bar {
            padding: 8px 12px 10px;
        }
        .cp-controls-left,
        .cp-controls-right {
            gap: 6px;
        }
        .cp-time-display {
            font-size: 11px;
        }
    }
</style>
@endpush

@php
    $chaptersList = isset($chapters) ? $chapters : collect();
    $activeChapterId = isset($selectedChapterId) ? $selectedChapterId : null;
    $activeVideoId = isset($selectedVideoId) ? $selectedVideoId : null;
    $company_info = $company_info ?? DB::table('company_settings')->first();
    $rawCompanyName = optional($company_info)->company_name ?: 'পাঠজল্পনা';
    $companyName = explode(' - ', $rawCompanyName)[0] ?: $rawCompanyName;
    $studentName = Cookie::get('user_name') ?: 'Student';
    $freeVideosCount = $chaptersList->sum(function ($ch) {
        return ($ch->videos ?? collect())->where('free_paid', 'free')->count();
    });
    $paidVideosCount = $chaptersList->sum(function ($ch) {
        return ($ch->videos ?? collect())->where('free_paid', '!=', 'free')->count();
    });
    $showLockedOnInit = ($isFreeMode && $freeVideosCount === 0);
@endphp

<div class="rbt-lesson-area bg-color-white">
    <div class="rbt-lesson-content-wrapper">
        <div class="rbt-lesson-leftsidebar">
            <div class="rbt-course-feature-inner rbt-search-activation">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">
                        {{ $isFreeMode ? 'Course Preview & Curriculum' : 'Course Content' }}
                    </h4>
                </div>

                <div class="lesson-search-wrapper">
                    <form action="#" class="rbt-search-style-1" onsubmit="return false;">
                        <input id="lessonSearchInput" type="text" placeholder="Search Lesson">
                        <button class="search-btn disabled" type="button"><i class="feather-search"></i></button>
                    </form>
                </div>

                <hr class="mt--10">

                <div class="rbt-accordion-style rbt-accordion-02 for-right-content accordion">
                    <div class="accordion" id="accordionExampleb2">
                        @forelse ($chaptersList as $cIdx => $chapter)
                            @php
                                $collapseId = 'collapseChapter' . ($chapter->id !== null ? $chapter->id : 'idx' . $cIdx);
                                $headingId = 'headingChapter' . ($chapter->id !== null ? $chapter->id : 'idx' . $cIdx);
                                $isChapterExpanded = ($activeChapterId !== null && (int) $chapter->id === (int) $activeChapterId)
                                    || ($activeChapterId === null && $cIdx === 0);
                                $chapterVideos = $chapter->videos ?? collect();
                                $chapterFreeCount = $chapterVideos->where('free_paid', 'free')->count();
                            @endphp
                            <div class="accordion-item card" data-chapter-accordion="1">
                                <h2 class="accordion-header card-header" id="{{ $headingId }}">
                                    <button class="accordion-button {{ $isChapterExpanded ? '' : 'collapsed' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            aria-expanded="{{ $isChapterExpanded ? 'true' : 'false' }}"
                                            data-bs-target="#{{ $collapseId }}"
                                            aria-controls="{{ $collapseId }}">
                                        <span>{{ $chapter->title }}</span>
                                        <span class="rbt-badge-5 ml--10">{{ $chapterVideos->count() }}</span>
                                        @if($isFreeMode && $chapterVideos->isNotEmpty() && $chapterFreeCount === 0)
                                            <span class="spark-paid-pill ms-2"><i class="feather-lock"></i> Paid</span>
                                        @endif
                                    </button>
                                </h2>
                                <div id="{{ $collapseId }}"
                                     class="accordion-collapse collapse {{ $isChapterExpanded ? 'show' : '' }}"
                                     aria-labelledby="{{ $headingId }}">
                                    <div class="accordion-body card-body">
                                        @if(!empty($chapter->description))
                                            <p class="spark-chapter-meta-desc">{{ $chapter->description }}</p>
                                        @endif
                                        <ul class="rbt-course-main-content liststyle">
                                            @forelse ($chapterVideos as $video)
                                                @php
                                                    $isLockedVideo = ($isFreeMode && $video->free_paid !== 'free');
                                                    $isVideoSelected = (!$isLockedVideo && $activeVideoId !== null && (int) $video->id === (int) $activeVideoId);
                                                @endphp
                                                <li class="lesson-video-item">
                                                    @if($isLockedVideo)
                                                        <a href="#"
                                                           class="locked-video-link"
                                                           data-video-id="{{ $video->id }}"
                                                           data-chapter-id="{{ $chapter->id }}"
                                                           data-video-title="{{ $video->title }}"
                                                           data-chapter-title="{{ $chapter->title }}">
                                                            <div class="course-content-left">
                                                                <i class="feather-lock text-warning"></i>
                                                                <span class="text">{{ $video->title }}</span>
                                                            </div>
                                                            <div class="course-content-right">
                                                                @if(!empty($video->duration))
                                                                    <span class="min-lable">{{ $video->duration }}</span>
                                                                @endif
                                                                <span class="spark-paid-pill"><i class="feather-lock"></i> Paid</span>
                                                            </div>
                                                        </a>
                                                    @else
                                                        <a href="#"
                                                           class="video-link {{ $isVideoSelected ? 'active-lecture' : '' }}"
                                                           data-video-id="{{ $video->id }}"
                                                           data-chapter-id="{{ $chapter->id }}"
                                                           data-link="{{ $video->link }}">
                                                            <div class="course-content-left">
                                                                <i class="feather-play-circle"></i>
                                                                <span class="text">{{ $video->title }}</span>
                                                            </div>
                                                            <div class="course-content-right">
                                                                @if(!empty($video->duration))
                                                                    <span class="min-lable">{{ $video->duration }}</span>
                                                                @endif
                                                                @if($video->free_paid === 'free')
                                                                    <span class="rbt-badge-5" style="font-size: 10px; padding: 2px 6px;">Free</span>
                                                                @endif
                                                                <span class="rbt-check"><i class="feather-check"></i></span>
                                                            </div>
                                                        </a>
                                                    @endif
                                                </li>
                                            @empty
                                                <li class="text-muted small py-2">No videos in this chapter yet.</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted">
                                No chapters or videos found for this course.
                            </div>
                        @endforelse
                    </div>
                </div>

                @if($isFreeMode && $paidVideosCount > 0)
                    <div class="spark-unlock-sidebar-card">
                        <h6><i class="feather-lock text-warning me-1"></i> Unlock Full Course</h6>
                        <p>
                            {{ $paidVideosCount }} paid {{ $paidVideosCount === 1 ? 'lecture' : 'lectures' }} across {{ $chaptersList->count() }} {{ $chaptersList->count() === 1 ? 'chapter' : 'chapters' }} require enrollment. Purchase the full course for unlimited access.
                        </p>
                        <div class="d-flex align-items-center justify-content-between gap-2">
                            <span style="font-size: 16px; font-weight: 800; color: #ff7a00;">৳{{ number_format($courseInfo->price ?? 0, 0) }}</span>
                            <a href="{{ route('user.buycourse', ['productID' => $courseInfo->id]) }}" class="rbt-btn btn-gradient btn-xs radius-round">
                                <span>Purchase Full Course</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="rbt-lesson-rightsidebar overflow-hidden lesson-video">
            <div class="lesson-top-bar">
                <div class="lesson-top-left">
                    <div class="rbt-lesson-toggle">
                        <button class="lesson-toggle-active btn-round-white-opacity" title="Toggle Sidebar">
                            <i class="feather-arrow-left"></i>
                        </button>
                    </div>
                    <h5>{{ $courseInfo->name ?? 'Course Title' }}</h5>
                </div>
                <div class="lesson-top-right">
                    <div class="rbt-btn-close">
                        <a href="{{ route('course.detail', ['id' => $courseInfo->id]) }}" title="Go Back to Course" class="rbt-round-btn">
                            <i class="feather-x"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="inner">
                <div class="custom-player-wrapper" id="customPlayerWrapper">
                    <!-- Top Shield (Completely masks YouTube top title & channel bar) -->
                    <div class="cp-top-shield" id="cpTopShield">
                        <div class="cp-top-left">
                            <span class="cp-shield-badge"><i class="feather-lock"></i> {{ $companyName }} সুরক্ষিত</span>
                            <span class="cp-title-text" id="cpLectureTitle">{{ $courseInfo->name ?? 'Course Title' }}</span>
                        </div>
                        <div class="cp-watermark-tag">
                            <i class="feather-shield"></i> {{ $studentName }}
                        </div>
                    </div>

                    <!-- YouTube Video Frame Container (Pointer events disabled) -->
                    <div class="cp-video-frame">
                        <div id="youtubePlayerTarget"></div>
                    </div>

                    <!-- Full Click Overlay (Catches single/double clicks and blocks context menu) -->
                    <div class="cp-click-overlay" id="cpClickOverlay" oncontextmenu="return false;">
                        <div class="cp-center-bubble" id="cpCenterBubble">
                            <i class="feather-play" id="cpCenterIcon"></i>
                        </div>
                        <div class="cp-skip-feedback left" id="cpSkipLeft"><i class="feather-rotate-ccw"></i> 10s</div>
                        <div class="cp-skip-feedback right" id="cpSkipRight">10s <i class="feather-rotate-cw"></i></div>
                    </div>

                    <!-- Floating Dynamic Anti-Piracy Watermark -->
                    <div class="cp-floating-watermark" id="cpFloatingWatermark">
                        {{ $companyName }} • {{ $studentName }}
                    </div>

                    <!-- Bottom Custom Controls Bar -->
                    <div class="cp-controls-bar" id="cpControlsBar">
                        <div class="cp-timeline-container" id="cpTimelineContainer">
                            <div class="cp-timeline-hover-time" id="cpHoverTime">00:00</div>
                            <div class="cp-timeline-track">
                                <div class="cp-timeline-buffer" id="cpTimelineBuffer"></div>
                                <div class="cp-timeline-played" id="cpTimelinePlayed"></div>
                                <div class="cp-timeline-thumb" id="cpTimelineThumb"></div>
                            </div>
                        </div>

                        <div class="cp-controls-row">
                            <div class="cp-controls-left">
                                <button type="button" class="cp-btn" id="cpPlayPauseBtn" title="Play / Pause (Space)">
                                    <i class="feather-play" id="cpPlayPauseIcon"></i>
                                </button>

                                <button type="button" class="cp-btn cp-seek-btn" id="cpRewind10Btn" title="Rewind 10s (Left Arrow)">
                                    <i class="feather-rotate-ccw"></i>
                                    <span class="cp-seek-text">10</span>
                                </button>

                                <button type="button" class="cp-btn cp-seek-btn" id="cpForward10Btn" title="Forward 10s (Right Arrow)">
                                    <i class="feather-rotate-cw"></i>
                                    <span class="cp-seek-text">10</span>
                                </button>

                                <div class="cp-volume-group">
                                    <button type="button" class="cp-btn" id="cpVolumeBtn" title="Mute / Unmute (m)">
                                        <i class="feather-volume-2" id="cpVolumeIcon"></i>
                                    </button>
                                    <div class="cp-volume-slider-wrapper">
                                        <input type="range" class="cp-volume-slider" id="cpVolumeSlider" min="0" max="100" value="100">
                                    </div>
                                </div>

                                <div class="cp-time-display">
                                    <span id="cpCurrentTime">00:00</span>
                                    <span class="cp-time-separator">/</span>
                                    <span id="cpTotalDuration">00:00</span>
                                </div>
                            </div>

                            <div class="cp-controls-right">
                                <div class="cp-speed-menu-container">
                                    <button type="button" class="cp-btn cp-speed-btn" id="cpSpeedBtn" title="Playback Speed">
                                        <span id="cpSpeedLabel">1x</span>
                                    </button>
                                    <div class="cp-speed-dropdown" id="cpSpeedDropdown">
                                        <div class="cp-speed-item" data-speed="0.5">0.5x</div>
                                        <div class="cp-speed-item" data-speed="0.75">0.75x</div>
                                        <div class="cp-speed-item active" data-speed="1">1x (Normal)</div>
                                        <div class="cp-speed-item" data-speed="1.25">1.25x</div>
                                        <div class="cp-speed-item" data-speed="1.5">1.5x</div>
                                        <div class="cp-speed-item" data-speed="1.75">1.75x</div>
                                        <div class="cp-speed-item" data-speed="2">2x</div>
                                    </div>
                                </div>

                                <button type="button" class="cp-btn" id="cpFullscreenBtn" title="Fullscreen (f)">
                                    <i class="feather-maximize" id="cpFullscreenIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Full Course Overlay (for Paid Videos in Free Preview Mode) -->
                    <div class="cp-locked-overlay {{ $showLockedOnInit ? 'show' : '' }}" id="cpLockedOverlay">
                        <div class="cp-locked-card">
                            <div class="cp-locked-icon">
                                <i class="feather-lock"></i>
                            </div>
                            <h4 id="cpLockedTitle">Paid Lesson — No Access</h4>
                            <p id="cpLockedSubtitle">
                                This lesson is part of the paid course curriculum. Purchase the full course to unlock all chapters and video lectures.
                            </p>
                            <div class="mb-3" style="font-size: 18px; font-weight: 800; color: #ff7a00;">
                                Course Fee: ৳{{ number_format($courseInfo->price ?? 0, 0) }}
                            </div>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <a href="{{ route('user.buycourse', ['productID' => $courseInfo->id]) }}" class="rbt-btn btn-gradient radius-round btn-sm">
                                    <i class="feather-shopping-cart me-1"></i> Purchase Full Course
                                </a>
                                @if($freeVideosCount > 0)
                                    <button type="button" class="rbt-btn btn-border radius-round btn-sm" id="cpBackToFreeBtn" style="color: #fff; border-color: rgba(255,255,255,0.35);">
                                        <i class="feather-play me-1"></i> Watch Free Video
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load YouTube IFrame API -->
<script src="https://www.youtube.com/iframe_api"></script>

<script>
(function() {
    var ytPlayer = null;
    var playerReady = false;
    var updateTimer = null;
    var hideControlsTimer = null;
    var isDragging = false;
    var lastClickTime = 0;

    var wrapper = document.getElementById('customPlayerWrapper');
    var clickOverlay = document.getElementById('cpClickOverlay');
    var playPauseBtn = document.getElementById('cpPlayPauseBtn');
    var playPauseIcon = document.getElementById('cpPlayPauseIcon');
    var centerBubble = document.getElementById('cpCenterBubble');
    var centerIcon = document.getElementById('cpCenterIcon');
    var skipLeft = document.getElementById('cpSkipLeft');
    var skipRight = document.getElementById('cpSkipRight');
    var timelineContainer = document.getElementById('cpTimelineContainer');
    var timelinePlayed = document.getElementById('cpTimelinePlayed');
    var timelineBuffer = document.getElementById('cpTimelineBuffer');
    var timelineThumb = document.getElementById('cpTimelineThumb');
    var hoverTime = document.getElementById('cpHoverTime');
    var currentTimeEl = document.getElementById('cpCurrentTime');
    var totalDurationEl = document.getElementById('cpTotalDuration');
    var volumeBtn = document.getElementById('cpVolumeBtn');
    var volumeIcon = document.getElementById('cpVolumeIcon');
    var volumeSlider = document.getElementById('cpVolumeSlider');
    var speedBtn = document.getElementById('cpSpeedBtn');
    var speedLabel = document.getElementById('cpSpeedLabel');
    var speedDropdown = document.getElementById('cpSpeedDropdown');
    var fullscreenBtn = document.getElementById('cpFullscreenBtn');
    var fullscreenIcon = document.getElementById('cpFullscreenIcon');
    var lectureTitleEl = document.getElementById('cpLectureTitle');
    var floatingWatermark = document.getElementById('cpFloatingWatermark');
    var searchInput = document.getElementById('lessonSearchInput');
    var lockedOverlay = document.getElementById('cpLockedOverlay');
    var lockedTitleEl = document.getElementById('cpLockedTitle');
    var lockedSubtitleEl = document.getElementById('cpLockedSubtitle');
    var backToFreeBtn = document.getElementById('cpBackToFreeBtn');
    var videoLinks = document.querySelectorAll('.video-link');
    var lockedVideoLinks = document.querySelectorAll('.locked-video-link');

    function extractVideoId(rawUrl) {
        if (!rawUrl) return '';
        var clean = rawUrl.trim();
        if (clean.length === 11 && clean.indexOf('/') === -1 && clean.indexOf('=') === -1) {
            return clean;
        }
        var match = clean.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
        return match ? match[1] : clean;
    }

    function formatTime(seconds) {
        var s = Math.floor(seconds || 0);
        var hours = Math.floor(s / 3600);
        var minutes = Math.floor((s % 3600) / 60);
        var secs = s % 60;
        if (hours > 0) {
            return (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ':' + (secs < 10 ? '0' : '') + secs;
        }
        return (minutes < 10 ? '0' : '') + minutes + ':' + (secs < 10 ? '0' : '') + secs;
    }

    function ensureChapterExpanded(chapterCollapse) {
        if (!chapterCollapse) return;
        var collapseId = '#' + chapterCollapse.id;
        try {
            sessionStorage.setItem('selectedCollapse', collapseId);
        } catch (err) {}

        document.querySelectorAll('#accordionExampleb2 .accordion-collapse').forEach(function(col) {
            var btn = col.previousElementSibling ? col.previousElementSibling.querySelector('.accordion-button') : null;
            if (col === chapterCollapse) {
                col.classList.add('show');
                if (btn) {
                    btn.classList.remove('collapsed');
                    btn.setAttribute('aria-expanded', 'true');
                }
            } else {
                col.classList.remove('show');
                if (btn) {
                    btn.classList.add('collapsed');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

    // Resolve initial video (deep-linked .active-lecture or first available lecture)
    var initialLinkEl = document.querySelector('.video-link.active-lecture') || (videoLinks.length > 0 ? videoLinks[0] : null);
    var initialVideoId = '';
    if (initialLinkEl) {
        var initialRawLink = initialLinkEl.getAttribute('data-link');
        if (initialRawLink) {
            initialVideoId = extractVideoId(initialRawLink);
        }
        initialLinkEl.classList.add('active-lecture');
        var initialTitle = initialLinkEl.querySelector('.text');
        if (initialTitle && lectureTitleEl) {
            lectureTitleEl.textContent = initialTitle.textContent.trim();
        }
        var targetCollapse = initialLinkEl.closest('.accordion-collapse');
        if (targetCollapse && targetCollapse.id) {
            try {
                sessionStorage.setItem('selectedCollapse', '#' + targetCollapse.id);
            } catch (err) {}
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    ensureChapterExpanded(targetCollapse);
                }, 0);
            });
        }
    }

    var pendingVideoId = initialVideoId;

    function createYouTubePlayer() {
        if (ytPlayer || !pendingVideoId || typeof YT === 'undefined' || !YT.Player) return;
        ytPlayer = new YT.Player('youtubePlayerTarget', {
            height: '100%',
            width: '100%',
            videoId: pendingVideoId,
            playerVars: {
                'controls': 0,
                'disablekb': 1,
                'modestbranding': 1,
                'rel': 0,
                'showinfo': 0,
                'iv_load_policy': 3,
                'playsinline': 1,
                'fs': 0,
                'origin': window.location.origin
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    window.onYouTubeIframeAPIReady = createYouTubePlayer;
    if (window.YT && window.YT.Player) {
        createYouTubePlayer();
    }

    function onPlayerReady() {
        playerReady = true;
        if (pendingVideoId && pendingVideoId !== initialVideoId) {
            ytPlayer.loadVideoById(pendingVideoId);
        }
        updateDuration();
        if (updateTimer) clearInterval(updateTimer);
        updateTimer = setInterval(updateProgress, 250);
    }

    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.PLAYING) {
            playPauseIcon.className = 'feather-pause';
            showCenterRipple('feather-play');
            resetHideControlsTimer();
        } else if (event.data === YT.PlayerState.PAUSED) {
            playPauseIcon.className = 'feather-play';
            showCenterRipple('feather-pause');
            clearTimeout(hideControlsTimer);
            wrapper.classList.remove('hide-controls');
        } else if (event.data === YT.PlayerState.ENDED) {
            playPauseIcon.className = 'feather-rotate-ccw';
            wrapper.classList.remove('hide-controls');
        }
        updateDuration();
    }

    function showCenterRipple(iconClass) {
        centerIcon.className = iconClass;
        centerBubble.classList.add('active');
        setTimeout(function() {
            centerBubble.classList.remove('active');
        }, 500);
    }

    function togglePlayPause() {
        if (!playerReady || !ytPlayer) return;
        var state = ytPlayer.getPlayerState();
        if (state === YT.PlayerState.PLAYING) {
            ytPlayer.pauseVideo();
        } else {
            ytPlayer.playVideo();
        }
    }

    function updateDuration() {
        if (!playerReady || !ytPlayer) return;
        var dur = ytPlayer.getDuration();
        if (dur && dur > 0) {
            totalDurationEl.textContent = formatTime(dur);
        }
    }

    function updateProgress() {
        if (!playerReady || !ytPlayer || isDragging) return;
        var currentTime = ytPlayer.getCurrentTime() || 0;
        var duration = ytPlayer.getDuration() || 0;
        var loadedFrac = ytPlayer.getVideoLoadedFraction ? ytPlayer.getVideoLoadedFraction() : 0;

        currentTimeEl.textContent = formatTime(currentTime);

        if (duration > 0) {
            var pct = (currentTime / duration) * 100;
            timelinePlayed.style.width = pct + '%';
            timelineThumb.style.left = pct + '%';
            timelineBuffer.style.width = (loadedFrac * 100) + '%';
        }
    }

    function getClientX(e) {
        if (e.touches && e.touches.length > 0) return e.touches[0].clientX;
        if (e.changedTouches && e.changedTouches.length > 0) return e.changedTouches[0].clientX;
        return e.clientX;
    }

    function seekFromEvent(e) {
        if (!playerReady || !ytPlayer) return;
        var rect = timelineContainer.getBoundingClientRect();
        var pos = Math.max(0, Math.min(1, (getClientX(e) - rect.left) / rect.width));
        var duration = ytPlayer.getDuration() || 0;
        var targetTime = pos * duration;
        timelinePlayed.style.width = (pos * 100) + '%';
        timelineThumb.style.left = (pos * 100) + '%';
        currentTimeEl.textContent = formatTime(targetTime);
        ytPlayer.seekTo(targetTime, true);
    }

    timelineContainer.addEventListener('mousedown', function(e) {
        isDragging = true;
        seekFromEvent(e);
    });
    timelineContainer.addEventListener('touchstart', function(e) {
        isDragging = true;
        seekFromEvent(e);
    }, { passive: true });

    window.addEventListener('mousemove', function(e) {
        if (isDragging) seekFromEvent(e);
    });
    window.addEventListener('touchmove', function(e) {
        if (isDragging) seekFromEvent(e);
    }, { passive: true });

    window.addEventListener('mouseup', function() {
        if (isDragging) isDragging = false;
    });
    window.addEventListener('touchend', function() {
        if (isDragging) isDragging = false;
    });

    timelineContainer.addEventListener('mousemove', function(e) {
        if (!playerReady || !ytPlayer) return;
        var rect = timelineContainer.getBoundingClientRect();
        var pos = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
        var duration = ytPlayer.getDuration() || 0;
        hoverTime.style.display = 'block';
        hoverTime.style.left = (pos * 100) + '%';
        hoverTime.textContent = formatTime(pos * duration);
    });

    timelineContainer.addEventListener('mouseleave', function() {
        hoverTime.style.display = 'none';
    });

    function seekRelative(seconds) {
        if (!playerReady || !ytPlayer) return;
        var cur = ytPlayer.getCurrentTime() || 0;
        var target = Math.max(0, Math.min(ytPlayer.getDuration() || 0, cur + seconds));
        ytPlayer.seekTo(target, true);
        updateProgress();
    }

    playPauseBtn.addEventListener('click', togglePlayPause);
    document.getElementById('cpRewind10Btn').addEventListener('click', function() {
        seekRelative(-10);
        skipLeft.classList.add('show');
        setTimeout(function() { skipLeft.classList.remove('show'); }, 500);
    });
    document.getElementById('cpForward10Btn').addEventListener('click', function() {
        seekRelative(10);
        skipRight.classList.add('show');
        setTimeout(function() { skipRight.classList.remove('show'); }, 500);
    });

    clickOverlay.addEventListener('click', function(e) {
        var now = Date.now();
        var rect = clickOverlay.getBoundingClientRect();
        var clickX = e.clientX - rect.left;
        var width = rect.width;

        if (now - lastClickTime < 300) {
            if (clickX < width * 0.35) {
                seekRelative(-10);
                skipLeft.classList.add('show');
                setTimeout(function() { skipLeft.classList.remove('show'); }, 500);
            } else if (clickX > width * 0.65) {
                seekRelative(10);
                skipRight.classList.add('show');
                setTimeout(function() { skipRight.classList.remove('show'); }, 500);
            } else {
                togglePlayPause();
            }
        } else {
            togglePlayPause();
        }
        lastClickTime = now;
    });

    volumeSlider.addEventListener('input', function() {
        if (!playerReady || !ytPlayer) return;
        var val = parseInt(this.value, 10);
        ytPlayer.setVolume(val);
        if (val === 0) {
            volumeIcon.className = 'feather-volume-x';
        } else if (val < 50) {
            volumeIcon.className = 'feather-volume-1';
        } else {
            volumeIcon.className = 'feather-volume-2';
        }
        if (ytPlayer.isMuted()) ytPlayer.unMute();
    });

    volumeBtn.addEventListener('click', function() {
        if (!playerReady || !ytPlayer) return;
        if (ytPlayer.isMuted()) {
            ytPlayer.unMute();
            volumeIcon.className = 'feather-volume-2';
            volumeSlider.value = ytPlayer.getVolume() || 100;
        } else {
            ytPlayer.mute();
            volumeIcon.className = 'feather-volume-x';
            volumeSlider.value = 0;
        }
    });

    speedBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        speedDropdown.classList.toggle('show');
    });

    document.querySelectorAll('.cp-speed-item').forEach(function(item) {
        item.addEventListener('click', function() {
            var rate = parseFloat(this.getAttribute('data-speed'));
            if (playerReady && ytPlayer) {
                ytPlayer.setPlaybackRate(rate);
            }
            speedLabel.textContent = rate + 'x';
            document.querySelectorAll('.cp-speed-item').forEach(function(el) { el.classList.remove('active'); });
            this.classList.add('active');
            speedDropdown.classList.remove('show');
        });
    });

    window.addEventListener('click', function(e) {
        if (!e.target.closest('.cp-speed-menu-container')) {
            speedDropdown.classList.remove('show');
        }
    });

    function toggleFullscreen() {
        if (!document.fullscreenElement) {
            if (wrapper.requestFullscreen) {
                wrapper.requestFullscreen().catch(function() {});
            }
            fullscreenIcon.className = 'feather-minimize';
        } else {
            if (document.exitFullscreen) document.exitFullscreen();
            fullscreenIcon.className = 'feather-maximize';
        }
    }
    fullscreenBtn.addEventListener('click', toggleFullscreen);

    document.addEventListener('fullscreenchange', function() {
        fullscreenIcon.className = document.fullscreenElement ? 'feather-minimize' : 'feather-maximize';
    });

    function isPlaying() {
        return playerReady && ytPlayer && typeof ytPlayer.getPlayerState === 'function' && ytPlayer.getPlayerState() === YT.PlayerState.PLAYING;
    }

    function resetHideControlsTimer() {
        clearTimeout(hideControlsTimer);
        wrapper.classList.remove('hide-controls');
        if (isPlaying()) {
            hideControlsTimer = setTimeout(function() {
                wrapper.classList.add('hide-controls');
            }, 3200);
        }
    }

    wrapper.addEventListener('mousemove', resetHideControlsTimer);
    wrapper.addEventListener('mouseleave', function() {
        if (isPlaying()) {
            wrapper.classList.add('hide-controls');
        }
    });

    window.addEventListener('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (!playerReady || !ytPlayer) return;

        if (e.key === ' ' || e.key === 'k') {
            e.preventDefault();
            togglePlayPause();
        } else if (e.key === 'ArrowLeft' || e.key === 'j') {
            e.preventDefault();
            seekRelative(-10);
        } else if (e.key === 'ArrowRight' || e.key === 'l') {
            e.preventDefault();
            seekRelative(10);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            var curVol = Math.min(100, (ytPlayer.getVolume() || 0) + 5);
            ytPlayer.setVolume(curVol);
            volumeSlider.value = curVol;
            if (ytPlayer.isMuted()) ytPlayer.unMute();
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            var curVolDown = Math.max(0, (ytPlayer.getVolume() || 0) - 5);
            ytPlayer.setVolume(curVolDown);
            volumeSlider.value = curVolDown;
        } else if (e.key === 'f') {
            e.preventDefault();
            toggleFullscreen();
        } else if (e.key === 'm') {
            e.preventDefault();
            volumeBtn.click();
        }
    });

    // Sidebar Lecture Playlist click integration
    videoLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            var rawLink = this.getAttribute('data-link');
            var titleText = this.querySelector('.text') ? this.querySelector('.text').textContent.trim() : '';
            var newVidId = extractVideoId(rawLink);

            if (lockedOverlay) {
                lockedOverlay.classList.remove('show');
            }
            lockedVideoLinks.forEach(function(el) { el.classList.remove('active-locked'); });

            if (lectureTitleEl && titleText) {
                lectureTitleEl.textContent = titleText;
            }

            if (newVidId) {
                pendingVideoId = newVidId;
                if (playerReady && ytPlayer && typeof ytPlayer.loadVideoById === 'function') {
                    ytPlayer.loadVideoById(newVidId);
                }
            }

            videoLinks.forEach(function(el) { el.classList.remove('active-lecture'); });
            this.classList.add('active-lecture');

            if (window.innerWidth <= 991 && wrapper && wrapper.scrollIntoView) {
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    // Locked/Paid video click prompts student to purchase the full course
    lockedVideoLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            if (playerReady && ytPlayer && typeof ytPlayer.pauseVideo === 'function') {
                ytPlayer.pauseVideo();
            }

            var vTitle = this.getAttribute('data-video-title') || 'Paid Lesson';
            var chTitle = this.getAttribute('data-chapter-title') || '';

            if (lockedTitleEl) {
                lockedTitleEl.textContent = vTitle;
            }
            if (lockedSubtitleEl) {
                lockedSubtitleEl.textContent = (chTitle ? chTitle + ' — ' : '') + 'This lesson is locked. Purchase the full course to unlock all chapters and paid videos.';
            }
            if (lockedOverlay) {
                lockedOverlay.classList.add('show');
            }

            lockedVideoLinks.forEach(function(el) { el.classList.remove('active-locked'); });
            this.classList.add('active-locked');

            if (window.innerWidth <= 991 && wrapper && wrapper.scrollIntoView) {
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    });

    if (backToFreeBtn) {
        backToFreeBtn.addEventListener('click', function() {
            if (lockedOverlay) {
                lockedOverlay.classList.remove('show');
            }
            lockedVideoLinks.forEach(function(el) { el.classList.remove('active-locked'); });
            var resumeLink = document.querySelector('.video-link.active-lecture') || (videoLinks.length > 0 ? videoLinks[0] : null);
            if (resumeLink) {
                resumeLink.click();
            }
        });
    }

    // Sidebar Chapter & Lesson Search Filter
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            var query = this.value.trim().toLowerCase();
            var chapterItems = document.querySelectorAll('#accordionExampleb2 .accordion-item');

            chapterItems.forEach(function(item) {
                var chapterBtn = item.querySelector('.accordion-button');
                var chapterTitleText = chapterBtn ? chapterBtn.textContent.toLowerCase() : '';
                var chapterMatches = query !== '' && chapterTitleText.indexOf(query) !== -1;
                var videoItems = item.querySelectorAll('.lesson-video-item');
                var anyVideoVisible = false;

                videoItems.forEach(function(li) {
                    var matchesVideo = query === '' || chapterMatches || li.textContent.toLowerCase().indexOf(query) !== -1;
                    li.style.display = matchesVideo ? '' : 'none';
                    if (matchesVideo) {
                        anyVideoVisible = true;
                    }
                });

                var showItem = (query === '') || chapterMatches || anyVideoVisible;
                item.style.display = showItem ? '' : 'none';

                if (query !== '' && showItem) {
                    var collapse = item.querySelector('.accordion-collapse');
                    if (collapse) {
                        collapse.classList.add('show');
                        if (chapterBtn) {
                            chapterBtn.classList.remove('collapsed');
                            chapterBtn.setAttribute('aria-expanded', 'true');
                        }
                    }
                }
            });
        });
    }

    // Reposition Floating Watermark every 12 seconds to prevent screen recording
    if (floatingWatermark) {
        setInterval(function() {
            var randomTop = Math.floor(Math.random() * 55) + 15;
            var randomLeft = Math.floor(Math.random() * 60) + 10;
            floatingWatermark.style.top = randomTop + '%';
            floatingWatermark.style.left = randomLeft + '%';
            floatingWatermark.style.bottom = 'auto';
            floatingWatermark.style.right = 'auto';
        }, 12000);
    }
})();
</script>
