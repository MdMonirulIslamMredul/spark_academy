<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PracticeQuiz;
use App\Models\PracticeQuestion;

class PracticeQuizSeeder extends Seeder
{
    public function run()
    {
        // 1. Remove old quiz games (e.g. BCS and any others)
        PracticeQuiz::truncate();
        PracticeQuestion::truncate();

        // 2. Create HSC-28 Physics 1st Paper Basic to Advance Course Quiz Game
        $quiz = PracticeQuiz::create([
            'category_id' => 1,
            'title' => 'HSC-28 Physics 1st Paper Basic to Advance Course',
            'slug' => 'hsc-28-physics-1st-paper-basic-to-advance-course',
            'description' => 'এইচএসসি-২৮ ব্যাচের পদার্থবিজ্ঞান ১ম পত্রের শিক্ষার্থীদের জন্য স্পেশাল MCQ Game। গ্যামিফাইড প্র্যাকটিস, স্পেসড রিপিটেশন ও চ্যাপ্টারভিত্তিক তাৎক্ষণিক সমাধান ও ব্যাখ্যা।',
            'target_repetitions' => 3,
            'is_active' => true,
        ]);

        // 3. Add 3 Authentic Physics Questions
        PracticeQuestion::create([
            'practice_quiz_id' => $quiz->id,
            'question' => 'নিচের কোনটি মৌলিক রাশি নয়?',
            'optn1' => 'তাপমাত্রা (Temperature)',
            'optn2' => 'তড়িৎ প্রবাহ (Electric Current)',
            'optn3' => 'বৈদ্যুতিক বিভব (Electric Potential)',
            'optn4' => 'পদার্থের পরিমাণ (Amount of Substance)',
            'right_ans' => 3,
            'explanation' => 'এসআই (SI) পদ্ধতিতে ৭টি মৌলিক রাশি হলো: দৈর্ঘ্য, ভর, সময়, তাপমাত্রা, তড়িৎ প্রবাহ, দীপন তীব্রতা এবং পদার্থের পরিমাণ। বৈদ্যুতিক বিভব হলো একটি লব্ধ বা যৌগিক রাশি (Derived Quantity)।',
        ]);

        PracticeQuestion::create([
            'practice_quiz_id' => $quiz->id,
            'question' => 'দুটি সমান মানের ভেক্টর একটি বিন্দুতে কত কোণে ক্রিয়া করলে তাদের লব্ধির মান যেকোনো একটি ভেক্টরের সমান হবে?',
            'optn1' => '60°',
            'optn2' => '90°',
            'optn3' => '120°',
            'optn4' => '180°',
            'right_ans' => 3,
            'explanation' => 'ধরি দুটি সমান ভেক্টর P এবং মধ্যবর্তী কোণ α। লব্ধি R = 2P cos(α/2)। প্রশ্নমতে, R = P => P = 2P cos(α/2) => cos(α/2) = 1/2 => α/2 = 60° => α = 120°। অতএব ভেক্টরদ্বয়ের মধ্যবর্তী কোণ ১২০°।',
        ]);

        PracticeQuestion::create([
            'practice_quiz_id' => $quiz->id,
            'question' => 'রকেট মহাশূন্যে সম্মুখপানে এগিয়ে চলার মূল নীতি কোনটি?',
            'optn1' => 'রৈখিক ভরবেগের সংরক্ষণশীলতা ও নিউটনের ৩য় সূত্র',
            'optn2' => 'শক্তির সংরক্ষণশীলতা নীতি ও আর্কিমিডিসের সূত্র',
            'optn3' => 'কৌণিক ভরবেগ সংরক্ষণ ও কেপলারের সূত্র',
            'optn4' => 'প্যাসকেলের নীতি ও অভিকর্ষ বলের প্রভাব',
            'right_ans' => 1,
            'explanation' => 'রকেট থেকে পেছনের দিকে তীব্র বেগে নির্গত গ্যাসের কারণে নিউটনের তৃতীয় গতিসূত্র অনুযায়ী সামনের দিকে সমান ও বিপরীতমুখী ধাক্কা (Thrust) অনুভূত হয়। এটি রৈখিক ভরবেগের সংরক্ষণশীলতা নীতির (Conservation of Linear Momentum) প্রত্যক্ষ প্রয়োগ।',
        ]);
    }
}
