@extends('user.pages.test_master')

@push('frontend_style')
<style>
    .video-container {
        position: relative;
        padding-bottom: 56.25%; /* 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
        max-width: 100%;
        background: #000;
    }

    .video-container iframe,
    .video-container embed,
    .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 0;
    }

    /* Protective overlay shields to block YouTube external links and buttons */
    .yt-shield-top {
        position: absolute;
        top: 0;
        left: 0;
        right: 150px;
        height: 54px;
        z-index: 12;
        background: #000000;
        pointer-events: auto;
        display: flex;
        align-items: center;
        padding: 0 16px;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
    }

    .yt-shield-top .classroom-watermark {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Blocks and masks the bottom-left YouTube copy-link button completely */
    .yt-shield-share {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 145px;
        height: 64px;
        z-index: 15;
        pointer-events: auto;
        background: #000000;
        border-top-right-radius: 8px;
        display: flex;
        align-items: center;
        padding-left: 14px;
        color: #03c6bb;
        cursor: default;
        box-shadow: 2px -2px 10px rgba(0, 0, 0, 0.6);
    }

    .yt-shield-share .share-block-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(15, 23, 42, 0.95);
        border: 1px solid rgba(3, 198, 187, 0.4);
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 700;
        color: #03c6bb;
        box-shadow: 0 2px 8px rgba(0,0,0,0.5);
    }

    /* Blocks & completely masks bottom-right 'Watch on YouTube' button */
    .yt-shield-watch-yt {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 235px;
        height: 56px;
        z-index: 12;
        pointer-events: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 12px;
        background: linear-gradient(90deg, transparent 0%, rgba(0, 0, 0, 0.95) 18%, #000 100%);
    }

    .yt-shield-watch-yt .protect-badge {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(4px);
        color: #03c6bb;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 5px 10px;
        border-radius: 4px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.5);
        border: 1px solid rgba(3, 198, 187, 0.35);
        pointer-events: auto;
    }

    @media (max-width: 768px) {
        .video_portion {
            display: none; /* Hide the fixed position container on mobile */
        }

        .video-modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.8); /* Black background with opacity */
        }

        .video-modal-content {
            background-color: #000;
            margin: 15% auto; /* Center modal */
            padding: 0;
            border: 1px solid #888;
            width: 90%; /* 90% of screen width */
            max-width: 900px;
            height: 80%; /* Set height */
            position: relative;
        }

        .video-modal-content iframe {
            width: 100%;
            height: 100%;
        }

        .close {
            color: #fff;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
            cursor: pointer;
        }

        .close:hover {
            color: #ddd;
        }
    }
</style>
@endpush

