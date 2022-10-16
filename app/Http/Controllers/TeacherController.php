<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
  public function registro(Request $request) {
    $validated = $request->validate([
      'fname' => 'required',
      'lname' => 'required',
      'email' => [
          'required',
          'string',
          'email',
          'max:255',
          Rule::unique(User::class),
      ],
      'tel' => ['required', 'regex:/[0-9()#&+*-=.]+/i'],
      'pais' => 'required',
      'city' => 'required',
      'experiencia' => 'required'
    ]);

    $input = $request->all();

    $input['name'] = $input['fname'].' '.$input['lname'];
    $input['personal_email'] = $input['email'];

    $input['email'] = mb_strtolower( substr($input['fname'],0,1).$input['lname'].time() ).'@wiseabc.net';
    $input['password'] = $input['email'];

    $metasrc = $input;
    unset($metasrc['_token'],$metasrc['name'],$metasrc['email'],$metasrc['password'],$metasrc['password_confirmation']);

    foreach ( $metasrc AS $mk=>$mv)
    {
        if (is_array($mv) || is_object($mv) )
        {
            $mv = json_encode($mv, JSON_UNESCAPED_UNICODE);
            $metasrc[$mk] = $mv;
        }
    }

    $user = User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => Hash::make($input['password'])
    ]);
    $user->user_type = 3;
    $user->status = 0;
    $user->save();

    foreach ( $metasrc AS $mk=>$mv)
    {
        if (is_array($mv) || is_object($mv) )
        {
            $mv = json_encode($mv, JSON_UNESCAPED_UNICODE);
        }
        if ( strlen($mv)>250 )
        {
            $mv_arr = str_split($mv, 250);
            foreach($mv_arr AS $_mv) {
                echo "{$mk} : {$_mv} \n";
                $user->usermetas()->create(['metakey'=>$mk,'metaval'=>$_mv]);
            }
        }
        else
        {
            echo "{$mk} : {$mv} \n";
            $user->usermetas()->create(['metakey'=>$mk, 'metaval'=>$mv]);
        }
    }

    return redirect()->route('gracias-profesor');
  }
}