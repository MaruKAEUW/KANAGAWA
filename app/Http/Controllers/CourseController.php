<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\courseData;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CourseRequest;
use App\Models\Order;
use App\Models\OrderTamp;
use App\Models\Question;
use App\Models\Test;
use App\Models\TestUser;
use App\Models\User;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Carbon;

class CourseController extends Controller
{
    public function index(Request $request)
    {
      
        $page = $request->page ?? 1;
        $pageSize = $request->per_page ?? 100;
        $query  = Course::orderByDesc('created_at')->where('deleted_at', Course::UNDELETE)->with('User');
        if($request->course_type){
            $course = $query->where('course_type', $request->course_type);

        }
        if ($request->status) {
            $course = $query->where('status', $request->status);
        }
        if ($pageSize) {
            $course = $query->paginate($pageSize, ['*'], 'page', $page);
        } else {
            $course = $query->get();
        }

        return response()->json([
            'data' => $course,
            'success' => true,
            'message' => 'User list retrieved successfully'
        ]);
    }


    public function index_web(Request $request){
        $user  = User::orderByDesc('created_at')->where('role_id', 2)
        ->where('deleted_at', User::UNDELETE)->get();
        $query  = Course::orderByDesc('created_at')->where('deleted_at', Course::UNDELETE)->with('User')->get();
        return view('course.index',compact('query','user'));
    }


    public function store_web(Request $request){
        $request->merge([
            'price' => str_replace(',', '', $request->price)
        ]);
        $categoryController = new CourseController();
        $storeCategoryRequest = CourseRequest::createFromBase($request);
        $a = $categoryController->store($storeCategoryRequest);

        if($a->original['success']){
            toastr()->success($a->original['message']);
            return redirect()->route('course.index');
        }else{
            toastr()->error($a->original['message']);
            return redirect()->route('course.index');
        }
    }

