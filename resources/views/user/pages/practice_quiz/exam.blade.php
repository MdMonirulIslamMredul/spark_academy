@extends('user.pages.test_master')

@push('test_style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .exam-sticky-header {
        position: sticky;
        top: 0;
        z-index: 1020;
        background: #0f172a;
        color: #ffffff;
        padding: 16px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        border-bottom: 2px solid #03c6bb;
    }
    .timer-badge {
        background: #1e293b;
        border: 2px solid #03c6bb;
        color: #2dd4bf;
        font-family: 'Courier New', Courier, monospace;
        font-weight: 800;
        font-size: 22px;
        padding: 6px 18px;
        border-radius: 30px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .timer-badge.urgent {
        border-color: #ef4444;
        color: #ef4444;
        background: rgba(239, 68, 68, 0.15);
        animation: pulseUrgent 1s infinite alternate;
    }
    @keyframes pulseUrgent {
        from { transform: scale(1); }
        to { transform: scale(1.05); }
    }
    .exam-q-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 20px;
        transition: border-color 0.2s ease;
    }
    .exam-q-card.is-answered {
        border-left: 5px solid #10b981 !important;
    }
    .exam-opt-label {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 18px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 15px;
    }
    .exam-opt-label:hover {
        border-color: #03c6bb;
        background: #f0fdfa;
    }
    .exam-opt-input {
        width: 18px;
        height: 18px;
        accent-color: #03c6bb;
        cursor: pointer;
    }
    .exam-opt-input:checked + .exam-opt-text {
        font-weight: 700;
        color: #0f172a;
    }
    .exam-opt-label.checked {
        border-color: #03c6bb;
        background: rgba(3, 198, 187, 0.08);
    }
    .q-nav-pill {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 13px;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        color: #475569;
        text-decoration: none;
        transition: all 0.2s;
    }
    .q-nav-pill.answered {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
    }

    /* Dark Mode */
    html.dark-theme .exam-q-card,
    [data-theme="dark"] .exam-q-card,
    body.dark .exam-q-card,
    body.dark-theme .exam-q-card {
        background: #1e293b !important;
        border-color: #334155 !important;
    }
    html.dark-theme .exam-opt-label,
    [data-theme="dark"] .exam-opt-label,
    body.dark .exam-opt-label,
    body.dark-theme .exam-opt-label {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    html.dark-theme .exam-opt-label:hover,
    [data-theme="dark"] .exam-opt-label:hover,
    body.dark .exam-opt-label:hover,
    body.dark-theme .exam-opt-label:hover {
        background: #16243b !important;
        border-color: #03c6bb !important;
    }
    html.dark-theme .exam-opt-label.checked,
    [data-theme="dark"] .exam-opt-label.checked,
    body.dark .exam-opt-label.checked,
    body.dark-theme .exam-opt-label.checked {
        background: rgba(3, 198, 187, 0.18) !important;
        border-color: #03c6bb !important;
    }
    html.dark-theme .text-dark-title,
    [data-theme="dark"] .text-dark-title,
    body.dark .text-dark-title,
    body.dark-theme .text-dark-title {
        color: #f8fafc !important;
    }
    html.dark-theme .q-nav-pill,
    [data-theme="dark"] .q-nav-pill,
    body.dark .q-nav-pill,
    body.dark-theme .q-nav-pill {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')
<!-- Sticky Header with Countdown Timer -->
<div class="exam-sticky-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <span class="badge bg-primary text-white mb-1" style="font-size: 11px;">Chapter Exam</span>
                <h5 class="mb-0 text-white font-weight-bold" style="font-size: 16px;">
                    {{ $attempt->chapter->title }}
                </h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="small text-white-50 d-none d-sm-inline">Remaining Time:</span>
                    <div class="timer-badge" id="examTimerBadge">
                        <i class="feather-clock"></i>
                        <span id="examTimerDisplay">--:--</span>
                    </div>
                </div>
                <button type="button" class="btn btn-success rounded-pill px-4 font-weight-bold shadow-sm" onclick="confirmSubmitExam();">
                    <i class="feather-check-circle me-1"></i> Submit Exam
                </button>
            </div>
        </div>
    </div>
</div>

<div class="rbt-section-gap bg-color-white py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Left / Main: Questions Column -->
            <div class="col-lg-8 col-12">
                <form id="chapterExamForm" action="{{ route('practice_quiz.exam.submit', $attempt->id) }}" method="POST">
                    @csrf
                    @foreach($attempt->answers as $index => $ans)
                        @php $q = $ans->question; @endphp
                        @if($q)
                        <div class="exam-q-card {{ !empty($ans->selected_option) ? 'is-answered' : '' }}" id="q-block-{{ $index + 1 }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge rounded-pill" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; font-weight: 700; font-size: 13px; padding: 4px 12px;">
                                    প্রশ্ন {{ $index + 1 }} / {{ $attempt->answers->count() }}
                                </span>
                                <span class="small text-muted save-indicator" id="save-ind-{{ $q->id }}" style="font-size: 12px;">
                                    {{ !empty($ans->selected_option) ? '✔ উত্তর সেভড' : '' }}
                                </span>
                            </div>

                            <h5 class="text-dark-title mb-3" style="font-weight: 700; font-size: 17px; line-height: 1.5;">
                                {{ $q->question }}
                            </h5>

                            @if(!empty($q->image))
                                <div class="text-center my-3">
                                    <img src="{{ str_starts_with($q->image, 'http') ? $q->image : asset($q->image) }}" alt="উদ্দীপক/চিত্র" class="img-fluid rounded-3" style="max-height: 220px;">
                                </div>
                            @endif

                            <div class="options-container">
                                @php
                                    $options = [
                                        1 => $q->optn1,
                                        2 => $q->optn2,
                                        3 => $q->optn3,
                                        4 => $q->optn4,
                                        5 => $q->optn5,
                                    ];
                                    $letters = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E'];
                                @endphp
                                @foreach($options as $optNum => $optText)
                                    @if(!empty($optText))
                                        <label class="exam-opt-label {{ (int)$ans->selected_option === (int)$optNum ? 'checked' : '' }}">
                                            <input type="radio" 
                                                   name="answers[{{ $q->id }}]" 
                                                   value="{{ $optNum }}" 
                                                   class="exam-opt-input"
                                                   data-qid="{{ $q->id }}"
                                                   data-qindex="{{ $index + 1 }}"
                                                   {{ (int)$ans->selected_option === (int)$optNum ? 'checked' : '' }}
                                                   onchange="handleOptionSelect(this);">
                                            <span class="badge bg-light text-dark font-weight-bold" style="width: 26px; height: 26px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;">
                                                {{ $letters[$optNum] }}
                                            </span>
                                            <span class="exam-opt-text flex-grow-1">{{ $optText }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach

                    <div class="text-center py-4">
                        <button type="button" class="btn btn-success btn-lg rounded-pill px-5 py-3 font-weight-bold shadow" onclick="confirmSubmitExam();">
                            <i class="feather-check-circle me-1"></i> Submit Chapter Exam
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right: Question Navigator Sidebar -->
            <div class="col-lg-4 col-12">
                <div class="card p-4 rounded-4 shadow-sm" style="position: sticky; top: 90px; border: 1px solid #e2e8f0;">
                    <h5 class="text-dark-title font-weight-bold mb-3 d-flex align-items-center justify-content-between">
                        <span>প্রশ্ন নেভিগেটর</span>
                        <span class="badge bg-info text-white" id="answeredCountBadge">
                            {{ $attempt->answers->whereNotNull('selected_option')->count() }} / {{ $attempt->answers->count() }} উত্তর করা হয়েছে
                        </span>
                    </h5>
                    <p class="small text-muted mb-3">যেকোনো প্রশ্নে সরাসরি যেতে নম্বরে ক্লিক করুন।</p>

                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($attempt->answers as $idx => $ans)
                            <a href="#q-block-{{ $idx + 1 }}" 
                               class="q-nav-pill {{ !empty($ans->selected_option) ? 'answered' : '' }}" 
                               id="q-nav-{{ $idx + 1 }}">
                                {{ $idx + 1 }}
                            </a>
                        @endforeach
                    </div>

                    <div class="d-flex align-items-center gap-3 small text-muted pt-3 border-top">
                        <span class="d-flex align-items-center gap-1">
                            <span class="q-nav-pill answered" style="width: 18px; height: 18px; font-size: 9px;"></span> উত্তর দেওয়া
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="q-nav-pill" style="width: 18px; height: 18px; font-size: 9px;"></span> বাকি
                        </span>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-success rounded-pill w-100 py-2 font-weight-bold" onclick="confirmSubmitExam();">
                            পরীক্ষা জমা দিন (Submit)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let remainingSeconds = {{ $remainingSeconds }};
    const attemptId = {{ $attempt->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const timerDisplay = document.getElementById('examTimerDisplay');
    const timerBadge = document.getElementById('examTimerBadge');
    const answeredCountBadge = document.getElementById('answeredCountBadge');
    const totalQuestions = {{ $attempt->answers->count() }};
    let isSubmitting = false;

    // Countdown Timer Loop
    function updateTimer() {
        if (remainingSeconds <= 0) {
            timerDisplay.textContent = '00:00';
            autoSubmitOnExpiry();
            return;
        }

        const mins = Math.floor(remainingSeconds / 60);
        const secs = remainingSeconds % 60;
        timerDisplay.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;

        if (remainingSeconds <= 120) {
            timerBadge.classList.add('urgent');
        }

        remainingSeconds--;
    }

    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // Instant Autosave on Option Select
    function handleOptionSelect(radioInput) {
        const qId = radioInput.dataset.qid;
        const qIndex = radioInput.dataset.qindex;
        const selectedVal = radioInput.value;

        // Visual radio active state in block
        const block = document.getElementById(`q-block-${qIndex}`);
        if (block) {
            block.classList.add('is-answered');
            block.querySelectorAll('.exam-opt-label').forEach(lbl => lbl.classList.remove('checked'));
            radioInput.closest('.exam-opt-label').classList.add('checked');
        }

        // Visual indicator in nav pill
        const pill = document.getElementById(`q-nav-${qIndex}`);
        if (pill) pill.classList.add('answered');

        // Update answered count
        const answeredCount = document.querySelectorAll('.exam-q-card.is-answered').length;
        if (answeredCountBadge) {
            answeredCountBadge.textContent = `${answeredCount} / ${totalQuestions} উত্তর করা হয়েছে`;
        }

        const saveInd = document.getElementById(`save-ind-${qId}`);
        if (saveInd) saveInd.textContent = 'সেভ হচ্ছে...';

        // AJAX Autosave
        fetch(`/api/practice-chapter/exam/${attemptId}/autosave`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                question_id: qId,
                selected_option: selectedVal
            })
        })
        .then(res => res.json())
        .then(data => {
            if (saveInd) saveInd.textContent = '✔ উত্তর সেভড';
        })
        .catch(err => {
            console.error('Autosave error:', err);
            if (saveInd) saveInd.textContent = 'সেভ হতে পারেনি';
        });
    }

    function confirmSubmitExam() {
        if (isSubmitting) return;
        const answeredCount = document.querySelectorAll('.exam-q-card.is-answered').length;
        const unansweredCount = totalQuestions - answeredCount;

        let message = `আপনি মোট ${totalQuestions}টি প্রশ্নের মধ্যে ${answeredCount}টির উত্তর দিয়েছেন।`;
        if (unansweredCount > 0) {
            message += `\n(${unansweredCount}টি প্রশ্ন অনুত্তরিত রয়েছে, যা ভুল হিসেবে গণ্য হবে)।`;
        }
        message += '\n\nআপনি কি নিশ্চিতভাবে পরীক্ষাটি সাবমিট করতে চান?';

        if (confirm(message)) {
            submitExamForm();
        }
    }

    function autoSubmitOnExpiry() {
        if (isSubmitting) return;
        clearInterval(timerInterval);
        alert('পরীক্ষার নির্ধারিত সময় শেষ হয়েছে! আপনার পরীক্ষাটি স্বয়ংক্রিয়ভাবে সাবমিট হচ্ছে।');
        submitExamForm();
    }

    function submitExamForm() {
        isSubmitting = true;
        clearInterval(timerInterval);
        document.getElementById('chapterExamForm').submit();
    }
</script>
@endsection
