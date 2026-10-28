@extends('user.pages.test_master')
@section('content')
<div class="rbt-lesson-area bg-color-white">
    <div class="rbt-lesson-content-wrapper">
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
                                    Welcome Course {{ $courseInfo->name }}
                                </button>
                            </h2>
                            <div id="collapseTwo1" class="accordion-collapse collapse show" aria-labelledby="headingTwo1">
                                <div class="accordion-body card-body">
                                    <ul class="rbt-course-main-content liststyle">
                                        @foreach ($modules as $module)
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
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="accordion-item card">
                            <h2 class="accordion-header card-header" id="headingTwo4">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" aria-expanded="true" data-bs-target="#collapseTwo4" aria-controls="collapseTwo4">
                                    Welcome Lessons <span class="rbt-badge-5 ml--10">1/3</span>
                                </button>
                            </h2>
                            <div id="collapseTwo4" class="accordion-collapse collapse show" aria-labelledby="headingTwo4">
                                <div class="accordion-body card-body">
                                    <ul class="rbt-course-main-content liststyle">

                                        <li>
                                            <a href="lesson.html">
                                                <div class="course-content-left">
                                                    <i class="feather-play-circle"></i> <span class="text">Hello World!
                                    </span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="min-lable">0.37</span>
                                                    <span class="rbt-check"><i class="feather-check"></i></span>
                                                </div>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <div class="course-content-left">
                                                    <i class="feather-play-circle"></i> <span class="text">Values and Variables</span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="min-lable">20 min</span>
                                                    <span class="rbt-check unread"><i class="feather-circle"></i></span>
                                                </div>
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <div class="course-content-left">
                                                    <i class="feather-play-circle"></i> <span class="text">Basic Operators
                                    </span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="min-lable">15 min</span>
                                                    <span class="rbt-check unread"><i class="feather-circle"></i></span>
                                                </div>
                                            </a>
                                        </li>


                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card">
                            <h2 class="accordion-header card-header" id="headingTwo2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseTwo2" aria-controls="collapseTwo2">
                                    Histudy Quiz <span class="rbt-badge-5 ml--10">1/2</span>
                                </button>
                            </h2>
                            <div id="collapseTwo2" class="accordion-collapse collapse" aria-labelledby="headingTwo2">
                                <div class="accordion-body card-body">
                                    <ul class="rbt-course-main-content liststyle">
                                        <li>
                                            <a href="lesson-quiz.html">
                                                <div class="course-content-left">
                                                    <i class="feather-help-circle"></i> <span class="text">Histudy Quiz Start</span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="rbt-check unread"><i class="feather-circle"></i></span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="lesson-quiz-result.html">
                                                <div class="course-content-left">
                                                    <i class="feather-help-circle"></i> <span class="text">Histudy Quiz Result</span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="rbt-check unread"><i class="feather-circle"></i></span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item card">
                            <h2 class="accordion-header card-header" id="headingTwo3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" aria-expanded="false" data-bs-target="#collapseTwo3" aria-controls="collapseTwo3">
                                    Histudy Assignments <span class="rbt-badge-5 ml--10">1/2</span>
                                </button>
                            </h2>
                            <div id="collapseTwo3" class="accordion-collapse collapse" aria-labelledby="headingTwo3">
                                <div class="accordion-body card-body">
                                    <ul class="rbt-course-main-content liststyle">
                                        <li>
                                            <a href="lesson-assignments.html">
                                                <div class="course-content-left">
                                                    <i class="feather-file-text"></i> <span class="text">Histudy Assignments</span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="rbt-check unread"><i class="feather-circle"></i></span>
                                                </div>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="lesson-assignments-submit.html">
                                                <div class="course-content-left">
                                                    <i class="feather-file-text"></i> <span class="text">Histudy Assignments Submit</span>
                                                </div>
                                                <div class="course-content-right">
                                                    <span class="rbt-check unread"><i class="feather-circle"></i></span>
                                                </div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}

                    </div>


                </div>
            </div>
        </div>

        <div class="rbt-lesson-rightsidebar overflow-hidden lesson-video">
            <div class="lesson-top-bar">
                <div class="lesson-top-left">
                    <div class="rbt-lesson-toggle">
                        <button class="lesson-toggle-active btn-round-white-opacity" title="Toggle Sidebar"><i class="feather-arrow-left"></i></button>
                    </div>
                    <h5>{{ $courseInfo->name }}</h5>
                </div>
                <div class="lesson-top-right">
                    <div class="rbt-btn-close">
                        <a href="{{ route('course.detail',$courseInfo->id) }}" title="Go Back to Course" class="rbt-round-btn"><i class="feather-x"></i></a>
                    </div>
                </div>
            </div>
            <style>
                .video-container {
                    position: relative;
                    padding-bottom: 56.25%; /* 16:9 aspect ratio */
                    height: 0;
                    overflow: hidden;
                    max-width: 100%;
                    background: #000;
                    user-select: none;
                    -webkit-user-select: none;
                }

                .video-container iframe {
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
            </style>
            <div class="inner">
                <div class="video-container" id="playerWrapper" oncontextmenu="return false;">
                    <!-- Top shield: solid black background to completely hide YouTube's title & BenMarquezTX text -->
                    <div class="yt-shield-top">
                        <div class="classroom-watermark" id="topShieldTitle">
                            <i class="feather-lock text-success" style="font-size: 13px;"></i>
                            <span>{{ $courseInfo->name }}</span>
                        </div>
                    </div>

                    <!-- Video Iframe -->
                    <iframe id="lessonIframe"
                            src=""
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                    </iframe>

                    <!-- Bottom-left shield: covers the round copy-link button completely with an LMS shield badge -->
                    <div class="yt-shield-share" title="পাঠজল্পনা সুরক্ষিত ক্লাস">
                        <span class="share-block-badge"><i class="feather-lock"></i> সুরক্ষিত</span>
                    </div>

                    <!-- Bottom-right shield: blocks and masks the 'Watch on YouTube' button -->
                    <div class="yt-shield-watch-yt">
                        <span class="protect-badge"><i class="feather-shield me-1"></i> পাঠজল্পনা সুরক্ষিত</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var videoLinks = document.querySelectorAll('.video-link');
                    var iframe = document.getElementById('lessonIframe');
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

                        // Embed with strict parameters: modestbranding, no related videos, disable annotations
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

                            iframe.src = getEmbedUrl(moduleLink);

                            // Highlight active lecture
                            videoLinks.forEach(function(el) { el.classList.remove('active-lecture'); });
                            this.classList.add('active-lecture');
                        });
                    });

                    // Auto-load the first lecture video if available
                    if (videoLinks.length > 0) {
                        var firstLink = videoLinks[0];
                        var firstModuleLink = firstLink.getAttribute('data-link');
                        var firstTitle = firstLink.querySelector('.text') ? firstLink.querySelector('.text').textContent.trim() : '';
                        if (firstModuleLink) {
                            iframe.src = getEmbedUrl(firstModuleLink);
                            if (topShieldTitle && firstTitle) {
                                topShieldTitle.innerHTML = '<i class="feather-play-circle text-info" style="font-size: 13px;"></i> <span>' + firstTitle + '</span>';
                            }
                            firstLink.classList.add('active-lecture');
                        }
                    }
                });
            </script>
@endsection
