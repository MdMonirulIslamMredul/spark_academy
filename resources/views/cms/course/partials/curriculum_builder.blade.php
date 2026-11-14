@php
    $initialChapters = old('chapters', $chapters ?? []);
    if (!is_array($initialChapters)) {
        $initialChapters = [];
    }
    $initialChapters = array_values($initialChapters);
@endphp

<div class="col-md-12 mt-4">
    <div class="card border shadow-none" id="curriculumBuilderCard" style="border-color: #dbe2ea !important;">
        <div class="card-header d-flex flex-wrap align-items-center justify-content-between bg-light">
            <div class="d-flex align-items-center">
                <h4 class="mb-0 mr-3">Course Curriculum (Chapters &amp; Videos)</h4>
                <span class="badge badge-primary" id="curriculumSummaryBadge">0 Chapters • 0 Videos</span>
            </div>
            <button type="button" class="btn btn-primary btn-sm" id="addChapterTopBtn">
                <i class="fas fa-plus mr-1"></i> Add Chapter
            </button>
        </div>

        <div class="card-body">
            <div id="emptyChaptersState" class="text-center py-4 px-3 rounded border {{ count($initialChapters) > 0 ? 'd-none' : '' }}" style="border-style: dashed !important; background: #fafbfd;">
                <i class="fas fa-layer-group text-muted mb-2" style="font-size: 28px;"></i>
                <p class="text-muted mb-2">No chapters added yet. You can save the course now or start building chapters and videos.</p>
                <button type="button" class="btn btn-outline-primary btn-sm" id="addChapterEmptyBtn">
                    <i class="fas fa-plus mr-1"></i> Add First Chapter
                </button>
            </div>

            <div id="chaptersContainer">
                @foreach($initialChapters as $cIndex => $chapter)
                    @php
                        $chapterVideos = isset($chapter['videos']) && is_array($chapter['videos'])
                            ? array_values($chapter['videos'])
                            : [];
                        $isExistingChapter = !empty($chapter['id']);
                    @endphp
                    <div class="card mb-3 border chapter-card" data-existing="{{ $isExistingChapter ? '1' : '0' }}" style="border-color: #cfd8e3 !important;">
                        <input type="hidden" class="chapter-id-input" name="chapters[{{ $cIndex }}][id]" value="{{ $chapter['id'] ?? '' }}">

                        <div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between" style="background: #f4f6f9; min-height: auto;">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-light border mr-2 toggle-chapter-body" title="Collapse/Expand Chapter">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                                <span class="badge badge-dark mr-2 chapter-badge">Chapter {{ $cIndex + 1 }}</span>
                                <span class="font-weight-bold text-dark chapter-header-title">{{ $chapter['title'] ?? 'Untitled Chapter' }}</span>
                                <span class="badge badge-info ml-2 chapter-video-count-badge">{{ count($chapterVideos) }} {{ count($chapterVideos) === 1 ? 'Video' : 'Videos' }}</span>
                            </div>
                            <div class="d-flex align-items-center mt-2 mt-sm-0">
                                <label class="mb-0 mr-1 small text-muted font-weight-bold">Order</label>
                                <input type="number" min="0" class="form-control form-control-sm mr-2 chapter-order-input" style="width: 70px;" name="chapters[{{ $cIndex }}][sort_order]" value="{{ $chapter['sort_order'] ?? ($cIndex + 1) }}">
                                <div class="btn-group btn-group-sm mr-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary move-chapter-up" title="Move Chapter Up"><i class="fas fa-arrow-up"></i></button>
                                    <button type="button" class="btn btn-outline-secondary move-chapter-down" title="Move Chapter Down"><i class="fas fa-arrow-down"></i></button>
                                </div>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-chapter-btn" title="Remove Chapter">
                                    <i class="fas fa-trash-alt mr-1"></i> Remove
                                </button>
                            </div>
                        </div>

                        <div class="card-body chapter-card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label><span class="text-danger">*</span> Chapter Title</label>
                                    <input type="text" class="form-control chapter-title-input" name="chapters[{{ $cIndex }}][title]" value="{{ $chapter['title'] ?? '' }}" placeholder="e.g. Chapter 1: Physical World & Measurement" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Chapter Description <small class="text-muted">(Optional)</small></label>
                                    <textarea class="form-control chapter-desc-input" rows="2" name="chapters[{{ $cIndex }}][description]" placeholder="Brief overview of this chapter...">{{ $chapter['description'] ?? '' }}</textarea>
                                </div>
                            </div>

                            <div class="mt-2 pt-3 border-top">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <h6 class="mb-0 font-weight-bold text-secondary">
                                        <i class="fas fa-play-circle mr-1"></i> Chapter Videos
                                    </h6>
                                    <button type="button" class="btn btn-info btn-sm add-video-btn">
                                        <i class="fas fa-plus mr-1"></i> Add Video
                                    </button>
                                </div>

                                <div class="videos-empty-hint text-muted small py-2 px-3 rounded bg-light border {{ count($chapterVideos) > 0 ? 'd-none' : '' }}">
                                    No videos in this chapter yet. Click <strong>Add Video</strong> to attach video lessons.
                                </div>

                                <div class="videos-container">
                                    @foreach($chapterVideos as $vIndex => $video)
                                        @php
                                            $isExistingVideo = !empty($video['id']);
                                            $freePaidVal = ($video['free_paid'] ?? 'paid') === 'free' ? 'free' : 'paid';
                                        @endphp
                                        <div class="border rounded p-2 mb-2 bg-light video-row" data-existing="{{ $isExistingVideo ? '1' : '0' }}">
                                            <input type="hidden" class="video-id-input" name="chapters[{{ $cIndex }}][videos][{{ $vIndex }}][id]" value="{{ $video['id'] ?? '' }}">
                                            <div class="form-row align-items-end">
                                                <div class="form-group col-md-3 mb-2 mb-md-0">
                                                    <label class="small font-weight-bold mb-1">
                                                        <span class="badge badge-secondary mr-1 video-badge">#{{ $vIndex + 1 }}</span>
                                                        <span class="text-danger">*</span> Video Name
                                                    </label>
                                                    <input type="text" class="form-control form-control-sm video-title-input" name="chapters[{{ $cIndex }}][videos][{{ $vIndex }}][title]" value="{{ $video['title'] ?? '' }}" placeholder="Lecture Title" required>
                                                </div>
                                                <div class="form-group col-md-3 mb-2 mb-md-0">
                                                    <label class="small font-weight-bold mb-1"><span class="text-danger">*</span> Video Link</label>
                                                    <input type="text" class="form-control form-control-sm video-link-input" name="chapters[{{ $cIndex }}][videos][{{ $vIndex }}][link]" value="{{ $video['link'] ?? '' }}" placeholder="YouTube URL or Video ID" required>
                                                </div>
                                                <div class="form-group col-md-2 mb-2 mb-md-0">
                                                    <label class="small font-weight-bold mb-1">Access</label>
                                                    <select class="form-control form-control-sm video-free-paid-input" name="chapters[{{ $cIndex }}][videos][{{ $vIndex }}][free_paid]">
                                                        <option value="paid" {{ $freePaidVal === 'paid' ? 'selected' : '' }}>Paid</option>
                                                        <option value="free" {{ $freePaidVal === 'free' ? 'selected' : '' }}>Free</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2 mb-2 mb-md-0">
                                                    <label class="small font-weight-bold mb-1">Duration <small class="text-muted">(Opt.)</small></label>
                                                    <input type="text" class="form-control form-control-sm video-duration-input" name="chapters[{{ $cIndex }}][videos][{{ $vIndex }}][duration]" value="{{ $video['duration'] ?? '' }}" placeholder="e.g. 15 min">
                                                </div>
                                                <div class="form-group col-md-2 mb-0">
                                                    <label class="small font-weight-bold mb-1">Order / Actions</label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="number" min="0" class="form-control form-control-sm mr-1 video-order-input" style="width: 58px;" name="chapters[{{ $cIndex }}][videos][{{ $vIndex }}][sort_order]" value="{{ $video['sort_order'] ?? ($vIndex + 1) }}">
                                                        <div class="btn-group btn-group-sm mr-1" role="group">
                                                            <button type="button" class="btn btn-outline-secondary move-video-up" title="Move Up"><i class="fas fa-arrow-up"></i></button>
                                                            <button type="button" class="btn btn-outline-secondary move-video-down" title="Move Down"><i class="fas fa-arrow-down"></i></button>
                                                        </div>
                                                        <button type="button" class="btn btn-outline-danger btn-sm remove-video-btn" title="Remove Video">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-right mt-2 {{ count($initialChapters) === 0 ? 'd-none' : '' }}" id="addChapterBottomWrapper">
                <button type="button" class="btn btn-outline-primary btn-sm" id="addChapterBottomBtn">
                    <i class="fas fa-plus mr-1"></i> Add Another Chapter
                </button>
            </div>
        </div>
    </div>
