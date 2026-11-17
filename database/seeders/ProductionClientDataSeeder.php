<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ProductionClientDataSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        // 1. Clear existing dummy/test tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tablesToTruncate = [
            'categories',
            'exams',
            'mcq_questions',
            'written_questions',
            'course_categories',
            'courses',
            'course_chapters',
            'course_modules',
            'teachers',
            'blogs',
            'notice',
            'slider_banners',
            'slider_infos',
            'slider_images',
            'company_settings',
            'courses_order',
            'results',
            'mcq_results',
            'mcq_results_parents',
            'written_results',
            'orders',
            'about_infos',
            'abouts'
        ];

        foreach ($tablesToTruncate as $t) {
            if (Schema::hasTable($t)) {
                DB::table($t)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 2. Ensure Admin User (admin@admin.com / 12345678)
        $admin = DB::table('admins')->where('email', 'admin@admin.com')->first();
        if ($admin) {
            DB::table('admins')->where('email', 'admin@admin.com')->update([
                'name' => 'Super Administrator',
                'password' => Hash::make('12345678'),
                'active' => 1,
                'updated_at' => $now
            ]);
        } else {
            DB::table('admins')->insert([
                'name' => 'Super Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // 3. Ensure Demo Student User (mesba@gmail.com / 12345678)
        $student = DB::table('users')->where('email', 'mesba@gmail.com')->first();
        $studentId = null;
        if ($student) {
            $studentId = $student->id;
            DB::table('users')->where('id', $studentId)->update([
                'name' => 'Ridoy Paul',
                'phone' => '01712345678',
                'password' => Hash::make('12345678'),
                'address' => 'House 42, Road 11, Dhanmondi, Dhaka',
                'active' => 1,
                'updated_at' => $now
            ]);
        } else {
            $studentId = DB::table('users')->insertGetId([
                'name' => 'Ridoy Paul',
                'email' => 'mesba@gmail.com',
                'phone' => '01712345678',
                'password' => Hash::make('12345678'),
                'address' => 'House 42, Road 11, Dhanmondi, Dhaka',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]);
        }

        // 4. Populate Company Settings
        DB::table('company_settings')->insert([
            'company_name' => 'Spark Academy - Emu Sir',
            'logo' => 'frontend/assets/images/logo/logo.png',
            'address' => 'মডুলাস, চট্টেশ্বরী রোড, চকবাজার, চট্টগ্রাম',
            'phone_1' => '01768240303',
            'phone_2' => '01521328888',
            'email' => 'learnwithemu@gmail.com',
            'count1' => 25000,
            'count2' => 450,
            'count3' => 85,
            'count4' => 98,
            'facebook' => 'https://facebook.com/sparkacademyofficial',
            'youtube' => 'https://youtube.com/@sparkacademyofficial',
            'instagram' => 'https://instagram.com/sparkacademyofficial',
            'twitter' => 'https://twitter.com/sparkacademyofficial',
            'linkden' => 'https://linkedin.com/company/sparkacademyofficial',
            'privacy_policy' => '<p>স্পার্ক একাডেমি শিক্ষার্থীদের ব্যক্তিগত তথ্যের সর্বোচ্চ সুরক্ষা ও গোপনীয়তা বজায় রাখতে প্রতিশ্রুতিবদ্ধ। শিক্ষার্থীদের প্রোফাইল, ফোন নম্বর এবং পরীক্ষার ফলাফল সম্পূর্ণ এনক্রিপ্টেড ডাটাবেজে সংরক্ষিত থাকে এবং কোনো তৃতীয় পক্ষের কাছে বিক্রি বা শেয়ার করা হয় না।</p>',
            'mission_and_vission' => '<p><strong>আমাদের লক্ষ্য:</strong> এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের পদার্থবিজ্ঞানের ভিত্তি মজবুত করা এবং বোর্ড ও ভর্তি পরীক্ষায় সর্বোচ্চ সাফল্য অর্জন করানো।</p>',
            'trams_and_condition' => '<p>১. স্পার্ক একাডেমি প্ল্যাটফর্মের সকল কোর্স, ভিডিও লেকচার, বই ও ম্যাটেরিয়ালের কপিরাইট সংরক্ষিত।</p>',
            'footer_des' => 'স্পার্ক একাডেমি ইমু স্যার এইচএসসি বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য একটি অনলাইন ও অফলাইন কার্যক্রম সম্পন্নকারী প্রতিষ্ঠান। স্পার্ক একাডেমির অনলাইন কোর্সের পাশাপাশি আছে নিজস্ব বই ।',
            'footer_credit' => '© ২০২৬ Spark Academy - Emu Sir. সর্বস্বত্ব সংরক্ষিত।',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        // 5. Populate Exam Categories (`categories`)
        $categoriesData = [
            [
                'id' => 1,
                'name' => 'HSC পদার্থবিজ্ঞান ১ম ও ২য় পত্র মডেল টেস্ট',
                'url' => 'hsc-physics-model-tests',
                'image' => 'images/bcs_prep.jpg',
                'free_paid' => 'free',
                'tk' => '0',
                'rank' => 1,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'HSC রসায়ন ১ম ও ২য় পত্র মডেল টেস্ট',
                'url' => 'hsc-chemistry-model-tests',
                'image' => 'images/bank_prep.jpg',
                'free_paid' => 'free',
                'tk' => '0',
                'rank' => 2,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'HSC উচ্চতর গণিত ১ম ও ২য় পত্র মডেল টেস্ট',
                'url' => 'hsc-higher-math-model-tests',
                'image' => 'images/primary_prep.jpg',
                'free_paid' => 'free',
                'tk' => '0',
                'rank' => 3,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'name' => 'HSC জীববিজ্ঞান ১ম ও ২য় পত্র মডেল টেস্ট',
                'url' => 'hsc-biology-model-tests',
                'image' => 'images/ntrca_prep.jpg',
                'free_paid' => 'free',
                'tk' => '0',
                'rank' => 4,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 5,
                'name' => 'HSC আইসিটি ও স্পেশাল বোর্ড ফাইনাল টেস্ট',
                'url' => 'hsc-ict-board-final-tests',
                'image' => 'images/govt_job_prep.jpg',
                'free_paid' => 'free',
                'tk' => '0',
                'rank' => 5,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('categories')->insert($categoriesData);

        // 6. Populate Exams (`exams`)
        $examsData = [
            // BCS Category (id 1)
            [
                'id' => 1,
                'catID' => 1,
                'mcq_written' => 'mcq',
                'free_paid' => 'free',
                'tk' => 0,
                'exam_name' => '৪৬তম বিসিএস পূর্ণাঙ্গ মডেল টেস্ট - ০১ (বাংলা ও সাধারণ জ্ঞান)',
                'type' => 'free',
                'startDate' => '2026-10-20',
                'examTime' => '20',
                'written_paragraph' => null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'catID' => 1,
                'mcq_written' => 'mcq',
                'free_paid' => 'free',
                'tk' => 0,
                'exam_name' => 'বিসিএস ইংরেজি ভাষা ও সাহিত্য স্পেশাল মেগা কুইজ',
                'type' => 'free',
                'startDate' => '2026-10-20',
                'examTime' => '15',
                'written_paragraph' => null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'catID' => 1,
                'mcq_written' => 'written',
                'free_paid' => 'free',
                'tk' => 0,
                'exam_name' => 'বিসিএস লিখিত বাংলা রচনা ও বঙ্গানুবাদ মূল্যায়ন পরীক্ষা',
                'type' => 'free',
                'startDate' => '2026-10-20',
                'examTime' => '60',
                'written_paragraph' => 'নিচের অনুচ্ছেদটি মনোযোগ দিয়ে পড়ুন এবং বঙ্গানুবাদ করুন।',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Bank Category (id 2)
            [
                'id' => 4,
                'catID' => 2,
                'mcq_written' => 'mcq',
                'free_paid' => 'paid',
                'tk' => 500,
                'exam_name' => 'সমন্বিত ১০ ব্যাংক সিনিয়র অফিসার ম্যাথ ও অ্যানালিটিক্যাল টেস্ট',
                'type' => 'paid',
                'startDate' => '2026-10-20',
                'examTime' => '30',
                'written_paragraph' => null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 5,
                'catID' => 2,
                'mcq_written' => 'written',
                'free_paid' => 'paid',
                'tk' => 500,
                'exam_name' => 'বাংলাদেশ ব্যাংক ফোকাস রাইটিং ও আর্গুমেন্ট এসে টেস্ট',
                'type' => 'paid',
                'startDate' => '2026-10-20',
                'examTime' => '45',
                'written_paragraph' => 'Write a structured analytical essay on: Smart Bangladesh & FinTech Transformation.',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Primary Category (id 3)
            [
                'id' => 6,
                'catID' => 3,
                'mcq_written' => 'mcq',
                'free_paid' => 'free',
                'tk' => 0,
                'exam_name' => 'প্রাথমিক সহকারী শিক্ষক চূড়ান্ত মডেল টেস্ট - ০১ (৮০ নম্বর)',
                'type' => 'free',
                'startDate' => '2026-10-20',
                'examTime' => '25',
                'written_paragraph' => null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // NTRCA Category (id 4)
            [
                'id' => 7,
                'catID' => 4,
                'mcq_written' => 'mcq',
                'free_paid' => 'paid',
                'tk' => 450,
                'exam_name' => '১৮তম শিক্ষক নিবন্ধন প্রিলিমিনারি সাধারণ জ্ঞান ও বিজ্ঞান টেস্ট',
                'type' => 'paid',
                'startDate' => '2026-10-20',
                'examTime' => '20',
                'written_paragraph' => null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Govt Non-Cadre Category (id 5)
            [
                'id' => 8,
                'catID' => 5,
                'mcq_written' => 'mcq',
                'free_paid' => 'free',
                'tk' => 0,
                'exam_name' => 'মন্ত্রণালয় ও সরকারি নন-ক্যাডার সাধারণ প্রস্তুতি মডেল টেস্ট',
                'type' => 'free',
                'startDate' => '2026-10-20',
                'examTime' => '20',
                'written_paragraph' => null,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('exams')->insert($examsData);

        // 7. Populate Authentic MCQ Questions (`mcq_questions`)
        $mcqQuestions = [
            // Exam 1 (BCS Model Test 1)
            [
                'examID' => 1,
                'questions' => 'চর্যাপদের সবচেয়ে বেশি সংখ্যক পদ কে রচনা করেছেন?',
                'optn1' => 'লুইপা',
                'optn2' => 'কাহ্নপা',
                'optn3' => 'শবরপা',
                'optn4' => 'ভুসুকুপা',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'চর্যাপদের মোট পদসংখ্যা সাড়ে ছেচল্লিশটি। এর মধ্যে সর্বোচ্চ ১৩টি পদ রচনা করেছেন কাহ্নপা। লুইপা প্রথম পদ এবং ভুসুকুপা ৮টি পদ রচনা করেন।',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 1,
                'questions' => 'কোনটি মুক্তিযুদ্ধভিত্তিক উপন্যাস?',
                'optn1' => 'আগুনের পরশমণি',
                'optn2' => 'পদ্মানদীর মাঝি',
                'optn3' => 'লালসালু',
                'optn4' => 'চিলেকোঠার সেপাই',
                'optn5' => null,
                'rightAns' => 1,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => '"আগুনের পরশমণি" কথাশিল্পী হুমায়ূন আহমেদের বিখ্যাত মুক্তিযুদ্ধভিত্তিক উপন্যাস। এর ওপর ভিত্তি করে তিনি চলচ্চিত্রও নির্মাণ করেছিলেন যা জাতীয় চলচ্চিত্র পুরস্কার পায়।',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 1,
                'questions' => 'বাংলাদেশের সংবিধানের কোন অনুচ্ছেদে ‘মৌলিক অধিকার নিশ্চিতকরণ’ সংক্রান্ত বিধান রয়েছে?',
                'optn1' => '২৭ নম্বর অনুচ্ছেদ',
                'optn2' => '৪৪ নম্বর অনুচ্ছেদ',
                'optn3' => '৩২ নম্বর অনুচ্ছেদ',
                'optn4' => '৪৮ নম্বর অনুচ্ছেদ',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'সংবিধানের ৪৪ নম্বর অনুচ্ছেদে মৌলিক অধিকার বলবৎকরণের জন্য সুপ্রিম কোর্টের হাইকোর্ট বিভাগে রিট করার অধিকার নিশ্চিত করা হয়েছে।',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 1,
                'questions' => 'মহাস্থানগড় কোন নদীর তীরে অবস্থিত?',
                'optn1' => 'করতোয়া',
                'optn2' => 'ব্রহ্মপুত্র',
                'optn3' => 'যমুনা',
                'optn4' => 'পদ্মা',
                'optn5' => null,
                'rightAns' => 1,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'বগুড়া শহর থেকে প্রায় ১৩ কিমি উত্তরে করতোয়া নদীর পশ্চিম তীরে প্রাচীন বাংলার রাজধানী পুণ্ড্রনগর বা মহাস্থানগড় অবস্থিত।',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 1,
                'questions' => 'একটি দ্রব্যের ক্রয়মূল্য ৫০০ টাকা। ১০% লাভে দ্রব্যটির বিক্রয়মূল্য কত হবে?',
                'optn1' => '৫৪০ টাকা',
                'optn2' => '৫৫০ টাকা',
                'optn3' => '৫৬০ টাকা',
                'optn4' => '৫৭৫ টাকা',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => '১০% লাভে বিক্রয়মূল্য = ৫০০ + (৫০০ × ১০/১০০) = ৫০০ + ৫০ = ৫৫০ টাকা।',
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Exam 2 (English Literature Mega Quiz)
            [
                'examID' => 2,
                'questions' => 'Who is known as the "Poet of Nature" in English Literature?',
                'optn1' => 'John Keats',
                'optn2' => 'William Wordsworth',
                'optn3' => 'P. B. Shelley',
                'optn4' => 'Lord Byron',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'William Wordsworth is called the "Poet of Nature" and "Worshipper of Nature". John Keats is the "Poet of Beauty".',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 2,
                'questions' => 'The antonym of the word "Candor" is -',
                'optn1' => 'Frankness',
                'optn2' => 'Deceit',
                'optn3' => 'Honesty',
                'optn4' => 'Fairness',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'Candor means frankness, openness, or honesty. Therefore, its antonym is Deceit (কপটতা / প্রতারণা).',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 2,
                'questions' => '"To be or not to be, that is the question" is a famous soliloquy from -',
                'optn1' => 'Macbeth',
                'optn2' => 'Othello',
                'optn3' => 'Hamlet',
                'optn4' => 'King Lear',
                'optn5' => null,
                'rightAns' => 3,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'This famous philosophical soliloquy is delivered by Prince Hamlet in William Shakespeare\'s tragedy "Hamlet" (Act III, Scene 1).',
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Exam 4 (Bank Math & Analytical)
            [
                'examID' => 4,
                'questions' => 'If a worker takes 6 hours to finish a job and another worker takes 12 hours, how long will they take working together?',
                'optn1' => '3 hours',
                'optn2' => '4 hours',
                'optn3' => '5 hours',
                'optn4' => '4.5 hours',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'Together in 1 hour: 1/6 + 1/12 = 3/12 = 1/4 of the job. Hence, the total time required together is 4 hours.',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 4,
                'questions' => 'What is the sum of the interior angles of a hexagon?',
                'optn1' => '540°',
                'optn2' => '720°',
                'optn3' => '360°',
                'optn4' => '900°',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'Formula for sum of interior angles: (n - 2) × 180°. For a hexagon (n = 6): (6 - 2) × 180° = 4 × 180° = 720°.',
                'created_at' => $now,
                'updated_at' => $now
            ],

            // Exam 6 (Primary Teacher Model Test)
            [
                'examID' => 6,
                'questions' => 'বাংলা বর্ণমালায় অর্ধমাত্রা বর্ণ কয়টি?',
                'optn1' => '১০টি',
                'optn2' => '৮টি',
                'optn3' => '৩২টি',
                'optn4' => '৬টি',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'বাংলা বর্ণমালায় মোট অর্ধমাত্রা বর্ণ ৮টি (স্বরে ১টি: ঋ, ব্যঞ্জনে ৭টি: খ, গ, ণ, থ, ধ, প, শ)। পূর্ণমাত্রা বর্ণ ৩২টি এবং মাত্রাহীন বর্ণ ১০টি।',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 6,
                'questions' => '‘সন্ধি’ ব্যাকরণের কোন অংশের আলোচ্য বিষয়?',
                'optn1' => 'রূপতত্ত্ব',
                'optn2' => 'ধ্বনিতত্ত্ব',
                'optn3' => 'বাক্যতত্ত্ব',
                'optn4' => 'অর্থতত্ত্ব',
                'optn5' => null,
                'rightAns' => 2,
                'mark' => '1.00',
                'negativeMark' => '0.25',
                'writeAnsDes' => 'সন্ধি হলো পাশাপাশি দুটি ধ্বনির মিলন। ধ্বনির মিলনের ফলে ধ্বনির পরিবর্তন ঘটে, তাই সন্ধি ব্যাকরণের ধ্বনিতত্ত্বে (Phonology) আলোচিত হয়।',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('mcq_questions')->insert($mcqQuestions);

        // 8. Populate Written Questions (`written_questions`)
        $writtenQuestions = [
            [
                'examID' => 3,
                'questions' => 'বঙ্গবন্ধু শেখ মুজিবুর রহমান এবং মুক্তিযুদ্ধের ঐতিহাসিক চেতনা শীর্ষক ৩০০ শব্দের একটি সুসংগঠিত প্রবন্ধ রচনা করুন।',
                'mark' => '15.00',
                'ans' => 'ভূমিকা, ঐতিহাসিক প্রেক্ষাপট, ৭ই মার্চের ভাষণ, মুক্তিযুদ্ধের নেতৃত্ব, আন্তর্জাতিক প্রভাব এবং উপসংহার অন্তর্ভুক্ত করতে হবে।',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 3,
                'questions' => 'Translate the following into Bengali: "Education is the most powerful weapon which you can use to change the world. It enlightens the human soul and eradicates darkness."',
                'mark' => '10.00',
                'ans' => 'বঙ্গানুবাদ: "শিক্ষা হলো সবচেয়ে শক্তিশালী হাতিয়ার যা দিয়ে আপনি বিশ্বকে বদলে দিতে পারেন। এটি মানব আত্মাকে আলোকিত করে এবং অজ্ঞতার অন্ধকার দূর করে।"',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'examID' => 5,
                'questions' => 'Write an analytical essay on "Challenges and Prospects of Cashless & Digital Banking in Bangladesh".',
                'mark' => '20.00',
                'ans' => 'Key focus points: FinTech adoption, cybersecurity challenges, financial inclusion, Bangla QR, and regulatory frameworks.',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('written_questions')->insert($writtenQuestions);

        // 9. Populate Course Categories (`course_categories`)
        $courseCategories = [
            [
                'id' => 1,
                'name' => 'HSC পদার্থবিজ্ঞান (Physics)',
                'url' => 'hsc-physics',
                'image' => 'images/bcs_prep.jpg',
                'rank' => 1,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'HSC রসায়ন (Chemistry)',
                'url' => 'hsc-chemistry',
                'image' => 'images/bank_prep.jpg',
                'rank' => 2,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'HSC উচ্চতর গণিত (Higher Math)',
                'url' => 'hsc-higher-math',
                'image' => 'images/primary_prep.jpg',
                'rank' => 3,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'name' => 'HSC জীববিজ্ঞান (Biology)',
                'url' => 'hsc-biology',
                'image' => 'images/ntrca_prep.jpg',
                'rank' => 4,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 5,
                'name' => 'HSC তথ্য ও যোগাযোগ প্রযুক্তি (ICT)',
                'url' => 'hsc-ict',
                'image' => 'images/govt_job_prep.jpg',
                'rank' => 5,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('course_categories')->insert($courseCategories);

        // 10. Populate Courses (`courses`)
        $coursesData = [
            [
                'id' => 1,
                'catID' => '1',
                'name' => 'HSC-28 Physics 1st Paper Basic to Advance Course',
                'price' => 3000,
                'image' => 'images/bcs_prep.jpg',
                'description' => '<p>ইমু স্যারের নির্দেশনায় এইচএসসি-২৮ ব্যাচের বিজ্ঞান বিভাগের শিক্ষার্থীদের জন্য পদার্থবিজ্ঞান ১ম পত্রের বেসিক টু অ্যাডভান্স পূর্ণাঙ্গ কোর্স। থিওরি ক্লিয়ারেন্স, শর্টকাট টেকনিক, গাণিতিক সমস্যা ও বোর্ড পরীক্ষার সেরা প্রস্তুতি।</p>',
                'rank' => 1,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'catID' => '2',
                'name' => 'ব্যাংক জবস ম্যাথ, অ্যানালিটিক্যাল ও ইংলিশ স্পেশাল প্রোগ্রাম',
                'price' => 1800,
                'image' => 'images/bank_prep.jpg',
                'description' => 'বাংলাদেশ ব্যাংক ও সমন্বিত সরকারি ব্যাংকের জন্য গণিত ও রিজনিংয়ের শর্টকাট টেকনিক, ফোকাস রাইটিং ও অনুবাদ মাস্টারক্লাস।',
                'rank' => 2,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'catID' => '3',
                'name' => 'প্রাথমিক শিক্ষক নিয়োগ শেষ মুহূর্তের চূড়ান্ত রিভিশন ব্যাচ',
                'price' => 1200,
                'image' => 'images/primary_prep.jpg',
                'description' => '৮০ নম্বরের টার্গেট প্রস্তুতি, বিগত ১০ বছরের প্রশ্নব্যাংক সমাধান, ২০টি ওএমআর লাইভ পরীক্ষা ও সাজেস্টিভ হ্যান্ডনোট।',
                'rank' => 3,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'catID' => '4',
                'name' => '১৮তম NTRCA শিক্ষক নিবন্ধন (স্কুল ও কলেজ) পূর্ণাঙ্গ কোর্স',
                'price' => 1500,
                'image' => 'images/ntrca_prep.jpg',
                'description' => '১০০ নম্বরের প্রিলিমিনারি সিলেবাস সম্পূর্ণ প্রস্তুতি, বিষয়ভিত্তিক স্পেশাল ক্লাস ও নিয়মিত সাপ্তাহিক মডেল টেস্ট।',
                'rank' => 4,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 5,
                'catID' => '5',
                'name' => 'সরকারি চাকরি ইংরেজি ও গণিত ফাউন্ডেশন বুটক্যাম্প',
                'price' => 1600,
                'image' => 'images/govt_job_prep.jpg',
                'description' => 'বেসিক থেকে অ্যাডভান্সড ইংরেজি গ্রামার, ভোকাবুলারি মনে রাখার বৈজ্ঞানিক উপায় এবং দ্রুত গণিত সমাধানের কৌশল।',
                'rank' => 5,
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('courses')->insert($coursesData);

        // 11. Populate Course Chapters (`course_chapters`) & Videos (`course_modules`)
        $courseChapters = [
            ['id' => 1, 'course_id' => 1, 'title' => 'ভৌতজগত ও পরিমাপ (Physical World & Measurement)', 'description' => 'মৌলিক ও লব্ধ রাশি, মাত্রা, ত্রুটি বিশ্লেষণ এবং সূক্ষ্ম পরিমাপ যন্ত্রপাতি।', 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'course_id' => 1, 'title' => 'ভেক্টর (Vector)', 'description' => 'ভেক্টর ও স্কেলার রাশি, একক ভেক্টর এবং ভেক্টর বিভাজন।', 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 3, 'course_id' => 1, 'title' => 'গতিবিদ্যা (Dynamics)', 'description' => 'রৈখিক গতি, ত্বরণ, প্রাস ও গতির সমীকরণ।', 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 4, 'course_id' => 1, 'title' => 'নিউটনিয়ান বলবিদ্যা (Newtonian Mechanics)', 'description' => 'জড়তা, ভরবেগ সংরক্ষণ, ঘর্ষণ ও রকেট গতি।', 'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 5, 'course_id' => 1, 'title' => 'কাজ, শক্তি ও ক্ষমতা (Work, Energy & Power)', 'description' => 'গতিশক্তি, বিভবশক্তি, সংরক্ষণশীলতা নীতি ও কুয়া সংক্রান্ত গাণিতিক সমস্যা।', 'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 6, 'course_id' => 1, 'title' => 'মহাকর্ষ ও অভিকর্ষ (Gravitation & Gravity)', 'description' => 'মহাকর্ষ সূত্র, কেপলারের সূত্র, মুক্তিবেগ ও কৃত্রিম উপগ্রহ।', 'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 7, 'course_id' => 1, 'title' => 'পদার্থের গাঠনিক ধর্ম (Structural Properties of Matter)', 'description' => 'স্থিতিস্থাপকতা, হুকের সূত্র, ইয়ং-এর গুণাঙ্ক ও পৃষ্ঠটান।', 'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 8, 'course_id' => 1, 'title' => 'পর্যায়বৃত্ত গতি (Periodic Motion)', 'description' => 'সরল ছন্দিত স্পন্দন, স্প্রিং-ভর সিস্টেম ও সরল দোলক।', 'sort_order' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 9, 'course_id' => 1, 'title' => 'তরঙ্গ (Waves)', 'description' => 'অগ্রগামী ও স্থির তরঙ্গ, শব্দের বেগ, বিট ও ডপলার ক্রিয়া।', 'sort_order' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 10, 'course_id' => 1, 'title' => 'আদর্শ গ্যাস ও গ্যাসের গতিতত্ত্ব (Ideal Gas & Kinetics)', 'description' => 'বয়েল ও চার্লসের সূত্র, আদর্শ গ্যাস সমীকরণ ও আর্দ্রতামিতি।', 'sort_order' => 10, 'created_at' => $now, 'updated_at' => $now],
        ];
        DB::table('course_chapters')->insert($courseChapters);

        $courseModules = [
            // Course 1: HSC-28 Physics 1st Paper Basic to Advance Course (10 Chapters)
            [
                'course_id' => 1,
                'chapter_id' => 1,
                'title' => 'লেকচার ০১: ভৌতজগত ও পরিমাপ - মৌলিক ও লব্ধ রাশি, মাত্রা ও ত্রুটি বিশ্লেষণ',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '28 min',
                'free_paid' => 'free',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 1,
                'title' => 'লেকচার ০২: স্লাইড ক্যালিপার্স ও স্ক্রু-গেজ দিয়ে সূক্ষ্ম পরিমাপ ও গাণিতিক উদাহরণ',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '34 min',
                'free_paid' => 'free',
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 1,
                'title' => 'লেকচার ০৩: ভৌতজগত ও পরিমাপ - বিগত বছরের বোর্ড ও ভর্তি পরীক্ষার MCQ সমাধান',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '42 min',
                'free_paid' => 'free',
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 2,
                'title' => 'লেকচার ০১: ভেক্টর ও স্কেলার রাশি, একক ভেক্টর ও অবস্থান ভেক্টর',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '36 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 3,
                'title' => 'লেকচার ০১: গতিবিদ্যা - রৈখিক গতি, ত্বরণ ও গতির সমীকরণ',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '40 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 4,
                'title' => 'লেকচার ০১: নিউটনিয়ান বলবিদ্যা - জড়তা, ভরবেগ সংরক্ষণ ও রকেট গতি',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '45 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 5,
                'title' => 'লেকচার ০১: কাজ, শক্তি ও ক্ষমতা - গতিশক্তি, বিভবশক্তি ও কুয়া সংক্রান্ত সমস্যা',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '38 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 6,
                'title' => 'লেকচার ০১: মহাকর্ষ ও অভিকর্ষ - মহাকর্ষ সূত্র, কেপলারের সূত্র ও অভিকর্ষজ ত্বরণ',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '35 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 7,
                'title' => 'লেকচার ০১: পদার্থের গাঠনিক ধর্ম - স্থিতিস্থাপকতা, হুকের সূত্র ও ইয়ং-এর গুণাঙ্ক',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '32 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 8,
                'title' => 'লেকচার ০১: পর্যায়বৃত্ত গতি - সরল ছন্দিত স্পন্দন ও সরল দোলক',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '37 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 9,
                'title' => 'লেকচার ০১: তরঙ্গ - অগ্রগামী ও স্থির তরঙ্গ, শব্দের বেগ ও ডপলার ক্রিয়া',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '41 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'course_id' => 1,
                'chapter_id' => 10,
                'title' => 'লেকচার ০১: আদর্শ গ্যাস ও গ্যাসের গতিতত্ত্ব - বয়েলের সূত্র, চার্লসের সূত্র ও আদর্শ গ্যাস সমীকরণ',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => '39 min',
                'free_paid' => 'paid',
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('course_modules')->insert($courseModules);

        // 12. Populate Teachers / Mentors (`teachers`)
        $teachersData = [
            [
                'id' => 1,
                'name' => 'মেহেদী হাসান',
                'subject' => 'বাংলা ভাষা ও সাহিত্য',
                'job_title' => '৪১তম বিসিএস (প্রশাসন ক্যাডার), ৫ম মেধা স্থান',
                'image' => 'frontend/assets/images/team/team-01.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'name' => 'নুসরাত জাহান',
                'subject' => 'ইংরেজি ভাষা ও সাহিত্য',
                'job_title' => 'সিনিয়র অফিসার, বাংলাদেশ ব্যাংক (১২তম মেধা স্থান)',
                'image' => 'frontend/assets/images/team/team-02.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'name' => 'ড. তারিকুল ইসলাম',
                'subject' => 'আন্তর্জাতিক ও ভূরাজনীতি',
                'job_title' => 'সহযোগী অধ্যাপক, ঢাকা বিশ্ববিদ্যালয়',
                'image' => 'frontend/assets/images/team/team-03.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'name' => 'তানভীর আহমেদ',
                'subject' => 'গণিত ও মানসিক দক্ষতা',
                'job_title' => 'বিসিএস ও ব্যাংক জবস স্পেশালিস্ট (১০ বছরের অভিজ্ঞতা)',
                'image' => 'frontend/assets/images/team/team-04.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('teachers')->insert($teachersData);

        // 13. Populate Blogs (`blogs`)
        $blogsData = [
            [
                'language' => 'bangla',
                'title' => '৪৬তম বিসিএস প্রিলিমিনারি পরীক্ষায় ২০০ নম্বরের নিখুঁত টাইম ম্যানেজমেন্ট কৌশল',
                'url' => 'bcs-preliminary-time-management-strategy',
                'image' => 'images/bcs_prep.jpg',
                'alt' => 'BCS Preliminary Strategy',
                'description' => '<p>বিসিএস প্রিলিমিনারি পরীক্ষায় সফল হওয়ার মূল চাবিকাঠি হলো দুই ঘণ্টার ১২০ মিনিটের নিখুঁত সদ্ব্যবহার। প্রতিটি প্রশ্নের পেছনে সর্বোচ্চ ৩৬ সেকেন্ড সময় বরাদ্দ থাকে। শুরুতে বাংলা ও সাধারণ জ্ঞান অংশের নিশ্চিত উত্তরগুলো ওএমআরে পূরণ করে নিলে পরবর্তী সময়ে গণিত ও ইংরেজি অংশে আত্মবিশ্বাস বহুগুণ বেড়ে যায়।</p><p>নেগেটিভ মার্কিং এড়াতে দ্বিধাগ্রস্ত উত্তর এড়িয়ে চলা এবং নিয়মিত ওএমআর শিটে ফুল মডেল টেস্ট অনুশীলনের কোনো বিকল্প নেই।</p>',
                'meta_title' => '৪৬তম বিসিএস প্রিলিমিনারি টাইম ম্যানেজমেন্ট গাইড',
                'meta_description' => 'বিসিএস প্রিলিমিনারি পরীক্ষায় সর্বোচ্চ নম্বর নিশ্চিত করার পরীক্ষিত কৌশল ও সময় বণ্টন।',
                'author' => 'মেহেদী হাসান (বিসিএস প্রশাসন)',
                'date' => '2026-10-15',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'language' => 'bangla',
                'title' => 'ব্যাংক নিয়োগ পরীক্ষায় গণিত ও অ্যানালিটিক্যাল রিজনিংয়ে ফুল মার্কস পাওয়ার উপায়',
                'url' => 'bank-job-math-and-analytical-mastery',
                'image' => 'images/bank_prep.jpg',
                'alt' => 'Bank Job Math Preparation',
                'description' => '<p>সমন্বিত ব্যাংক এবং বাংলাদেশ ব্যাংকের নিয়োগ পরীক্ষায় গণিত এবং অ্যানালিটিক্যাল অংশই মেধা তালিকার মূল নির্ধারক। গতানুগতিক বড় সমাধান পদ্ধতির পরিবর্তে বৈজ্ঞানিক শর্টকাট সূত্র, অপশন টেস্ট মেথড এবং নিয়মিত মেন্টাল অ্যালগরিদম প্র্যাকটিস করতে হবে।</p><p>প্রতিদিন অন্তত ২৫টি করে ডিফারেন্ট টাইপের ওয়ার্ড প্রবলেম সমাধানের অভ্যাস গড়ে তুলুন।</p>',
                'meta_title' => 'ব্যাংক পরীক্ষা গণিত প্রস্তুতি গাইড',
                'meta_description' => 'ব্যাংকিং পরীক্ষায় গণিতে সর্বোচ্চ মার্ক পাওয়ার উপায়।',
                'author' => 'নুসরাত জাহান (বাংলাদেশ ব্যাংক)',
                'date' => '2026-10-12',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'language' => 'english',
                'title' => 'Mastering English Vocabulary and Idioms for Competitive BCS & Bank Exams',
                'url' => 'mastering-english-vocabulary-competitive-exams',
                'image' => 'images/ntrca_prep.jpg',
                'alt' => 'English Vocabulary Strategy',
                'description' => '<p>Vocabulary retention requires contextual learning rather than rote memorization. Candidates should focus on high-frequency GRE words, root words, prefixes, and suffixes. Reading quality English editorials daily and practicing previous ten-year question papers builds intuitive grammatical accuracy.</p>',
                'meta_title' => 'Mastering English Vocabulary for Competitive Exams',
                'meta_description' => 'High yield strategies for BCS and Bank job English literature and grammar.',
                'author' => 'Prof. Tariqul Islam',
                'date' => '2026-10-10',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];
        DB::table('blogs')->insert($blogsData);

        try {
            DB::statement('ALTER TABLE notice CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        } catch (\Exception $e) {
            // ignore if already converted
        }

        // 14. Populate Notice Board (`notice`)
        $notices = [
            [
                'title' => '[লাইভ মডেল টেস্ট] ৪৬তম বিসিএস স্পেশাল মেগা লাইভ মডেল টেস্ট আগামী শুক্রবার রাত ৮:০০ টায় অনুষ্ঠিত হবে।',
                'active' => 1,
                'date' => $now
            ],
            [
                'title' => '[লেকচার শিট] প্রাথমিক শিক্ষক নিয়োগ ব্যাচের ৩য় সপ্তাহের গণিত লেকচার শিট ও ওএমআর সমাধান আপলোড করা হয়েছে।',
                'active' => 1,
                'date' => $now
            ],
            [
                'title' => '[ফলাফল প্রকাশ] সমন্বিত ১০ ব্যাংক সিনিয়র অফিসার লিখিত পরীক্ষার অ্যানালিটিক্যাল রেজাল্ট ও মেধা তালিকা প্রকাশিত হয়েছে।',
                'active' => 1,
                'date' => $now
            ],
            [
                'title' => '[ফ্রি ক্যারিয়ার সেমিনার] ১৮তম শিক্ষক নিবন্ধন (NTRCA) স্পেশাল জুম সেমিনার আগামী রবিবার সন্ধ্যা ৭:০০ টায় অনুষ্ঠিত হবে।',
                'active' => 1,
                'date' => $now
            ]
        ];
        DB::table('notice')->insert($notices);

        // 15. Populate Sliders & Banners
        $sliderInfoId = DB::table('slider_infos')->insertGetId([
            'title' => 'বিসিএস ও সরকারি চাকরি নিশ্চিত প্রস্তুতি ২০২৬',
            'description' => 'অভিজ্ঞ ক্যাডার ও ব্যাংক কর্মকর্তাদের প্রত্যক্ষ দিকনির্দেশনায় লাইভ মডেল টেস্ট ও ভিডিও লেকচার।',
            'active' => 1,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('slider_images')->insert([
            [
                'slider_info_id' => $sliderInfoId,
                'image' => 'images/bcs_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'slider_info_id' => $sliderInfoId,
                'image' => 'images/bank_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'slider_info_id' => $sliderInfoId,
                'image' => 'images/primary_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        DB::table('slider_banners')->insert([
            [
                'title' => 'বিসিএস প্রিলিমিনারি ও লিখিত স্পেশাল প্রস্তুতি',
                'alt' => '৪৬তম ও ৪৭তম বিসিএস পরীক্ষার জন্য পূর্ণাঙ্গ সিলেবাসভিত্তিক লাইভ ক্লাস ও রিয়েল-টাইম মডেল টেস্ট।',
                'image' => 'images/bcs_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'সমন্বিত ১০ ব্যাংক অফিসার ও সিনিয়র অফিসার',
                'alt' => 'বাংলাদেশ ব্যাংক ও রাষ্ট্রায়ত্ত ব্যাংকের সমন্বিত নিয়োগ পরীক্ষার স্পেশাল ম্যাথ, অ্যানালিটিক্যাল ও মডেল টেস্ট।',
                'image' => 'images/bank_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'প্রাথমিক সহকারী শিক্ষক ও NTRCA নিবন্ধন প্রস্তুতি',
                'alt' => 'অধ্যায়ভিত্তিক প্র্যাকটিস, পূর্ণাঙ্গ প্রশ্নব্যাংক ও অভিজ্ঞ মেন্টরদের নিবিড় তত্ত্বাবধানে প্রস্তুতি।',
                'image' => 'images/primary_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'title' => 'মন্ত্রণালয় ও নন-ক্যাডার সরকারি চাকরি প্রস্তুতি',
                'alt' => '১০ম থেকে ২০তম গ্রেডের সকল সরকারি চাকরির বিগত বছরের প্রশ্ন সমাধান ও ধারাবাহিক লাইভ পরীক্ষা।',
                'image' => 'images/govt_job_prep.jpg',
                'active' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]
        ]);

        // 16. Populate Sample Student Orders & Exam Results
        // Course Enrollment for student (ID: $studentId, course: 1)
        DB::table('courses_order')->insert([
            [
                'pid' => 1,
                'price' => '2500',
                'orderNum' => 'ORD-BCS-' . rand(10000, 99999),
                'transaction_id' => 'TXN' . rand(10000000, 99999999),
                'name' => 'Ridoy Paul',
                'email' => 'mesba@gmail.com',
                'phone' => '01712345678',
                'address' => 'Dhanmondi, Dhaka',
                'currency' => 'BDT',
                'amount' => 2500.00,
                'date' => $now->format('Y-m-d H:i:s'),
                'status' => 'Complete',
                'course_id' => 1,
                'user_id' => $studentId,
                'payment_status' => 'paid'
            ],
            [
                'pid' => 2,
                'price' => '1800',
                'orderNum' => 'ORD-BNK-' . rand(10000, 99999),
                'transaction_id' => 'TXN' . rand(10000000, 99999999),
                'name' => 'Ridoy Paul',
                'email' => 'mesba@gmail.com',
                'phone' => '01712345678',
                'address' => 'Dhanmondi, Dhaka',
                'currency' => 'BDT',
                'amount' => 1800.00,
                'date' => $now->subDays(2)->format('Y-m-d H:i:s'),
                'status' => 'Complete',
                'course_id' => 2,
                'user_id' => $studentId,
                'payment_status' => 'paid'
            ]
        ]);

        // Sample Results for Student
        $resId1 = DB::table('results')->insertGetId([
            'examID' => 1,
            'userID' => $studentId,
            'mcq_written' => 'mcq',
            'written_status' => null,
            'created_at' => $now->subDays(1),
            'updated_at' => $now->subDays(1)
        ]);

        $parentId1 = DB::table('mcq_results_parents')->insertGetId([
            'examID' => '1',
            'userID' => (string)$studentId,
            'created_at' => $now->subDays(1),
            'updated_at' => $now->subDays(1)
        ]);

        // Seed 3 answered questions for Exam 1
        $questions = DB::table('mcq_questions')->where('examID', 1)->get();
        foreach ($questions as $q) {
            DB::table('mcq_results')->insert([
                'examID' => 1,
                'userID' => $studentId,
                'qid' => $q->id,
                'userAns' => $q->rightAns, // Answered correctly
                'rightAns' => $q->rightAns,
                'mark' => '1.00',
                'obtained_mark' => '1.00',
                'negativeMark' => '0.25',
                'mcq_results_parent_id' => (string)$parentId1,
                'created_at' => $now->subDays(1),
                'updated_at' => $now->subDays(1)
            ]);
        }

        // Written exam result
        DB::table('results')->insert([
            'examID' => 3,
            'userID' => $studentId,
            'mcq_written' => 'written',
            'written_status' => 'checked',
            'created_at' => $now->subDays(2),
            'updated_at' => $now->subDays(1)
        ]);

        $wq = DB::table('written_questions')->where('examID', 3)->first();
        if ($wq) {
            DB::table('written_results')->insert([
                'examID' => 3,
                'userID' => $studentId,
                'qid' => $wq->id,
                'userAns' => 'Student uploaded answer script: বঙ্গবন্ধুর ৭ই মার্চের ভাষণ এবং মহান মুক্তিযুদ্ধের পটভূমি...',
                'mark' => '15.00',
                'rightansMark' => '13.50',
                'note' => 'অসাধারণ গঠনমূলক লেখা ও ঐতিহাসিক তথ্য নির্ভুল উপস্থাপন। বঙ্গানুবাদ অংশটিও প্রশংসনীয়।',
                'created_at' => $now->subDays(2),
                'updated_at' => $now->subDays(1)
            ]);
        }

        // 17. Populate About Us Information
        $aboutHtml = '
<p class="lead mb-4" style="font-size: 17px; font-weight: 600; color: #0f172a; line-height: 1.8;">
পাঠজল্পনা (Pathjolpona Education) বাংলাদেশের একটি সর্বাধুনিক ও প্রযুক্তিভিত্তিক বিশেষায়িত ক্যারিয়ার প্রস্তুতি প্ল্যাটফর্ম। বিসিএস, ব্যাংক, প্রাথমিক শিক্ষক নিয়োগ ও সকল সরকারি চাকরির জন্য এটি দেশের প্রথমসারির পূর্ণাঙ্গ ডিজিটাল একাডেমি।
</p>

<p style="margin-bottom: 16px; color: #334155; line-height: 1.8;">
২০২১ সালে প্রতিষ্ঠিত পাঠজল্পনার মূল দর্শন হলো দেশের প্রত্যন্ত অঞ্চলের প্রতিটি শিক্ষার্থীর কাছে নামমাত্র মূল্যে সেরা বিসিএস ক্যাডার ও ব্যাংকারদের প্রত্যক্ষ গাইডলাইন পৌঁছে দেওয়া। গতানুগতিক কোচিং বা গাইড বইয়ের মুখস্থবিদ্যার বাইরে এসে আমরা শিক্ষার্থীদের প্রদান করি বাস্তবমুখী ওএমআর (OMR) সিমুলেশন, নির্ভুল নেগেটিভ মার্কিং এবং প্রতিটি পরীক্ষার পরই স্বয়ংক্রিয় মেধা তালিকা।
</p>

<div class="row g-3 my-4">
    <div class="col-sm-6 col-12">
        <div class="p-3 rounded-3" style="background: #f8fafc; border-left: 4px solid #03c6bb;">
            <h6 style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">লাইভ ওএমআর টেস্ট ইঞ্জিন</h6>
            <p style="font-size: 13px; color: #64748b; margin: 0;">প্রকৃত বিসিএস পরীক্ষার মতো ঘড়ি ধরে লাইভ পরীক্ষা ও নেগেটিভ মার্কিং গণনা।</p>
        </div>
    </div>
    <div class="col-sm-6 col-12">
        <div class="p-3 rounded-3" style="background: #f8fafc; border-left: 4px solid #4f46e5;">
            <h6 style="font-weight: 700; color: #0f172a; margin-bottom: 4px;">ক্যাডার ও ব্যাংকারদের মেন্টরশিপ</h6>
            <p style="font-size: 13px; color: #64748b; margin: 0;">বিসিএস প্রশাসন, পুলিশ ক্যাডার ও বাংলাদেশ ব্যাংকের সিনিয়র কর্মকর্তাদের নিয়মিত ক্লাস।</p>
        </div>
    </div>
</div>

<p style="margin-bottom: 16px; color: #334155; line-height: 1.8;">
আমাদের বিশেষায়িত প্রশ্নব্যাংক প্রতিনিয়ত পিএসসি (BPSC) ও ব্যাংকার্স সিলেকশন কমিটির (BSCC) সর্বশেষ সিলেবাস ও প্রশ্নের প্যাটার্ন অনুযায়ী আপডেট করা হয়। লিখিত পরীক্ষার জন্য রয়েছে বিশেষ উত্তরপত্র মূল্যায়ন এবং ওয়ান-অন-ওয়ান ফিডব্যাক ব্যবস্থা।
</p>';

        DB::table('about_infos')->insert([
            'aboutDes' => $aboutHtml,
            'image' => 'images/about_pathjolpona.jpg',
            'created_at' => $now,
            'updated_at' => $now
        ]);

        DB::table('abouts')->insert([
            'title_english' => 'Pathjolpona Education',
            'title_bangla' => 'পাঠজল্পনা - ক্যারিয়ার ও বিসিএস প্রস্তুতি',
            'details_1_eng' => '<p>Pathjolpona is a premier EdTech platform providing comprehensive mock exams, video modules, and mentorship for BCS, Bank, Primary, and NTRCA examinations.</p>',
            'details_1_bng' => $aboutHtml,
            'main_image' => 'images/about_pathjolpona.jpg',
            'banner_image' => 'images/about_pathjolpona.jpg',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
}
