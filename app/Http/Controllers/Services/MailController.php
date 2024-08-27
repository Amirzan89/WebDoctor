<?php
namespace App\Http\Controllers\Services;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Jobs\SendResetPassword;
use App\Jobs\SendVerifyEmail;
use App\Models\User;
use App\Models\Verify;
use Carbon\Carbon;
class MailController extends Controller
{
    // private static $conditionOTP = [ 1, 15, 30, 60];
    // private static $conditionOTP = [ 5, 15, 30, 60];
    private static $conditionOTP = [ 60, 15, 30, 60];
    private static $enCol = ['email' => SendVerifyEmail::class, 'password' => SendResetPassword::class];
    public static function getConditionOTP(){
        return self::$conditionOTP;
    }
    private static function getEmail($data, $cond){
        if (isset(self::$enCol[$cond])) return new self::$enCol[$cond]($data);
    }
    private function deleteOldEmail(){
        Verify::where('updated_at', '<', Carbon::now()->subDays(3))->delete();
    }
    public function createVerifyEmail(Request $request, Verify $verify){
        $validator = Validator::make($request->only('email'), [
            'email'=>'required|email',
        ],[
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $email = $request->input('email');
        //check email on table user
        $user = User::select('id_user', 'name')->whereRaw("BINARY email = ?",[$request->input('email')])->first();
        if ($user === null) {
            if($request->path() === 'verify/create/email' && $request->isMethod("get")){
                return response()->json(['status'=>'error','message'=>'email invalid'], 400);
            }else{
                return response()->json(['status'=>'error','message'=>'email invalid','code'=>400]);
            }
        }
        //check if user have create verify email
        $verifyDb = Verify::select('send','updated_at')->whereRaw("BINARY email = ?",[$request->input('email')])->where('description', 'email')->first();
        if ($verifyDb === null) {
            self::deleteOldEmail();
            //for register
            $verificationCode = mt_rand(100000, 999999);
            $linkPath = Str::random(50);
            $verificationLink = URL::to("/verify/email/$linkPath");
            $verify->email = $email;
            $verify->code = $verificationCode;
            $verify->link = $linkPath;
            $verify->description = 'email';
            $verify->send = 1;
            $verify->id_user = $user['id_user'];
            if(!$verify->save()){
                return response()->json(['status'=>'error','message'=>'fail create verify email'], 400);
            }
            dispatch_sync(self::getEmail(['name'=>$user->name,'email'=>$email,'code'=>$verificationCode,'link'=>$verificationLink], 'email'));
            return response()->json(['status'=>'Success','message'=>'Akun Berhasil Dibuat Silahkan verifikasi email','code'=>200,'data'=>['waktu'=>Carbon::now()->addMinutes(self::$conditionOTP[0])]]);
        }
        //checking if user have create verify email
        $expTime = self::$conditionOTP[($verifyDb['send'] - 1)];
        if (Carbon::parse($verifyDb->updated_at)->diffInMinutes(Carbon::now()) < $expTime) {
            return response()->json(['status'=>'success','message'=>'Kami sudah mengirim email verifikasi ','data'=>['waktu' => Carbon::now()->addMinutes(self::$conditionOTP[min($verifyDb['send'], count(self::$conditionOTP)) - 1])]]);
        }
        //if after desired time then update code
        $verificationCode = mt_rand(100000, 999999);
        $linkPath = Str::random(50);
        $verificationLink = URL::to("/verify/email/$linkPath");
        if(is_null(DB::table('verifikasi')->whereRaw("BINARY email = ? AND description = 'email'",[$email])->update(['code'=>$verificationCode,'link'=>$linkPath, 'send'=> min($verifyDb['send'] + 1, count(self::$conditionOTP))]))){
            return response()->json(['status'=>'error','message'=>'fail create verify email'], 400);
        }
        //resend email
        dispatch_sync(self::getEmail(['name'=>$user->name,'email'=>$email,'code'=>$verificationCode,'link'=>$verificationLink], 'email'));
        return response()->json(['status'=>'success','message'=>'email benar kami kirim ulang kode ke anda silahkan cek email','data'=>['waktu'=>Carbon::now()->addMinutes(self::$conditionOTP[min($verifyDb['send'] + 1, count(self::$conditionOTP)) - 1])]]);
    }
    //send email forgot password for all
    public function createForgotPassword(Request $request, Verify $verify){
        $validator = Validator::make($request->only('email'), [
            'email'=>'required|email',
        ],[
            'email.required'=>'Email wajib di isi',
            'email.email'=>'Email yang anda masukkan invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $email = $request->input('email');
        //check email on table user
        $user = User::select('id_user', 'name', 'role')->whereRaw("BINARY email = ?",[$request->input('email')])->first();
        if (is_null($user)) {
            return response()->json(['status'=>'error','message'=>'Email tidak terdaftar !'], 400);
        }
        //checking process if user have create verify email or not
        $verifyDb = Verify::select('send','updated_at')->whereRaw("BINARY email = ?",[$request->input('email')])->where('description', 'password')->first();
        if (is_null($verifyDb)) {
            self::deleteOldEmail();
            //if user haven't create email forgot password
            $verificationCode = mt_rand(100000, 999999);
            $linkPath = Str::random(50);
            $verificationLink = URL::to("/verify/password/$linkPath");
            $verify->email = $email;
            $verify->code = $verificationCode;
            $verify->link = $linkPath;
            $verify->description = 'password';
            $verify->send = 1;
            $verify->id_user = $user['id_user'];
            if($verify->save()){
                dispatch_sync(self::getEmail(['name'=>$user->name,'email'=>$email,'code'=>$verificationCode,'link'=>$verificationLink], 'password'));
                return response()->json(['status'=>'success','message'=>'kami akan kirim kode ke anda silahkan cek email','data'=>['waktu'=>Carbon::now()->addMinutes(self::$conditionOTP[0])]]);
            }else{
                return response()->json(['status'=>'error','message'=>'fail create forgot password'],500);
            }
        }
        //checking if user have create verify email
        $expTime = self::$conditionOTP[($verifyDb['send'] - 1)];
        if (Carbon::parse($verifyDb->updated_at)->diffInMinutes(Carbon::now()) < $expTime){
            return response()->json(['status'=>'success','message'=>'Kami sudah mengirim Otp silahkan cek mail anda ','data'=>['waktu' => Carbon::now()->addMinutes(self::$conditionOTP[min($verifyDb['send'], count(self::$conditionOTP)) - 1])]]); 
        }
        //if after desired time then update code
        $verificationCode = mt_rand(100000, 999999);
        $linkPath = Str::random(50);
        $verificationLink = URL::to("/verify/password/$linkPath");
        if(is_null(DB::table('verifikasi')->whereRaw("BINARY email = ? AND description = 'password'",[$email])->update(['code'=>$verificationCode, 'link'=>$linkPath, 'updated_at' => Carbon::now(), 'send' => min($verifyDb['send'] + 1, count(self::$conditionOTP))]))){
            return response()->json(['status'=>'error','message'=>'fail create forgot password'], 500);
        }
        dispatch_sync(self::getEmail(['name'=>$user->name,'email'=>$email,'code'=>$verificationCode,'link'=>$verificationLink], 'password'));
        return response()->json(['status'=>'success','message'=>'email benar kami kirim ulang kode ke anda silahkan cek email','data'=>['waktu'=>Carbon::now()->addMinutes(self::$conditionOTP[min($verifyDb['send'] + 1, count(self::$conditionOTP)) - 1])]]);
    }
    public function ResendOTP(Request $request, User $user){
        $validator = Validator::make($request->only('email', 'code'), [
            'email'=>'required|email',
            'code' =>'nullable'
        ],[
            'email.required'=>'Email harus di isi',
            'email.email'=>'Email yang anda masukkan invalid',
        ]);
        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->toArray() as $field => $errorMessages) {
                $errors[$field] = $errorMessages[0];
                break;
            }
            return response()->json(['status' => 'error', 'message' => implode(', ', $errors)], 400);
        }
        $laPath = last(explode('/', $request->path()));
        if (in_array($laPath, array_keys(self::$enCol))) {
            return response()->json(['status' => 'error', 'message' => 'Link Invalid'], 400);
        }
        $verifyDb = Verify::select('send', 'updated_at')->whereRaw("BINARY email = ?",[$request->input('email')])->where('description', $laPath)->first();
        if (is_null($verifyDb)) {
            return response()->json(['status'=>'error','message'=>'Data verifikasi tidak ada'], 400);
        }
        //checking if user have create verify email
        $expTime = self::$conditionOTP[($verifyDb['send'] - 1)];
        if (Carbon::parse($verifyDb->updated_at)->diffInMinutes(Carbon::now()) < $expTime) {
            return response()->json(['status'=>'success','message'=>'Kami sudah mengirim email ','data'=>['waktu' => Carbon::now()->addMinutes(self::$conditionOTP[min($verifyDb['send'], count(self::$conditionOTP)) - 1])]]);
        }
        $user = User::select('id_user', 'name', 'role')->whereRaw("BINARY email = ?",[$request->input('email')])->first();
        if (is_null($user)) {
            return response()->json(['status'=>'error','message'=>'Email tidak terdaftar !'], 400);
        }
        //if after desired time then update code
        $verificationCode = mt_rand(100000, 999999);
        $linkPath = Str::random(50);
        $verificationLink = URL::to("/verify/$laPath/$linkPath");
        if(is_null(DB::table('verifikasi')->whereRaw("BINARY email = ? AND description = $laPath",[$request->input('email')])->update(['code'=>$verificationCode,'link'=>$linkPath, 'send'=> min($verifyDb['send'] + 1, count(self::$conditionOTP))]))){
            return response()->json(['status'=>'error','message'=>"fail create email $laPath"], 400);
        }
        $data = ['name'=>$user->name,'email'=>$request->input('email'),'code'=>$verificationCode,'link'=>$verificationLink];
        //resend email
        dispatch_sync(self::getEmail($data, $laPath));
        return response()->json(['status'=>'success','message'=>'email benar kami kirim ulang kode ke anda silahkan cek email','data'=>['waktu'=>Carbon::now()->addMinutes(self::$conditionOTP[min($verifyDb['send'] + 1, count(self::$conditionOTP)) - 1])]]);
    }
}
?>