</div>

<template id="chapterTemplate">
    <div class="card mb-3 border chapter-card" data-existing="0" style="border-color: #cfd8e3 !important;">
        <input type="hidden" class="chapter-id-input" value="">

        <div class="card-header py-2 px-3 d-flex flex-wrap align-items-center justify-content-between" style="background: #f4f6f9; min-height: auto;">
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-sm btn-light border mr-2 toggle-chapter-body" title="Collapse/Expand Chapter">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <span class="badge badge-dark mr-2 chapter-badge">Chapter 1</span>
                <span class="font-weight-bold text-dark chapter-header-title">Untitled Chapter</span>
                <span class="badge badge-info ml-2 chapter-video-count-badge">0 Videos</span>
            </div>
            <div class="d-flex align-items-center mt-2 mt-sm-0">
                <label class="mb-0 mr-1 small text-muted font-weight-bold">Order</label>
                <input type="number" min="0" class="form-control form-control-sm mr-2 chapter-order-input" style="width: 70px;" value="1">
                <div class="btn-group btn-group-sm mr-2" role="group">
                    <button type="button" class="btn btn-outline-secondary move-chapter-up" title="Move Chapter Up"><i class="fas fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-outline-secondary move-chapter-down" title="Move Chapter Down"><i class="fas fa-arrow-down"></i></button>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm remove-chapter-btn" title="Remove Chapter">
                    <i class="fas fa-trash-alt mr-1"></i> Remove
                </button>
            </div>
        </div>

        <div class="card-body chapter-card-body">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label><span class="text-danger">*</span> Chapter Title</label>
                    <input type="text" class="form-control chapter-title-input" value="" placeholder="e.g. Chapter 1: Physical World & Measurement" required>
                </div>
                <div class="form-group col-md-6">
                    <label>Chapter Description <small class="text-muted">(Optional)</small></label>
                    <textarea class="form-control chapter-desc-input" rows="2" placeholder="Brief overview of this chapter..."></textarea>
                </div>
            </div>

            <div class="mt-2 pt-3 border-top">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="mb-0 font-weight-bold text-secondary">
                        <i class="fas fa-play-circle mr-1"></i> Chapter Videos
                    </h6>
                    <button type="button" class="btn btn-info btn-sm add-video-btn">
                        <i class="fas fa-plus mr-1"></i> Add Video
                    </button>
                </div>

                <div class="videos-empty-hint text-muted small py-2 px-3 rounded bg-light border">
                    No videos in this chapter yet. Click <strong>Add Video</strong> to attach video lessons.
                </div>

                <div class="videos-container"></div>
            </div>
        </div>
    </div>