    public function store(CourseRequest $request)
    {
        $images = null;
        if ($request->hasFile('images') && $request->file('images')->isValid()) {
          $images =  $this->uploadFile($request->file('images'));
           
        }
        $course = Course::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => $request->user_id,
            'number_of_lessons' => $request->number_of_lessons,
            'course_type' => $request->course_type,
            'status' => Course::ACTIVE,
            'deleted_at' => Course::UNDELETE,
            'images' => $images,

        ]);

        if ((int)$request->number_of_lessons > 0) {
            for ($i = 0; $i < $request->number_of_lessons; $i++) {
                courseData::create([
                    'course_id' => $course->id
                ]);
            }
        }
        return response()->json([
            'data' => $course,
            'success' => true,
            'message' => 'course created successfully'
        ]);
    }

    public function edit_web($id){
        // dd(1);
        $edit = $this->edit($id);
        // dd($edit);
        // dd($edit);
        if($edit->original['success']){
            $data = $edit->original['data'];
            // dd($data);
            toastr()->success($edit->original['message']);
            return view('course.edit',compact('data'));
        }else{
            toastr()->error($edit->original['message']);
            return redirect()->route('course.index');
        }
   
    }

    public function edit($id)
    {
        $course = Course::with('User', 'CourseData', 'RateYo')->find($id);
        // dd(1);
        if ($course) {
            return response()->json([
                'data' => $course,
                'success' => true,
                'message' => 'Lấy khóa học thàn công'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'course not found'
            ], 404);
        }
    }
    public function update_web(Request $request){
        // dd($request->all());
        $request->merge([
            'price' => str_replace(',', '', $request->price)
        ]);

        $a = $this->update($request->id,$request);
        if($a->original['success']){
            toastr()->success($a->original['message']);
            return redirect()->route('course.index');
        }else{
            toastr()->error($a->original['message']);
            return redirect()->route('course.index');
        }

    }

    public function update($id, Request $request)
    {
        $course = Course::find($id);
        if ($course) {
            $course->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'user_id' => $request->user_id,
                'course_type' => $request->course_type,
                'status' => $request->status,

            ]);
            if ($request->hasFile('images') && $request->file('images')->isValid()) {
                $course->update([
                    'images' => $this->uploadFile($request->file('images'))
                ]);
            }
           
            if($request->status == 2){
                $order_tamp =  OrderTamp::where('course_id',$id)->get();
            
                foreach($order_tamp as $item){
                    $order = OrderTamp::find($item->id);
                    if($order){
                        $order->delete();
                    }
                }
            }
            return response()->json([
                'data' => $course,
                'success' => true,
                'message' => 'course updated successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'course not found'
            ], 404);
        }
    }

    public function destroy_web($id){
        $delete = $this->destroy($id);
        if($delete->original['success']){
            toastr()->success($delete->original['message']);
            return redirect()->route('course.index');
        }else{
            toastr()->error($delete->original['message']);
            return redirect()->route('course.index');
        }
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if ($course) {
            $course->update([
                'deleted_at' => Course::DELETE,
            ]);
            $order_tamp =  OrderTamp::where('course_id',$id)->get();
                foreach($order_tamp as $item){
                    $order = OrderTamp::find($item->id);
                    if($order){
                        $order->delete();
                    }
                }
            return response()->json([
                'success' => true,
                'message' => 'Xóa khóa học thành công'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'course not found'
            ], 404);
        }
    }



    public function storeVideo_web(Request $request){
        $a = $this->updateVideo($request->id,$request);
        if($a->original['success']){
            toastr()->success($a->original['message']);
            return redirect()->back();
        }else{
            toastr()->error($a->original['message']);
            return redirect()->back();
        }
    }
    public function updateVideo($id, Request $request)
    {
        $course = courseData::find($id);
        // if ($request->hasFile('video')) {
        //     $a =  $this->uploadVideo($request);
        //     $course->update(['video' => $a]);
        // }
        $course->update([
            'description' => $request->description,
            'video' => $request->video
        ]);
        return response()->json([
            'data' => $course,
            'success' => true,
            'message' => 'Video updated successfully'
        ]);
    }

    public function uploadVideo(Request $request)
    {
        $file = $request->file('video');
        $filename = Str::random(20) . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/videos', $filename);
        return asset(Storage::url($path));
    }

    public function addQuestion_web(Request $request){
  

        $a = $this->addQuestion($request);
        if($a->original['success']){
            toastr()->success($a->original['message']);
            return redirect()->back();
        } else{
            toastr()->error($a->original['message']);
            return redirect()->route('course.edit');
        }
    }

    public function addQuestion(Request $request)
    {

        $question_content = $request->question_content;
        $check = Question::where('course_data_id', $request->course_data_id)->get();
        if ($check->isNotEmpty()) {
            foreach ($check as $question) {
                $question->delete();
            }
        }

        foreach ($question_content as $key => $course) {
            Question::create([
                'course_data_id' => $request->course_data_id,
                'question_content' => $course,
                'A' => $request->A[$key],
                'B' => $request->B[$key],
                'C' => $request->C[$key],
                'D' => $request->D[$key],
                'answer' => $request->answer[$key],
            ]);
        }
        return response()->json([
            'data' => '',
            'success' => true,
            'message' => 'Thêm câu hỏi thành công'
        ]);
    }
    public function getQuestionWeb($id){
        $edit = $this->getQuestion($id);
        // dd($edit);
        if($edit->original['success']){
            $data = $edit->original['data'];
            toastr()->success($edit->original['message']);
            return view('course.getQuestion',compact('data','id'));
        }else{
            toastr()->error($edit->original['message']);
            return redirect()->route('course.index');
        }
    }

    public function getQuestion($couserId)
    {
        $Question =  Question::where('course_data_id', $couserId)->get();
        if ($Question) {
            return response()->json([
                'data' => $Question,
                'success' => true,
                'message' => 'Lấy câu hỏi thành công'
            ]);
        } else {
            return response()->json([
                'data' => '',
                'success' => false,
                'message' => 'Không tìm thấy câu hỏi của buổi học'
            ]);
        }
    }


    public function storeTest(Request $request)
    {
        $questions = Question::where('course_data_id', $request->course_data_id)->get();
        $test_1 = Test::where('course_data_id', $request->course_data_id)->where('user_id', $request->user_id)->first();
        if ($test_1) {
            if ($test_1->status == 1) {
                return response()->json([
                    'data' => '',
                    'success' => false,
                    'message' => 'Bài kiểm tra chưa thực hiện xong'
                ]);
            }
        }
        $test = Test::create([
            'user_id' => $request->user_id,
            'status' => Test::UNFINISHED,
            'course_data_id' => $request->course_data_id,
            'score' => 0
        ]);
        foreach ($questions as $question) {
            TestUser::create([
                'test_id' => $test->id,
                'question_id' => $question->id,
                'answer_user_id' => $request->user_id,
                'answer' => null,
            ]);
        }
        $test->refresh();
        return response()->json([
            'data' => $test->load('testUsers1'),
            'success' => true,
            'message' => 'Tạo thành công'
        ]);
    }

    public function updateTest(Request $request)
    {
        $id = $request->id;
        $answer = $request->answer;
        $testUser = TestUser::find($id);
        $testUser->update([
            'answer' => $answer
        ]);
        return response()->json([
            'data' => $testUser,
            'success' => true,
            'message' => 'Tạo thành công'
        ]);
        
    }

    public function scoringTest(Request $request){
        $id = $request->id;
        $Test = Test::find($id);
        $a = 0;
        if($Test){
           $test_user =  TestUser::where('test_id', $id)->get();
           foreach($test_user as $t_user){
                
                $question = Question::find($t_user->question_id);
                if($t_user->answer == $question->answer){
                    $a = $a + 1;
                }
           }
           $Test->update([
                'status' => Test::COMPLETE,
                'score' => $a
           ]);
           return response()->json([
            'data' => $Test,
            'success' => true,
            'message' => 'Đã chấm điểm thành công !'
        ]);
        }else{
            return response()->json([
                'data' => '',
                'success' => false,
                'message' => 'Không tìm thấy !'
            ]);
        }

    }

    public function getTest($id){
 
        $test = Test::with('TestUser.Question')->find($id);
        if($test){
            return response()->json([
                'data' => $test,
                'success' => false,
                'message' => 'Lấy dữ liệu thành công!'
            ]);
        }else{
            return response()->json([
                'data' => '',
                'success' => false,
                'message' => 'Không tìm thấy!'
            ]);
        }

    }
    public function uploadFile($file)
    {
            $fileNameOriginal = $file->getClientOriginalName();
            $fileNameHas = Str::random(20) . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('public', $fileNameHas);
            $result = [
                'file_name' => $fileNameOriginal,
                'file_path' => Storage::url($filePath)
            ];
            return asset($result['file_path']);
    }

    public function testCourseData(Request $request){
        $test = Test::where('course_data_id', $request->course_data_id)->where('user_id', $request->user_id)
        ->orderBy('id','desc')
        ->get();
        if($test){
            return response()->json([
                'data' => $test,
                'success' => true,
                'message' => 'Lấy dữ liệu thành công!'
            ]);
        }else{
            return response()->json([
                'data' => '',
                'success' => false,
                'message' => 'Không tìm thấy!'
            ]);
        }
    }

    public function statistic(Request $request){
          
        if ($request->start) {
            $data_start = Carbon::createFromFormat('Y-m-d', $request->start)->startOfDay()->subDay();
            $data_end = Carbon::createFromFormat('Y-m-d', $request->end)->endOfDay();
        } else {
            $data_start = Carbon::today('Asia/Ho_Chi_Minh')->startOfDay();
            // ->subDay();
            $data_end = Carbon::today('Asia/Ho_Chi_Minh')->endOfDay();

        }
        if($request->start){
            $data_start_ = Carbon::createFromFormat('Y-m-d', $request->start)->startOfDay();
           
        }else{
            $data_start_ = Carbon::today()->startOfDay();
     
        }
        $data_start_str = $data_start->format('Y-m-d H:i:s');
        $data_end_str = $data_end->format('Y-m-d H:i:s');
        $orders = Order::whereBetween('created_at', [$data_start_str, $data_end_str])
            ->where('status', 2)
            ->with('OrderDetails')
            ->get();
            $revenueByDay = [];

            $orders->each(function($order) use (&$revenueByDay) {
                $date = Carbon::parse($order->created_at)->format('Y-m-d');
                $orderTotal = $order->total;
                if (isset($revenueByDay[$date])) {
                    $revenueByDay[$date] += $orderTotal;
                } else {
                    $revenueByDay[$date] = $orderTotal;
                }
                });
                $date = [];
                $total = [];
                foreach ($revenueByDay as $key => $value) {
                    $date[] = Carbon::parse($key)->format('d-m-Y');
                    $total[] = $value;
                }
                return view('statistic.index', compact('date', 'total', 'data_start_',
                'data_end'));    
    }
}