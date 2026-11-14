<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Image ;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseModule;

class ExamCon extends Controller
{
    // Helper to attach stats and counts
    private function attachExamStats($exams) {
        $examIds = $exams->pluck('exam_id')->toArray();
        if (empty($examIds)) {
            return $exams;
        }

        $mcqStats = DB::table('mcq_questions')
            ->whereIn('examID', $examIds)
            ->select('examID', DB::raw('COUNT(*) as total_questions'), DB::raw('SUM(mark) as total_marks'))
            ->groupBy('examID')
            ->get()
            ->keyBy('examID');

        $writtenStats = DB::table('written_questions')
            ->whereIn('examID', $examIds)
            ->select('examID', DB::raw('COUNT(*) as total_questions'), DB::raw('SUM(mark) as total_marks'))
            ->groupBy('examID')
            ->get()
            ->keyBy('examID');

        foreach ($exams as $exam) {
            if ($exam->mcq_written == 'mcq') {
                $stat = $mcqStats->get($exam->exam_id);
            } else {
                $stat = $writtenStats->get($exam->exam_id);
            }
            $exam->total_questions = $stat ? ($stat->total_questions ?? 0) : 0;
            $exam->total_marks = $stat ? ($stat->total_marks ?? 0) : 0;
        }

        return $exams;
    }

    // All Exams (MCQ & Written)
    public function indexAll(){
        $exams = DB::table('exams')
            ->leftJoin('categories', 'exams.catID', 'categories.id')
            ->select('exams.*', 'categories.name as cat_name', 'categories.name as name', 'categories.free_paid as cat_free_paid', 'categories.free_paid as free_paid', 'exams.id as exam_id', 'exams.active as exam_active')
            ->orderBy('exams.id', 'DESC')
            ->get();
        $exams = $this->attachExamStats($exams);

        $counts = [
            'all' => DB::table('exams')->count(),
            'mcq' => DB::table('exams')->where('mcq_written', 'mcq')->count(),
            'written' => DB::table('exams')->where('mcq_written', 'written')->count(),
            'permanent' => DB::table('exams')->where('type', 'permanent')->count(),
            'temporary' => DB::table('exams')->where('type', 'temporary')->count(),
        ];

        $pageTitle = "All Exams (MCQ & Written)";
        $activeTab = "all";
        return view('cms.exam.all_exams', compact('exams', 'pageTitle', 'activeTab', 'counts'));
    }

    // All MCQ Exams
    public function indexMCQ(){
        $exams = DB::table('exams')
            ->leftJoin('categories', 'exams.catID', 'categories.id')
            ->select('exams.*', 'categories.name as cat_name', 'categories.name as name', 'categories.free_paid as cat_free_paid', 'categories.free_paid as free_paid', 'exams.id as exam_id', 'exams.active as exam_active')
            ->where('exams.mcq_written', 'mcq')
            ->orderBy('exams.id', 'DESC')
            ->get();
        $exams = $this->attachExamStats($exams);

        $counts = [
            'all' => DB::table('exams')->count(),
            'mcq' => DB::table('exams')->where('mcq_written', 'mcq')->count(),
            'written' => DB::table('exams')->where('mcq_written', 'written')->count(),
            'permanent' => DB::table('exams')->where('type', 'permanent')->count(),
            'temporary' => DB::table('exams')->where('type', 'temporary')->count(),
        ];

        $pageTitle = "All MCQ Exams";
        $activeTab = "mcq";
        return view('cms.exam.all_exams', compact('exams', 'pageTitle', 'activeTab', 'counts'));
    }