</template>

<template id="videoTemplate">
    <div class="border rounded p-2 mb-2 bg-light video-row" data-existing="0">
        <input type="hidden" class="video-id-input" value="">
        <div class="form-row align-items-end">
            <div class="form-group col-md-3 mb-2 mb-md-0">
                <label class="small font-weight-bold mb-1">
                    <span class="badge badge-secondary mr-1 video-badge">#1</span>
                    <span class="text-danger">*</span> Video Name
                </label>
                <input type="text" class="form-control form-control-sm video-title-input" value="" placeholder="Lecture Title" required>
            </div>
            <div class="form-group col-md-3 mb-2 mb-md-0">
                <label class="small font-weight-bold mb-1"><span class="text-danger">*</span> Video Link</label>
                <input type="text" class="form-control form-control-sm video-link-input" value="" placeholder="YouTube URL or Video ID" required>
            </div>
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="small font-weight-bold mb-1">Access</label>
                <select class="form-control form-control-sm video-free-paid-input">
                    <option value="paid" selected>Paid</option>
                    <option value="free">Free</option>
                </select>
            </div>
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="small font-weight-bold mb-1">Duration <small class="text-muted">(Opt.)</small></label>
                <input type="text" class="form-control form-control-sm video-duration-input" value="" placeholder="e.g. 15 min">
            </div>
            <div class="form-group col-md-2 mb-0">
                <label class="small font-weight-bold mb-1">Order / Actions</label>
                <div class="d-flex align-items-center">
                    <input type="number" min="0" class="form-control form-control-sm mr-1 video-order-input" style="width: 58px;" value="1">
                    <div class="btn-group btn-group-sm mr-1" role="group">
                        <button type="button" class="btn btn-outline-secondary move-video-up" title="Move Up"><i class="fas fa-arrow-up"></i></button>
                        <button type="button" class="btn btn-outline-secondary move-video-down" title="Move Down"><i class="fas fa-arrow-down"></i></button>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm remove-video-btn" title="Remove Video">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var builder = document.getElementById('curriculumBuilderCard');
    if (!builder) {
        return;
    }

    var chaptersContainer = document.getElementById('chaptersContainer');
    var emptyChaptersState = document.getElementById('emptyChaptersState');
    var addChapterBottomWrapper = document.getElementById('addChapterBottomWrapper');
    var summaryBadge = document.getElementById('curriculumSummaryBadge');
    var chapterTemplate = document.getElementById('chapterTemplate');
    var videoTemplate = document.getElementById('videoTemplate');

    function reindexAll() {
        var chapterCards = Array.prototype.slice.call(chaptersContainer.querySelectorAll(':scope > .chapter-card'));
        var totalChapters = chapterCards.length;
        var totalVideos = 0;

        if (totalChapters === 0) {
            emptyChaptersState.classList.remove('d-none');
            addChapterBottomWrapper.classList.add('d-none');
        } else {
            emptyChaptersState.classList.add('d-none');
            addChapterBottomWrapper.classList.remove('d-none');
        }

        chapterCards.forEach(function (card, cIdx) {
            card.querySelector('.chapter-badge').textContent = 'Chapter ' + (cIdx + 1);
            card.querySelector('.chapter-id-input').setAttribute('name', 'chapters[' + cIdx + '][id]');
            card.querySelector('.chapter-title-input').setAttribute('name', 'chapters[' + cIdx + '][title]');
            card.querySelector('.chapter-desc-input').setAttribute('name', 'chapters[' + cIdx + '][description]');
            card.querySelector('.chapter-order-input').setAttribute('name', 'chapters[' + cIdx + '][sort_order]');

            var upBtn = card.querySelector('.move-chapter-up');
            var downBtn = card.querySelector('.move-chapter-down');
            if (upBtn) upBtn.disabled = (cIdx === 0);
            if (downBtn) downBtn.disabled = (cIdx === totalChapters - 1);

            var videosContainer = card.querySelector('.videos-container');
            var emptyHint = card.querySelector('.videos-empty-hint');
            var videoRows = Array.prototype.slice.call(videosContainer.querySelectorAll(':scope > .video-row'));
            var chapterVideoCount = videoRows.length;
            totalVideos += chapterVideoCount;

            card.querySelector('.chapter-video-count-badge').textContent =
                chapterVideoCount + (chapterVideoCount === 1 ? ' Video' : ' Videos');

            if (chapterVideoCount === 0) {
                emptyHint.classList.remove('d-none');
            } else {
                emptyHint.classList.add('d-none');
            }

            videoRows.forEach(function (row, vIdx) {
                row.querySelector('.video-badge').textContent = '#' + (vIdx + 1);
                row.querySelector('.video-id-input').setAttribute('name', 'chapters[' + cIdx + '][videos][' + vIdx + '][id]');
                row.querySelector('.video-title-input').setAttribute('name', 'chapters[' + cIdx + '][videos][' + vIdx + '][title]');
                row.querySelector('.video-link-input').setAttribute('name', 'chapters[' + cIdx + '][videos][' + vIdx + '][link]');
                row.querySelector('.video-free-paid-input').setAttribute('name', 'chapters[' + cIdx + '][videos][' + vIdx + '][free_paid]');
                row.querySelector('.video-duration-input').setAttribute('name', 'chapters[' + cIdx + '][videos][' + vIdx + '][duration]');
                row.querySelector('.video-order-input').setAttribute('name', 'chapters[' + cIdx + '][videos][' + vIdx + '][sort_order]');

                var vUpBtn = row.querySelector('.move-video-up');
                var vDownBtn = row.querySelector('.move-video-down');
                if (vUpBtn) vUpBtn.disabled = (vIdx === 0);
                if (vDownBtn) vDownBtn.disabled = (vIdx === chapterVideoCount - 1);
            });
        });

        summaryBadge.textContent =
            totalChapters + (totalChapters === 1 ? ' Chapter' : ' Chapters') +
            ' • ' +
            totalVideos + (totalVideos === 1 ? ' Video' : ' Videos');
    }

    function syncSequentialOrders(elements, inputSelector) {
        elements.forEach(function (el, idx) {
            var orderInput = el.querySelector(inputSelector);
            if (orderInput) {
                orderInput.value = idx + 1;
            }
        });
    }

    function addChapter() {
        var fragment = document.importNode(chapterTemplate.content, true);
        var card = fragment.querySelector('.chapter-card');
        var nextOrder = chaptersContainer.querySelectorAll(':scope > .chapter-card').length + 1;
        card.querySelector('.chapter-order-input').value = nextOrder;
        chaptersContainer.appendChild(fragment);
        reindexAll();
        var titleInput = card.querySelector('.chapter-title-input');
        if (titleInput) titleInput.focus();
    }

    function addVideo(chapterCard) {
        var videosContainer = chapterCard.querySelector('.videos-container');
        var fragment = document.importNode(videoTemplate.content, true);
        var row = fragment.querySelector('.video-row');
        var nextOrder = videosContainer.querySelectorAll(':scope > .video-row').length + 1;
        row.querySelector('.video-order-input').value = nextOrder;
        videosContainer.appendChild(fragment);
        var body = chapterCard.querySelector('.chapter-card-body');
        if (body && body.classList.contains('d-none')) {
            body.classList.remove('d-none');
            var icon = chapterCard.querySelector('.toggle-chapter-body i');
            if (icon) {
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-down');
            }
        }
        reindexAll();
        var titleInput = row.querySelector('.video-title-input');
        if (titleInput) titleInput.focus();
    }

    ['addChapterTopBtn', 'addChapterEmptyBtn', 'addChapterBottomBtn'].forEach(function (id) {
        var btn = document.getElementById(id);
        if (btn) {
            btn.addEventListener('click', addChapter);
        }
    });

    chaptersContainer.addEventListener('input', function (e) {
        if (e.target.classList.contains('chapter-title-input')) {
            var card = e.target.closest('.chapter-card');
            var headerTitle = card ? card.querySelector('.chapter-header-title') : null;
            if (headerTitle) {
                headerTitle.textContent = e.target.value.trim() || 'Untitled Chapter';
            }
        }
    });

    chaptersContainer.addEventListener('click', function (e) {
        var target = e.target.closest('button');
        if (!target) {
            return;
        }

        if (target.classList.contains('toggle-chapter-body')) {
            var card = target.closest('.chapter-card');
            var body = card.querySelector('.chapter-card-body');
            var icon = target.querySelector('i');
            body.classList.toggle('d-none');
            if (icon) {
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-right');
            }
            return;
        }

        if (target.classList.contains('add-video-btn')) {
            addVideo(target.closest('.chapter-card'));
            return;
        }

        if (target.classList.contains('remove-chapter-btn')) {
            var chapterCard = target.closest('.chapter-card');
            var isExisting = chapterCard.getAttribute('data-existing') === '1';
            var hasVideos = chapterCard.querySelectorAll('.video-row').length > 0;
            if ((isExisting || hasVideos) && !confirm('Are you sure you want to remove this chapter and all of its videos?')) {
                return;
            }
            chapterCard.remove();
            reindexAll();
            return;
        }

        if (target.classList.contains('move-chapter-up')) {
            var cCardUp = target.closest('.chapter-card');
            var prevCard = cCardUp.previousElementSibling;
            if (prevCard) {
                chaptersContainer.insertBefore(cCardUp, prevCard);
                syncSequentialOrders(
                    Array.prototype.slice.call(chaptersContainer.querySelectorAll(':scope > .chapter-card')),
                    '.chapter-order-input'
                );
                reindexAll();
            }
            return;
        }

        if (target.classList.contains('move-chapter-down')) {
            var cCardDown = target.closest('.chapter-card');
            var nextCard = cCardDown.nextElementSibling;
            if (nextCard) {
                chaptersContainer.insertBefore(nextCard, cCardDown);
                syncSequentialOrders(
                    Array.prototype.slice.call(chaptersContainer.querySelectorAll(':scope > .chapter-card')),
                    '.chapter-order-input'
                );
                reindexAll();
            }
            return;
        }

        if (target.classList.contains('remove-video-btn')) {
            var videoRow = target.closest('.video-row');
            if (videoRow.getAttribute('data-existing') === '1' && !confirm('Are you sure you want to remove this video?')) {
                return;
            }
            videoRow.remove();
            reindexAll();
            return;
        }

        if (target.classList.contains('move-video-up')) {
            var vRowUp = target.closest('.video-row');
            var vContainerUp = vRowUp.parentElement;
            var prevVideo = vRowUp.previousElementSibling;
            if (prevVideo) {
                vContainerUp.insertBefore(vRowUp, prevVideo);
                syncSequentialOrders(
                    Array.prototype.slice.call(vContainerUp.querySelectorAll(':scope > .video-row')),
                    '.video-order-input'
                );
                reindexAll();
            }
            return;
        }

        if (target.classList.contains('move-video-down')) {
            var vRowDown = target.closest('.video-row');
            var vContainerDown = vRowDown.parentElement;
            var nextVideo = vRowDown.nextElementSibling;
            if (nextVideo) {
                vContainerDown.insertBefore(nextVideo, vRowDown);
                syncSequentialOrders(
                    Array.prototype.slice.call(vContainerDown.querySelectorAll(':scope > .video-row')),
                    '.video-order-input'
                );
                reindexAll();
            }
        }
    });

    // Auto-expand collapsed chapter card if native HTML5 validation fails inside it
    var parentForm = builder.closest('form');
    if (parentForm) {
        parentForm.addEventListener('invalid', function (e) {
            var chapterCard = e.target.closest('.chapter-card');
            if (chapterCard) {
                var body = chapterCard.querySelector('.chapter-card-body');
                if (body && body.classList.contains('d-none')) {
                    body.classList.remove('d-none');
                    var icon = chapterCard.querySelector('.toggle-chapter-body i');
                    if (icon) {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                    }
                }
            }
        }, true);
    }

    reindexAll();
});
</script>

