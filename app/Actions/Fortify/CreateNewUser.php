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
        $valid = Validator::make($input, [
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
            'tel' => ['required', 'regex:/[0-9()#&+*-=.]+/i' ],
            'edad' => ['required', 'integer'],
            'nivel' => ['required', 'integer'],
        ])->validate();

        $user = User::create([
            'name' => ucwords($input['fname'].' '.$input['lname']),
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        $temp = array(1=>$input['horarios']);

        $user->saveMetas($input);
        $user->saveHorarios($temp);

        return $user;
    }
}