    // All Written Exams
    public function indexWritten(){
        $exams = DB::table('exams')
            ->leftJoin('categories', 'exams.catID', 'categories.id')
            ->select('exams.*', 'categories.name as cat_name', 'categories.name as name', 'categories.free_paid as cat_free_paid', 'categories.free_paid as free_paid', 'exams.id as exam_id', 'exams.active as exam_active')
            ->where('exams.mcq_written', 'written')
            ->orderBy('exams.id', 'DESC')
            ->get();
        $exams = $this->attachExamStats($exams);

        $counts = [
            'all' => DB::table('exams')->count(),
            'mcq' => DB::table('exams')->where('mcq_written', 'mcq')->count(),
            'written' => DB::table('exams')->where('mcq_written', 'written')->count(),
            'permanent' => DB::table('exams')->where('type', 'permanent')->count(),
            'temporary' => DB::table('exams')->where('type', 'temporary')->count(),
        ];

        $pageTitle = "All Written Exams";
        $activeTab = "written";
        return view('cms.exam.all_exams', compact('exams', 'pageTitle', 'activeTab', 'counts'));
    }

    // Exam index page
    public function indexParment(){
        $exams = DB::table('exams')
        ->join('categories', 'exams.catID', 'categories.id')
        ->select('exams.*', 'categories.*', 'exams.id as exam_id', 'exams.active as exam_active')
        ->where('exams.type', 'permanent')
        ->orderBy('exams.id', 'DESC')
        ->get();
        return view('cms.exam.permanent_exam', compact('exams'));
    }

    public function indexTemporary(){
        $exams = DB::table('exams')
        ->join('categories', 'exams.catID', 'categories.id')
        ->select('exams.*', 'categories.*', 'exams.id as exam_id', 'exams.active as exam_active')
        ->where('exams.type', 'temporary')
        ->orderBy('exams.id', 'DESC')
        ->get();
        return view('cms.exam.temporary_exam', compact('exams'));
    }

    //Begin:: cms add exam page
    public function Add_exams() {
        $categories = DB::table('categories')->get();
        return view('cms.exam.add_exam', compact('categories'));
    }
    //End:: cms add exam page

    // Add Exam Confirm Function
    public function Add_exams_Confirm(Request $request){

        $request->validate([
            'exam_name' => 'required|unique:exams',
        ]);

        $data = array();
        $data['catID'] = $request->catID;
        $data['exam_name'] = $request->exam_name;
        $data['mcq_written'] = $request->mcq_written;
        $data['free_paid'] = 'null';
        $data['tk'] = 0;
        $data['type'] = $request->type;
        $data['startDate'] = $request->startDate;
        $data['examTime'] = $request->examTime;
        $data['active'] = $request->active;
        $data['written_paragraph'] = $request->editor1;
        $data['created_at'] = Carbon::now();

        DB::table('exams')->insert($data);
        if($request->type != 'temporary') {
            return Redirect()->route('cms.permanent_exams')->with('status', 'New Exam Added Successfully');
        }
        else {
            return Redirect()->route('cms.temporary_exams')->with('status', 'New Exam Added Successfully');
        }


    }

    // Edit Exam Function
    public function EditExam($id){
        $categories = DB::table('categories')->get();
        $exam = DB::table('exams')-> where('id', $id) ->first();
        return view('cms.exam.edit_exam', compact('exam', 'categories'));
    }
    // Edit result Function
    public function allresult($id){

        $exam_info = DB::table('exams')->where('id', $id)->first();

       if($exam_info->mcq_written == 'mcq') {
           $mcqExams = DB::table('mcq_results')
                   ->select('userID', DB::raw("SUM(obtained_mark) as total"))
                   ->groupBy('userID')
                   ->where('examID', $id)
                   ->orderBy('total', 'DESC')
                   ->get();
       }
       else if($exam_info->mcq_written == 'written') {
           $mcqExams = DB::table('written_results')
                   ->select('userID', DB::raw("SUM(rightansMark) as total"))
                   ->groupBy('userID')
                   ->where('examID', $id)
                   ->orderBy('total', 'DESC')
                   ->get();
       }

        return view('cms.exam.mcq_result_view', compact('mcqExams', 'exam_info'));
    }

