<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PracticeSubject;
use App\Models\PracticeChapter;
use App\Models\PracticeQuiz;
use App\Models\PracticeQuestion;

class PracticeQuizSeeder extends Seeder
{
    public function run()
    {
        // 1. Create or update Subject
        $subject = PracticeSubject::updateOrCreate(
            ['slug' => 'physics-1st-paper'],
            [
                'category_id' => 1,
                'title' => 'Physics 1st Paper / পদার্থবিজ্ঞান ১ম পত্র',
                'description' => 'এইচএসসি শিক্ষার্থীদের জন্য সম্পূর্ণ পদার্থবিজ্ঞান ১ম পত্রের অধ্যায়ভিত্তিক গেম ও পরীক্ষা রোডম্যাপ।',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 2. Create or update Chapter 1
        $chapter = PracticeChapter::updateOrCreate(
            ['slug' => 'physical-world-and-vectors'],
            [
                'subject_id' => $subject->id,
                'title' => 'অধ্যায় ০১: ভৌত জগত, পরিমাপ ও ভেক্টর',
                'description' => 'ভৌত জগতের পরিমাপ, মাত্রা ও ভেক্টর বিশ্লেষণের মৌলিক থেকে অ্যাডভান্সড প্রশ্নাবলী।',
                'sort_order' => 1,
                'exam_duration_minutes' => 15,
                'exam_pass_mark_percent' => 0,
                'target_repetitions' => 3,
                'is_active' => true,
            ]
        );

        // 3. Level 1: মৌলিক রাশি ও পরিমাপ
        $level1 = PracticeQuiz::updateOrCreate(
            ['slug' => 'hsc-physics-level-1-units'],
            [
                'chapter_id' => $chapter->id,
                'category_id' => 1,
                'title' => 'Level 01: মৌলিক রাশি ও পরিমাপ',
                'sort_order' => 1,
                'description' => 'এসআই একক, মাত্রা সমীকরণ ও মৌলিক রাশি সম্পর্কিত প্র্যাকটিস গেম।',
                'target_repetitions' => 3,
                'is_active' => true,
            ]
        );

        PracticeQuestion::updateOrCreate(
            ['practice_quiz_id' => $level1->id, 'question' => 'নিচের কোনটি মৌলিক রাশি নয়?'],
            [
                'optn1' => 'তাপমাত্রা (Temperature)',
                'optn2' => 'তড়িৎ প্রবাহ (Electric Current)',
                'optn3' => 'বৈদ্যুতিক বিভব (Electric Potential)',
                'optn4' => 'পদার্থের পরিমাণ (Amount of Substance)',
                'right_ans' => 3,
                'explanation' => 'এসআই (SI) পদ্ধতিতে ৭টি মৌলিক রাশি হলো: দৈর্ঘ্য, ভর, সময়, তাপমাত্রা, তড়িৎ প্রবাহ, দীপন তীব্রতা এবং পদার্থের পরিমাণ। বৈদ্যুতিক বিভব হলো একটি লব্ধ বা যৌগিক রাশি (Derived Quantity)।',
            ]
        );

        PracticeQuestion::updateOrCreate(
            ['practice_quiz_id' => $level1->id, 'question' => 'মহাকর্ষীয় ধ্রুবক G এর মাত্রা সমীকরণ কোনটি?'],
            [
                'optn1' => '[M^-1 L^3 T^-2]',
                'optn2' => '[M L^2 T^-2]',
                'optn3' => '[M^-1 L^2 T^-1]',
                'optn4' => '[M L^3 T^-2]',
                'right_ans' => 1,
                'explanation' => 'নিউটনীয় সূত্র অনুসারে F = G(m1*m2)/r^2। সুতরাং G = F*r^2 / (m1*m2)। বলের মাত্রা [M L T^-2], দূরত্বের মাত্রা [L], ভরের মাত্রা [M]। অতএব [G] = [M^-1 L^3 T^-2]।',
            ]
        );

        // 4. Level 2: ভেক্টর বিশ্লেষণ
        $level2 = PracticeQuiz::updateOrCreate(
            ['slug' => 'hsc-physics-level-2-vectors'],
            [
                'chapter_id' => $chapter->id,
                'category_id' => 1,
                'title' => 'Level 02: ভেক্টর বিশ্লেষণ ও লব্ধি',
                'sort_order' => 2,
                'description' => 'ভেক্টর যোগ, বিয়োগ ও মধ্যবর্তী কোণ সংক্রান্ত সমস্যা।',
                'target_repetitions' => 3,
                'is_active' => true,
            ]
        );

        PracticeQuestion::updateOrCreate(
            ['practice_quiz_id' => $level2->id, 'question' => 'দুটি সমান মানের ভেক্টর একটি বিন্দুতে কত কোণে ক্রিয়া করলে তাদের লব্ধির মান যেকোনো একটি ভেক্টরের সমান হবে?'],
            [
                'optn1' => '60°',
                'optn2' => '90°',
                'optn3' => '120°',
                'optn4' => '180°',
                'right_ans' => 3,
                'explanation' => 'ধরি দুটি সমান ভেক্টর P এবং মধ্যবর্তী কোণ α। লব্ধি R = 2P cos(α/2)। প্রশ্নমতে, R = P => P = 2P cos(α/2) => cos(α/2) = 1/2 => α/2 = 60° => α = 120°। অতএব ভেক্টরদ্বয়ের মধ্যবর্তী কোণ ১২০°।',
            ]
        );

        PracticeQuestion::updateOrCreate(
            ['practice_quiz_id' => $level2->id, 'question' => 'দুটি ভেক্টরের ডট গুণন শূন্য হলে তাদের মধ্যবর্তী কোণ কত?'],
            [
                'optn1' => '0°',
                'optn2' => '45°',
                'optn3' => '90°',
                'optn4' => '180°',
                'right_ans' => 3,
                'explanation' => 'A . B = |A||B| cos θ। যদি A . B = 0 এবং অশূন্য ভেক্টর হয়, তবে cos θ = 0 => θ = 90°। অর্থাৎ ভেক্টরদ্বয় পরস্পর লম্ব।',
            ]
        );

        // 5. Level 3: বল ও গতিবিদ্যা
        $level3 = PracticeQuiz::updateOrCreate(
            ['slug' => 'hsc-physics-level-3-dynamics'],
            [
                'chapter_id' => $chapter->id,
                'category_id' => 1,
                'title' => 'Level 03: গতিবিদ্যা ও রৈখিক ভরবেগ',
                'sort_order' => 3,
                'description' => 'নিউটনের গতিসূত্র, ভরবেগ সংরক্ষণ ও রকেট গতিবিদ্যা।',
                'target_repetitions' => 3,
                'is_active' => true,
            ]
        );

        PracticeQuestion::updateOrCreate(
            ['practice_quiz_id' => $level3->id, 'question' => 'রকেট মহাশূন্যে সম্মুখপানে এগিয়ে চলার মূল নীতি কোনটি?'],
            [
                'optn1' => 'রৈখিক ভরবেগের সংরক্ষণশীলতা ও নিউটনের ৩য় সূত্র',
                'optn2' => 'শক্তির সংরক্ষণশীলতা নীতি ও আর্কিমিডিসের সূত্র',
                'optn3' => 'কৌণিক ভরবেগ সংরক্ষণ ও কেপলারের সূত্র',
                'optn4' => 'প্যাসকেলের নীতি ও অভিকর্ষ বলের প্রভাব',
                'right_ans' => 1,
                'explanation' => 'রকেট থেকে পেছনের দিকে তীব্র বেগে নির্গত গ্যাসের কারণে নিউটনের তৃতীয় গতিসূত্র অনুযায়ী সামনের দিকে সমান ও বিপরীতমুখী ধাক্কা (Thrust) অনুভূত হয়। এটি রৈখিক ভরবেগের সংরক্ষণশীলতা নীতির (Conservation of Linear Momentum) প্রত্যক্ষ প্রয়োগ।',
            ]
        );

        PracticeQuestion::updateOrCreate(
            ['practice_quiz_id' => $level3->id, 'question' => 'ভরবেগ এবং গতিশক্তির মধ্যকার সঠিক সম্পর্ক কোনটি?'],
            [
                'optn1' => 'E_k = p^2 / (2m)',
                'optn2' => 'E_k = 2m / p^2',
                'optn3' => 'E_k = m*p / 2',
                'optn4' => 'E_k = p / (2m)',
                'right_ans' => 1,
                'explanation' => 'গতিশক্তি E_k = 1/2 m v^2 = (m v)^2 / (2m) = p^2 / (2m)।',
            ]
        );
    }
}
