<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Models\User;
use Datetime;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    public function auth(Request $request) {
        $user = DB::table('users')->where('id', $request->id)->get();
        if (count($user) === 0){
            return view('login', ['login_error' => '1']);
        }

        if (Hash::check($request->password, $user[0]->password)) {
            session(['id' => $user[0]->id]);
            session(['name'  => $user[0]->name]);
            session(['department'  => $user[0]->department]);
            session(['point'  => $user[0]->point]);
            session(['attendance_flg'  => $user[0]->attendance_flg]);
            session(['admin_flg'  => $user[0]->admin_flg]);
            session(['user_login' => 0]);

            return redirect(url('/'));
        }else{
            return view('login', ['login_error' => '1']);
        }
    }

    public function auth_admin(Request $request) {
        $user = DB::table('users')->where([
            ['id', $request->id],
            ['admin_flg', 1]
        ])->get();
        if (count($user) === 0){
            return view('login_admin', ['login_error' => '1']);
        }

        if (Hash::check($request->password, $user[0]->password)) {
            session(['id' => $user[0]->id]);
            session(['name'  => $user[0]->name]);
            session(['department'  => $user[0]->department]);
            session(['point'  => $user[0]->point]);
            session(['attendance_flg'  => $user[0]->attendance_flg]);
            session(['admin_flg'  => $user[0]->admin_flg]);
            session(['user_login' => 1]);

            return redirect(url('/user_admin'));
        } else {
            return view('login_admin', ['login_error' => '1']);
        }
    }

    public function logout() {
        session()->flush();
        return view('logout');
    }

    public function session_out() {
        session()->flush();
        return view('session_out');
     }

    public function index() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        return view('index', compact('info'));
    }

    public function attendance() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $date = now()->format('Y-m-d');
        $time = now()->format('H:i:s');
        $id = session('id');


        if (DB::table('times')->where([
            ['user_id', $id],
            ['attendance_flg', 1],
            ['date', $date],
            ['earn_point', '<>', 0],
        ])->exists()) {
            $point = 0;
        } else {
            $point = 10;
        }

        DB::table('users')->where('id', $id)
                          ->increment('point', $point, ['attendance_flg' => 1]);
        DB::table('times')->insert([
            'user_id' => $id,
            'attendance_flg' => 1,
            'date' => $date,
            'time' => $time,
            'earn_point' => $point,
        ]);

        session()->increment('point', $point);
        session(['attendance_flg'  => 1]);
        session()->regenerateToken();

        return view('/attendance',compact('info'), ['point' => $point]);
    }

    public function leaving() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        $date = now()->format('Y-m-d');
        $time = now()->format('H:i:s');
        $id = session('id');

        DB::table('users')->where('id', $id)
                          ->update(['attendance_flg' => 0]);
        DB::table('times')->insert([
            'user_id' => $id,
            'attendance_flg' => 0,
            'date' => $date,
            'time' => $time,
            'earn_point' => 0
        ]);

        session(['attendance_flg'  => 0]);
        session()->regenerateToken();

        return view('/leaving', ['info' => $info]);
    }

    public function user_info() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
            'user_login' => session('user_login'),
        ];

        return view('user_info', compact('info'));
    }

    public function pass_change() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'admin_flg' => session('admin_flg'),
            'user_login' => session('user_login'),
        ];
        return view('pass_change', compact('info'));
    }

    public function pass_change_check(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'admin_flg' => session('admin_flg')
        ];
        if (empty($request->change_pass) || empty($request->change_pass_confirm) || $request->change_pass != $request->change_pass_confirm) {
            return view('pass_change', ['login_error' => '1','info' => $info]);
        }
        $user = DB::table('users')->where('id', $info['id'])->get();
        if (Hash::check($request->current_pass, $user[0]->password)) {
            $change_pass_hash = password_hash($request->change_pass, PASSWORD_DEFAULT);
            DB::table('users')->where('id', $info['id'])
                              ->update(['password' => $change_pass_hash]);

            return redirect(url('/pass_change_complete'));
        } else {
            return view('pass_change', ['login_error' => '1','info' => $info]);
        }
    }

    public function pass_change_complete() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'admin_flg' => session('admin_flg')
        ];
        return view('pass_change_complete', compact('info'));
    }

    public function user_admin() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $result = DB::table('users')->simplePaginate(8);

        session()->forget('add_user_input');
        session()->forget('edit_user_input');
        session()->forget('edit_detail_input');

        return view('user_admin', compact('result', 'info'));
    }

    public function user_admin_search(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        $department = $request->department;
        if ($department == "") {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $result = DB::table('users')->where('department', $department)->simplePaginate(8);
        $result->appends(['department' => $department]);

        return view('user_admin', compact('result', 'info'));
    }

    public function add_user() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        $input = [
            'id' => "",
            'name' => "",
            'department' => "",
        ];
        if (session()->has('add_user_input')) {
            $input = session()->get('add_user_input');
            $input['pass'] = "";
            $input['pass_confirm'] = "";
        }

        return view('add_user', compact('info', 'input'));
    }

    private $add_validator = [
        "id" => "required|unique:users,id|max:255",
        "name" => "required|max:255",
        "pass" => "required|max:255",
        "pass_confirm" => "required|same:pass"
    ];
    private $add_error_message = [
        "id.required" => "ID入力は必須です",
        "id.unique" => "そのIDは既に存在しています",
        "id.max" => "IDが長すぎます",
        "name.required" => "名前入力は必須です",
        "name.max" => "名前が長すぎます",
        "pass.required" => "パスワード入力は必須です",
        "pass.max" => "パスワードが長すぎます",
        "pass_confirm.required" => "パスワード入力は必須です",
        "pass_confirm.same" => "パスワードが間違っています",
    ];
    public function add_user_confirm(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $inputs = $request->all();
        $validator = Validator::make($inputs, $this->add_validator, $this->add_error_message);
        if ($validator->fails()) {
            return redirect(url('/add_user'))->withInput()
                                             ->withErrors($validator);
        }
        $request->session()->put('add_user_input', $inputs);
        return view('add_user_confirm', compact('inputs', 'info'));
    }

    public function add_user_complete(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $input = $request->session()->get('add_user_input');
        if (!$input) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $id = $input['id'];
        $department = $input['department'];
        $name = $input['name'];
        $pass = password_hash($input['pass'], PASSWORD_DEFAULT);
        DB::table('users')->insert(['id' => $id, 'name' => $name, 'department' => $department, 'password' => $pass]);

        $request->session()->forget('add_user_input');
        session()->regenerateToken();

        return view('add_user_complete', compact('info'));
    }

    public function edit_user(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        if (!isset($inputs)) {
            $inputs = $request->all();
        }
        if (!$inputs) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $input = [
            'id' => "",
            'name' => "",
            'department' => "",
        ];
        if (session()->has('edit_user_input')) {
            $input = session()->get('edit_user_input');
        }
        $id = $inputs['id'];
        $value = DB::table('users')->where('id', $id)->first();
        return view('edit_user', compact('value', 'info', 'input'));
    }

    private $edit_validator = [
        "name" => "required|max:255"
    ];
    private $edit_error_message = [
        "name.required" => "名前入力は必須です",
        "name.max" => "名前が長すぎます"
    ];
    public function edit_user_confirm(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $inputs = $request->all();
        if (!$inputs) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $validator = Validator::make($inputs, $this->edit_validator, $this->edit_error_message);
        if ($validator->fails()) {
            return redirect()->action('App\Http\Controllers\UserController@edit_user', ['id' => $inputs['id']])
                             ->withInput()
                             ->withErrors($validator);
        }
        $request->session()->put('edit_user_input', $inputs);
        return view('edit_user_confirm', compact('inputs', 'info'));
    }

    public function edit_user_complete(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $input = $request->session()->get('edit_user_input');
        if (!$input) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $id = $input['id'];
        $department = $input['department'];
        $name = $input['name'];
        DB::table('users')->where('id', $id)->update(['name' => $name, 'department' => $department]);

        $request->session()->forget('edit_user_input');
        session()->regenerateToken();

        return view('edit_user_complete', compact('info'));
    }

    public function delete_user(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $inputs = $request->all();
        if (!$inputs) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $id = $inputs['id'];
        DB::table('users')->where('id', $id)->delete();
        session()->regenerateToken();
        return view('delete_user', compact('info'));
    }

    public function detail(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $user_id = $request->id;
        $result = DB::table('times')->where('user_id', $user_id)->simplePaginate(8);

        $result->appends(['id' => $request->id]);

        return view('detail', compact('result', 'info'),['user_id' => $user_id]);
    }

    public function edit_detail(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        if (!isset($inputs)) {
            $inputs = $request->all();
        }
        if (!$inputs) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $id = $inputs['id'];
        $value = DB::table('times')->where('id', $id)->first();
        return view('edit_detail', compact('value', 'info'));
    }

    private $edit_detail_validator = [
        "date" => "required|date_format:Y-m-d",
        "time" => "required|date_format:H:i:s"
    ];
    private $edit_detail_error_message = [
        "date.required" => "日付入力は必須です",
        "date.date_format" => "日付の形式が間違っています",
        "time.required" => "時間入力は必須です",
        "time.date_format" => "時間の形式が間違っています",
    ];
    public function edit_detail_confirm(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $inputs = $request->all();
        if (!$inputs) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $validator = Validator::make($inputs, $this->edit_detail_validator, $this->edit_detail_error_message);
        if ($validator->fails()) {
            return redirect()->action('App\Http\Controllers\UserController@edit_detail', ['id' => $inputs['id']])
                             ->withInput()
                             ->withErrors($validator);
        }
        $request->session()->put('edit_detail_input', $inputs);
        return view('edit_detail_confirm', compact('inputs', 'info'));
    }

    public function edit_detail_complete(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $input = $request->session()->get('edit_detail_input');
        if (!$input) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $id = $input['id'];
        $attendance_flg = $input['attendance_flg'];
        $date = $input['date'];
        $time = $input['time'];
        DB::table('times')->where('id', $id)->update(['attendance_flg' => $attendance_flg, 'date' => $date, 'time' => $time]);

        $request->session()->forget('edit_detail_input');
        session()->regenerateToken();

        return view('edit_detail_complete', compact('info'));
    }

    public function delete_detail(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $inputs = $request->all();
        if (!$inputs) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $id = $inputs['id'];
        DB::table('times')->where('id', $id)->delete();
        session()->regenerateToken();
        return view('delete_detail', compact('info'));
    }

    public function csv(Request $request) {
        /* 出来なかったやつ
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $user_id = $request->user_id;

        $data = DB::table('times')->where('user_id', $user_id)
                                  ->select([
                                    'id',
                                    'attendance_flg',
                                    'date',
                                    'time',
                                  ])
                                  ->get()->map(function($row){
                                    return [
                                        'id' => $row->id,
                                        'attendance_flg' =>$row->attendance_flg,
                                        'date' => $row->date,
                                        'time' => $row->time
                                    ];
                                  })->toArray();
        $head = ['ID', '出勤/退勤', '日付', '時間'];
        $f = fopen('attendance.csv', 'w');
        if ($f) {
            mb_convert_variables('SJIS', 'UTF-8', $head);
            fputcsv($f, $head);
            foreach ($data as $time) {
                mb_convert_variables('SJIS', 'UTF-8', $time);
                fputcsv($f, $time);
            }
        }
        fclose($f);
        header("Content-Type: application/octet-stream");
        header('Content-Length: '.filesize('attendance.csv'));
        header('Content-Disposition: attachment; filename=attendance.csv');
        readfile('attendance.csv');

        return view('detail', compact('info'), ['id' => $user_id]);
        */
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 1) {
            return redirect()->action('App\Http\Controllers\UserController@index');
        }
        $user_id = $request->user_id;
        if ($user_id == "") {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }

        $datetime = new Datetime();
        $datetime = $datetime->format('YmdHis');
        $filename = "attendance_".$user_id."_".$datetime.".csv";

        return response()->streamDownload(
            function () use ($user_id) {
                $stream = fopen('php://output', 'w');
                stream_filter_prepend($stream,'convert.iconv.utf-8/cp932//TRANSLIT');

                $head = ['ID', '出勤/退勤', '日付', '時間'];
                fputcsv($stream, $head);

                $data = DB::table('times')->where('user_id', $user_id)
                                  ->select([
                                    'id',
                                    'attendance_flg',
                                    'date',
                                    'time',
                                  ])
                                  ->get();
                foreach ($data as $time) {
                    fputcsv($stream,[
                        $time->id,
                        $time->attendance_flg,
                        $time->date,
                        $time->time
                    ]);
                }
                fclose($stream);
            },
            $filename,
            [
                'Content-Type' => 'application/octet-stream'
            ]
        );
    }

    public function point() {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        $products = DB::table('products')->get();

        return view('point', compact('products', 'info'));
    }

    public function product(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        $product_id = $request->product_id;
        if ($product_id == "") {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }

        $product = DB::table('products')->where('id', $product_id)->first();
        $balance = (int)$info['point'] - (int)$product->required_point;

        return view('product', compact('product', 'info'), ['product_id' => $product_id, 'balance' => $balance]);
    }

    public function purchase(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];

        $product_id = $request->product_id;
        if ($product_id == "") {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }

        $product = DB::table('products')->where('id', $product_id)->first();
        $balance = (int)$info['point'] - (int)$product->required_point;

        return view('purchase', compact('product', 'info'), ['product_id' => $product_id, 'balance' => $balance]);
    }

    public function purchase_complete(Request $request) {
        if(session()->missing('id')) {
            return redirect()->action('App\Http\Controllers\UserController@session_out');
        }
        $user_login = session('user_login');
        if ($user_login != 0) {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }
        $info = [
            'id' => session('id'),
            'name' => session('name'),
            'department' => session('department'),
            'point' => session('point'),
            'attendance_flg'  => session('attendance_flg'),
            'admin_flg' => session('admin_flg'),
        ];
        $user_id = $info['id'];
        $product_id = $request->product_id;
        if ($product_id == "") {
            return redirect()->action('App\Http\Controllers\UserController@user_admin');
        }

        $product = DB::table('products')->where('id', $product_id)->first();
        $balance = (int)$info['point'] - (int)$product->required_point;

        DB::table('users')->where('id', $user_id)->update(['point' => $balance]);
        DB::table('orders')->insert(['user_id' => $user_id, 'product_id' => $product_id]);
        session(['point'  => $balance]);
        session()->regenerateToken();

        return view('purchase_complete', compact('info'));
    }
}