    //Begin::Update Exam Function
    public function UpdateExams(Request $request, $id){

        $data = array();
        $data['catID'] = $request->catID;
        $data['exam_name'] = $request->exam_name;
        $data['mcq_written'] = $request->mcq_written;
        $data['free_paid'] = 'null';
        $data['tk'] = 0;
        $data['type'] = $request->type;
        $data['startDate'] = $request->startDate;
        $data['examTime'] = $request->examTime;
        $data['active'] = $request->active;
        $data['written_paragraph'] = $request->editor1;
        $data['created_at'] = Carbon::now();

        DB::table('exams')->where('id', $id)->update($data);
        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('status', 'Exam Updated Successfully');
        }
        if($request->type != 'temporary') {
            return Redirect()->route('cms.permanent_exams')->with('status', 'Exam Update Successfully');
        }
        else {
            return Redirect()->route('cms.temporary_exams')->with('status', 'Exam Update Successfully');
        }
    }

    public function DeleteExams($id) {
        DB::table('mcq_questions')->where('examID', $id)->delete();
        DB::table('written_questions')->where('examID', $id)->delete();
        DB::table('exams')->where('id', $id)->delete();

        return redirect()->back()->with('status', 'Exam Deleted Successfully');
    }
    //End::Update Exam Function


    //Begin::Exam MCQ questions page
    public function Exam_MCQ_Questions_Page($id){
        $exam = DB::table('exams')->where('id', $id)->first();
        $mcqQuestions = DB::table('mcq_questions')->where('examID', $id)->get();
        return view('cms.exam.mcq_questions', compact('exam', 'mcqQuestions'));
    }
    public function Exam_written_Questions_Page_edit($id){

        $WrittenQuestions = DB::table('written_questions')->find($id);
         $exam = DB::table('exams')->where('id', $WrittenQuestions->examID)->first();
        return view('cms.exam.wrritten_questions_edit', compact('exam', 'WrittenQuestions'));
    }
    public function Exam_MCQ_Questions_Page_edit($id){

        $mcqQuestions = DB::table('mcq_questions')->find($id);
         $exam = DB::table('exams')->where('id', $mcqQuestions->examID)->first();
        return view('cms.exam.mcq_questions_edit', compact('exam', 'mcqQuestions'));
    }
    public function addd_MCQ_Questions_Page ($id){
       $exam = DB::table('exams')->where('id', $id)->first();
        $mcqQuestions = DB::table('mcq_questions')->where('examID', $id)->get();
        return view('cms.exam.add_mcq_questions', compact('exam', 'mcqQuestions'));
    }
    //End::Exam MCQ questions page

    //Begin::Add MCQ questions Confirm
    public function Add_MCQ_Questions(Request $request){

        if(!empty($request->QID)) {
            $count = count($request->QID);
            $data = array();

            for($i = 0; $i < $count; $i++) {
                if($request->QID[$i] == 0){ // New Questions

                    $data['examID'] = $request->examID;
                    $data['questions'] = $request->questions[$i];
                    $data['optn1'] = $request->optn1[$i];
                    $data['optn2'] = $request->optn2[$i];
                    $data['optn3'] = $request->optn3[$i];
                    $data['optn4'] = $request->optn4[$i];
                    $data['optn5'] = $request->optn5[$i];
                    $data['rightAns'] = $request->rightAns[$i];
                    $data['mark'] = $request->mark[$i];
                    $data['negativeMark'] = $request->negativeMark[$i];
                    $data['writeAnsDes'] = $request->writeAnsDes[$i];
                    $data['created_at'] = Carbon::now();

                    DB::table('mcq_questions')->insert($data);

                }
                else {
                    $data['questions'] = $request->questions[$i];
                    $data['optn1'] = $request->optn1[$i];
                    $data['optn2'] = $request->optn2[$i];
                    $data['optn3'] = $request->optn3[$i];
                    $data['optn4'] = $request->optn4[$i];
                    $data['optn5'] = $request->optn5[$i];
                    $data['rightAns'] = $request->rightAns[$i];
                    $data['mark'] = $request->mark[$i];
                    $data['negativeMark'] = $request->negativeMark[$i];
                    $data['writeAnsDes'] = $request->writeAnsDes[$i];
                    $data['updated_at'] = Carbon::now();
                    DB::table('mcq_questions')-> where('id', $request->QID[$i])->update($data);
                }
            }

            return back()->with('status', 'MCQ Questions Update Successfully');


        }
        else {
            return Redirect()->back();
        }
    }
    //End::Add MCQ questions Confirm

    //Begin::Exam Written questions page
    public function Exam_Written_Questions_Page($id){
        $exam = DB::table('exams')->where('id', $id)->first();
        $WrittenQuestions = DB::table('written_questions')->where('examID', $id)->get();
        return view('cms.exam.written_questions', compact('exam', 'WrittenQuestions'));
    }
    public function add_written_Questions_Page($id){
        $exam = DB::table('exams')->where('id', $id)->first();
        $WrittenQuestions = DB::table('written_questions')->where('examID', $id)->get();
        return view('cms.exam.add_written_questions', compact('exam', 'WrittenQuestions'));
    }
    //End::Exam Written questions page

    //Begin::Add Written questions Confirm
    public function Add_Written_Questions(Request $request){

        if(!empty($request->QID)) {
            $count = count($request->QID);
            $data = array();

            for($i = 0; $i < $count; $i++) {
                if($request->QID[$i] == 0){ // New Questions

                    $data['examID'] = $request->examID;
                    $data['questions'] = $request->questions[$i];
                    $data['mark'] = $request->mark[$i];
                    $data['ans'] = $request->ans[$i];
                    $data['created_at'] = Carbon::now();
                    DB::table('written_questions')->insert($data);

                }
                else {
                    $data['examID'] = $request->examID;
                    $data['questions'] = $request->questions[$i];
                    $data['mark'] = $request->mark[$i];
                    $data['ans'] = $request->ans[$i];
                    $data['updated_at'] = Carbon::now();
                    DB::table('written_questions')-> where('id', $request->QID[$i])->update($data);
                }
            }
            return Redirect()->back()->with('status', 'Written Questions Update Successfully');
        }
        else {
            return Redirect()->back();
        }
    }
    //End::Add Written questions Confirm

    // Begin:: MCQ CSV Confirm
    public function MCQ_questions_CSV_Confirm(Request $request) {
        $filename= $request->csvFile;
        $file = fopen($filename, "r");
        $i = 1;
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {

            if(!empty($getData[0])) {
                $examCheck = DB::table('exams')->where('id', $getData[0])->first();
                $existQcheck = DB::table('mcq_questions')->where('questions', $getData[1])->where('examID', $getData[0])->first();
                if(!empty($examCheck->id) && ($examCheck->mcq_written == 'mcq') && empty($existQcheck->id) && !empty($getData[1]) && !empty($getData[2]) && !empty($getData[3]) && !empty($getData[4]) && !empty($getData[7])) {
                    if($getData[8] == ''){
                        $mark = 1;
                    }
                    else {
                        $mark = $getData[8];
                    }

                    if($getData[9] == ''){
                        $Negativemark = '-1';
                    }
                    else {
                        $Negativemark = $getData[9];
                    }

                    $data = array();

                    $data['examID'] = $examCheck->id;
                    $data['questions'] = $getData[1];
                    $data['optn1'] = $getData[2];
                    $data['optn2'] = $getData[3];
                    $data['optn3'] = $getData[4];
                    $data['optn4'] = $getData[5];
                    $data['optn5'] = $getData[6];
                    $data['rightAns'] = $getData[7];
                    $data['mark'] = $mark;
                    $data['negativeMark'] = $Negativemark;
                    $data['writeAnsDes'] = $getData[10];
                    $data['created_at'] = Carbon::now();

                    DB::table('mcq_questions')->insert($data);
                    $i++;
                }

            }

        $ss= substr(str_shuffle($getData[0]),0, 4).rand(0,3);
        }
        fclose($file);

        if($i > 1){
            return Redirect()->route('/cms')->with('status', 'MCQ Questions Upload Successfully.');
        }
        else {
            return Redirect()->back()->with('adminError', 'No MCQ Questions Uploaded!');
        }

    }
    // End:: MCQ CSV Confirm

    // Begin:: Written CSV Confirm
    public function Written_questions_CSV_Confirm(Request $request) {
        $filename= $request->csvFile;
        $file = fopen($filename, "r");
        $i = 1;
        while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {

            if(!empty($getData[0])) {
                $examCheck = DB::table('exams')->where('id', $getData[0])->first();
                $existQcheck = DB::table('written_questions')->where('questions', $getData[1])->where('examID', $getData[0])->first();
                if(!empty($examCheck->id) && ($examCheck->mcq_written == 'written') && empty($existQcheck->id) && !empty($getData[1]) && !empty($getData[2]) && !empty($getData[3])) {

                    $data = array();
                    $data['examID'] = $examCheck->id;
                    $data['questions'] = $getData[1];
                    $data['mark'] = $getData[2];
                    $data['ans'] = $getData[3];
                    $data['created_at'] = Carbon::now();

                    DB::table('written_questions')->insert($data);
                    $i++;
                }

            }

        $ss= substr(str_shuffle($getData[0]),0, 4).rand(0,3);
        }
        fclose($file);

        if($i > 1){
            return Redirect()->route('/cms')->with('status', 'Written Questions Upload Successfully.');
        }
        else {
            return Redirect()->back()->with('adminError', 'No Written Questions Uploaded!');
        }

    }
    // End:: Written CSV Confirm


    // Begin:: Student MCQ Reports page
    public function student_mcq_result_page() {
        $mcqExams = DB::table('results')
                    ->join('exams', 'results.examID', 'exams.id')
                    ->select('results.*', 'exams.exam_name')
                    ->where('results.mcq_written', 'mcq')
                    ->orderBy('results.id', 'desc')
                    ->latest()->get();
        return view('cms.exam.mcq_result', compact('mcqExams'));

    }

    public function delete_mcq_result_page($id) {
        // Retrieve the teacher record to get the image path
        DB::table('results')-> where('id', $id)->first();
        DB::table('results')-> where('id', $id)->delete();

        return redirect()->back()->with('status', 'Blog Deleted Successfully');
    }
    // End:: Student MCQ Reports page

    // Begin:: Student Written Reports page
    public function student_written_result_page() {
        $writtenExams = DB::table('results')
                    ->join('exams', 'results.examID', 'exams.id')
                    ->select('results.*', 'exams.exam_name')
                    ->where('results.mcq_written', 'written')
                    ->orderBy('results.id', 'desc')
                    ->latest()->get();
        return view('cms.exam.written_result', compact('writtenExams'));

    }
    // End:: Student Written Reports page

    // Begin:: Student Written Exam review page
    public function review_written_exam_cms($id) {
        $writtenExam = DB::table('results')
                    ->join('exams', 'results.examID', 'exams.id')
                    ->select('results.*', 'exams.exam_name')
                    ->where('results.id', $id)
                    ->first();
        return view('cms.exam.review_written_exam', compact('writtenExam'));

    }
    // End:: Student Written Exam review page

    // Begin:: Set written exam mark
    public function set_written_exam_mark() {
        $qid = $_GET['qid'];
        $userID = $_GET['userID'];
        $mark = $_GET['mark'];

        $data = array();
        $data['rightansMark'] = $mark;
        $data['updated_at'] = Carbon::now();

        DB::table('written_results')->where('id', $qid)->where('userID', $userID)->update($data);

        $sts = [
            'status' => 'yes',
        ];
        return response()->json($sts);
    }
    // End:: Set written exam mark

    // Begin:: Set written exam Review Status
    public function SET_written_exam_review_status() {
        $status = $_GET['status'];
        $userID = $_GET['userID'];
        $examID = $_GET['examID'];

        $data = array();
        $data['written_status'] = $status;
        $data['updated_at'] = Carbon::now();

        DB::table('results')->where('examID', $examID)->where('userID', $userID)->update($data);

        $sts = [
            'status' => 'yes',
        ];
        return response()->json($sts);
    }
    // End:: Set written exam Review Status

    // Begin:: Set written exam Note
    public function SET_written_exam_note() {
        $note = $_GET['note'];
        $qid = $_GET['qid'];
        $userID = $_GET['userID'];


        $data = array();
        $data['note'] = $note;
        $data['updated_at'] = Carbon::now();

        DB::table('written_results')->where('id', $qid)->where('userID', $userID)->update($data);

        $sts = [
            'status' => 'yes',
        ];
        return response()->json($sts);
    }
    // End:: Set written exam Note



    // Begin:: Sum written exam Result
    public function Sum_written_exam() {
        $userID = $_GET['userID'];
        $examID = $_GET['examID'];


        $totalSum = DB::table('written_results')->where('examID', $examID)->where('userID', $userID)->sum('rightansMark');

        $sts = [
            'status' => 'yes',
            'totalSum' => $totalSum,
        ];
        return response()->json($sts);
    }
     // End:: Sum written exam Result


    /**
     * Validate course metadata and nested chapters/videos payload.
     */
    private function validateCourseRequest(Request $request, bool $isCreate): array
    {
        return $request->validate([
            'catID' => 'required',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => ($isCreate ? 'required' : 'nullable') . '|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'description' => 'nullable|string',
            'rank' => 'nullable|integer',
            'chapters' => 'nullable|array',
            'chapters.*.id' => 'nullable|integer',
            'chapters.*.title' => 'required|string|max:255',
            'chapters.*.description' => 'nullable|string',
            'chapters.*.sort_order' => 'nullable|integer|min:0',
            'chapters.*.videos' => 'nullable|array',
            'chapters.*.videos.*.id' => 'nullable|integer',
            'chapters.*.videos.*.title' => 'required|string|max:255',
            'chapters.*.videos.*.link' => 'required|string|max:2000',
            'chapters.*.videos.*.duration' => 'nullable|string|max:100',
            'chapters.*.videos.*.free_paid' => 'required|in:free,paid',
            'chapters.*.videos.*.sort_order' => 'nullable|integer|min:0',
        ], [], [
            'catID' => 'course category',
            'chapters.*.title' => 'chapter title',
            'chapters.*.videos.*.title' => 'video name',
            'chapters.*.videos.*.link' => 'video link',
            'chapters.*.videos.*.free_paid' => 'free/paid status',
        ]);
    }

    /**
     * Save uploaded course image into public/images and return relative path.
     */
    private function storeCourseImage($uploadedImage): string
    {
        $dir = public_path('images');
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $nameGen = hexdec(uniqid()) . '.' . $uploadedImage->getClientOriginalExtension();
        Image::make($uploadedImage)->resize(800, 630)->save($dir . DIRECTORY_SEPARATOR . $nameGen);

        return 'images/' . $nameGen;
    }

    /**
     * Delete existing course image from public directory if present.
     */
    private function removeCourseImage(?string $relativePath): void
    {
        if (empty($relativePath)) {
            return;
        }

        $publicPath = public_path($relativePath);
        if (file_exists($publicPath)) {
            unlink($publicPath);
        } elseif (file_exists($relativePath)) {
            unlink($relativePath);
        }
    }

    public function add_course()
    {
        $categories = DB::table('course_categories')->get();
        return view('cms.course.create', compact('categories'));
    }

    public function course_list()
    {
        $courses = Course::withCount(['chapters', 'courseModules'])->get();
        return view('cms.course.list', compact('courses'));
    }

    public function Add_course_Confirm(Request $request)
    {
        $this->validateCourseRequest($request, true);

        DB::transaction(function () use ($request) {
            $course = new Course;
            $course->catID = $request->catID;
            $course->name = $request->name;
            $course->price = $request->price;
            $course->description = $request->description;
            $course->active = 1;

            if ($request->hasFile('image')) {
                $course->image = $this->storeCourseImage($request->file('image'));
            }

            $course->save();
            $course->syncChaptersAndVideos($request->input('chapters', []));
        });

        return redirect('/cms/course/list')->with('status', 'New Course Added Successfully');
    }

    public function update_course_Confirm(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $this->validateCourseRequest($request, false);

        DB::transaction(function () use ($request, $course) {
            $course->catID = $request->catID;
            $course->name = $request->name;
            $course->price = $request->price;
            $course->description = $request->description;
            $course->rank = $request->rank;
            $course->active = 1;

            if ($request->hasFile('image')) {
                $this->removeCourseImage($course->image);
                $course->image = $this->storeCourseImage($request->file('image'));
            }

            $course->save();
            $course->syncChaptersAndVideos($request->input('chapters', []));
        });

        return redirect()->back()->with('status', 'Course Updated Successfully');
    }

    public function deactivecourse($id)
    {
        $course = Course::findOrFail($id);
        $course->active = 0;
        $course->save();
        return back()->with('status', 'Deactive Successfully');
    }

    public function activecourse($id)
    {
        $course = Course::findOrFail($id);
        $course->active = 1;
        $course->save();
        return back()->with('status', 'Active Successfully');
    }

    public function editcourse($id)
    {
        $course = Course::with(['chapters.videos', 'courseModules'])->findOrFail($id);
        $categories = DB::table('course_categories')->get();

        $chaptersData = $course->chapters->map(function ($chapter) {
            return [
                'id' => $chapter->id,
                'title' => $chapter->title,
                'description' => $chapter->description,
                'sort_order' => $chapter->sort_order,
                'videos' => $chapter->videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'link' => $video->link,
                        'duration' => $video->duration,
                        'free_paid' => $video->free_paid === 'free' ? 'free' : 'paid',
                        'sort_order' => $video->sort_order,
                    ];
                })->values()->all(),
            ];
        })->values()->all();

        $orphanVideos = $course->courseModules->whereNull('chapter_id');
        if ($orphanVideos->isNotEmpty()) {
            $chaptersData[] = [
                'id' => '',
                'title' => 'Uncategorized Videos',
                'description' => '',
                'sort_order' => count($chaptersData) + 1,
                'videos' => $orphanVideos->values()->map(function ($video, $index) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'link' => $video->link,
                        'duration' => $video->duration,
                        'free_paid' => $video->free_paid === 'free' ? 'free' : 'paid',
                        'sort_order' => $video->sort_order ?: ($index + 1),
                    ];
                })->all(),
            ];
        }

        return view('cms.course.edit', compact('course', 'categories', 'chaptersData'));
    }

    public function deleteCourse($id)
    {
        $course = Course::find($id);

        if ($course) {
            DB::transaction(function () use ($course) {
                $this->removeCourseImage($course->image);
                CourseModule::where('course_id', $course->id)->delete();
                CourseChapter::where('course_id', $course->id)->delete();
                $course->delete();
            });

            return redirect()->route('cms.course.list')->with('status', 'Course Deleted Successfully');
        }

        return redirect()->route('cms.course.list')->with('error', 'Course Not Found');
    }

    public function editCourseModule(Request $request)
    {
        $module = CourseModule::find($request->id);
        if ($module) {
            $module->title = $request->title;
            $module->link = $request->link;
            $module->free_paid = $request->free_paid;
            $module->save();

            return response()->json(['status' => 'Module updated successfully']);
        }
        return response()->json(['status' => 'Module not found'], 404);
    }

    public function deleteCourseModule(Request $request)
    {
        $module = CourseModule::find($request->id);
        if ($module) {
            $module->delete();
            return response()->json(['status' => 'Module deleted successfully']);
        }
        return response()->json(['status' => 'Module not found'], 404);
    }



}
