<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'fname' => ['required', 'string', 'max:255'],
            'lname' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $input['name'] = $input['fname'].' '.$input['lname'];

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
            'password' => Hash::make($input['password']),
        ]);

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

        return $user;
    }
}
