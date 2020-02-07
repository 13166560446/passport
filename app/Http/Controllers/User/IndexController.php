<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
class IndexController extends Controller
{
        public function index(Request $request){
            $name=$request->input('name');
            $email=$request->input('email');
            $tel=$request->input('tel');
            $pass1=$request->input('pass1');
            $pass2=$request->input('pass2');

            if($pass1!=$pass2){
                die('输入的两次密码不一致');die;
            }

            $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
            if(!preg_match($pattern,$email)){
                echo '邮箱格式错误！';die;
            }

            $data=[
                'name'=>$name,
                'email'=>$email,
                'tel'=>$tel,
                'pass'=>$pass1
            ];
//            dd($data);
            $res=UserModel::insertGetId($data);
            if($res){
                echo '注册成功';
            }else{
                echo '注册失败';die;
            }

        }
        public function login(Request $request){
            $data=$request->all();
            $res=UserModel::where($data)->first();
            if($res){
                echo "登录成功";
                $token=Str::random(32);
                $key=$res->id.":";
                Redis::set($key,$token,40000);
                $response=[
                    'errno'=>0,
                    'msg'=>'ok',
                    'data'=>[
                        'token'=>$token
                    ]
                ];
//                return $response;
            }else{
                echo "登录失败";
                $response=[
                    'errno'=>40002,
                    'msg'=>'密码不正确'
                ];
            }
            return $response;
        }
        public function userlist(){
//           print_r($_SERVER);die;
            $name=$_SERVER['HTTP_NAME'];
            $token =$_SERVER['HTTP_TOKEN'];
//          echo  $name=$_SERVER['NAME'];
            $res=UserModel::where(['name'=>$name])->first();
            $key=$res->id.":";
//            echo $key;die;
            $info=Redis::get($key);
//            echo $info;die;
            if($info==$token){
                echo "<pre>";print_r($res);echo"</pre>";
            }else{
                echo "请输入正确的token";
            }
            
        }






        //签名
        public function md5test(){
        echo "<pre>";print_r($_GET);echo "</pre>";

        //验签
        $data=$_GET['data'];
        $signature=$_GET['signature'];

        //接收的key与发送端的一致
        $key='1905';

        $sign=md5($data.$key);
        echo "接收端计算的签名：".$sign;echo '</br>';
        if($sign==$signature){
            echo "验签通过";
        }else{
            echo '验签失败';
        }
    }
}