@section('content')
<div class="rbt-lesson-area bg-color-white">
    <div class="rbt-lesson-content-wrapper">
        <div class="rbt-lesson-rightsidebar video_portion overflow-hidden lesson-video">
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
                <div class="video-container" id="playerWrapper" oncontextmenu="return false;">
                    <div class="yt-shield-top">
                        <div class="classroom-watermark" id="topShieldTitle">
                            <i class="feather-lock text-success" style="font-size: 13px;"></i>
                            <span>{{ $courseInfo->name ?? 'Course Title' }}</span>
                        </div>
                    </div>
                    <iframe id="lessonIframe"
                            src=""
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>
                    <div class="yt-shield-share" title="পাঠজল্পনা সুরক্ষিত ক্লাস">
                        <span class="share-block-badge"><i class="feather-lock"></i> সুরক্ষিত</span>
                    </div>
                    <div class="yt-shield-watch-yt">
                        <span class="protect-badge"><i class="feather-shield me-1"></i> পাঠজল্পনা সুরক্ষিত</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rbt-lesson-leftsidebar">
            <div class="rbt-course-feature-inner rbt-search-activation">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Course Content</h4>
                </div>

                <div class="lesson-search-wrapper">
                    <form action="#" class="rbt-search-style-1">
                        <input class="rbt-search-active" type="text" placeholder="Search Lesson">
                        <button class="search-btn disabled"><i class="feather-search"></i></button>
                    </form>
                </div>

                <hr class="mt--10">

                <div class="rbt-accordion-style rbt-accordion-02 for-right-content accordion">
                    <div class="accordion" id="accordionExampleb2">
                        <div class="accordion-item card">
                            <h2 class="accordion-header card-header" id="headingTwo1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" data-bs-target="#collapseTwo1" aria-controls="collapseTwo1">
                                    Welcome Course {{ $courseInfo->name ?? 'Course Title' }}
                                </button>
                            </h2>
                            <div id="collapseTwo1" class="accordion-collapse collapse show" aria-labelledby="headingTwo1">
                                <div class="accordion-body card-body">
                                    <ul class="rbt-course-main-content liststyle">
                                        @forelse ($modules as $module)
                                            <li>
                                                <a href="#" class="video-link" data-link="{{ $module->link }}">
                                                    <div class="course-content-left">
                                                        <i class="feather-play-circle"></i> <span class="text">{{ $module->title }}</span>
                                                    </div>
                                                    <div class="course-content-right">
                                                        <span class="min-lable"></span>
                                                        <span class="rbt-check"><i class="feather-check"></i></span>
                                                    </div>
                                                </a>
                                            </li>
                                        @empty
                                            <li>No videos found</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Video Modal -->
    <div id="videoModal" class="video-modal">
        <div class="video-modal-content">
            <span class="close">&times;</span>
            <iframe id="modalIframe" width="100%" height="100%" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var videoLinks = document.querySelectorAll('.video-link');
            var videoModal = document.getElementById('videoModal');
            var modalIframe = document.getElementById('modalIframe');
            var closeModal = document.querySelector('.close');
            var topShieldTitle = document.getElementById('topShieldTitle');

            function getEmbedUrl(rawUrl) {
                if (!rawUrl) return '';
                var videoId = '';
                var clean = rawUrl.trim();

                if (clean.indexOf('embed/') !== -1) {
                    videoId = clean.split('embed/')[1].split('?')[0].split('&')[0];
                } else if (clean.indexOf('watch?v=') !== -1) {
                    videoId = clean.split('watch?v=')[1].split('&')[0];
                } else if (clean.indexOf('youtu.be/') !== -1) {
                    videoId = clean.split('youtu.be/')[1].split('?')[0].split('&')[0];
                } else {
                    videoId = clean;
                }

                return 'https://www.youtube-nocookie.com/embed/' + videoId + '?autoplay=1&modestbranding=1&rel=0&iv_load_policy=3&playsinline=1&controls=1&showinfo=0';
            }

            videoLinks.forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    var moduleLink = this.getAttribute('data-link');
                    var lectureTitle = this.querySelector('.text') ? this.querySelector('.text').textContent.trim() : '';

                    if (topShieldTitle && lectureTitle) {
                        topShieldTitle.innerHTML = '<i class="feather-play-circle text-info" style="font-size: 13px;"></i> <span>' + lectureTitle + '</span>';
                    }

                    var embedLink = getEmbedUrl(moduleLink);
                    if (window.innerWidth <= 768) {
                        modalIframe.src = embedLink;
                        videoModal.style.display = "block";
                    } else {
                        var iframe = document.getElementById('lessonIframe');
                        iframe.src = embedLink;
                    }
                });
            });

            if (videoLinks.length > 0) {
                var firstLink = videoLinks[0];
                var firstModuleLink = firstLink.getAttribute('data-link');
                var firstTitle = firstLink.querySelector('.text') ? firstLink.querySelector('.text').textContent.trim() : '';
                if (firstModuleLink) {
                    var iframe = document.getElementById('lessonIframe');
                    iframe.src = getEmbedUrl(firstModuleLink);
                    if (topShieldTitle && firstTitle) {
                        topShieldTitle.innerHTML = '<i class="feather-play-circle text-info" style="font-size: 13px;"></i> <span>' + firstTitle + '</span>';
                    }
                }
            }

            closeModal.addEventListener('click', function() {
                videoModal.style.display = "none";
                modalIframe.src = "";
            });

            window.addEventListener('click', function(event) {
                if (event.target == videoModal) {
                    videoModal.style.display = "none";
                    modalIframe.src = "";
                }
            });
        });
    </script>
@endsection
