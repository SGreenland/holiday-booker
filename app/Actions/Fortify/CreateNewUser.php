<?php

namespace App\Actions\Fortify;

use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'team' => ['required', 'string', Rule::in(['HR', 'Accounts', 'Sales', 'Development'])],
            'management' => ['boolean', 'nullable'],
            'password' => $this->passwordRules(),
        ])->validate();


        $team = Team::firstOrCreate([
            'name' => $input['team']
        ]);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'team_id' => $team->id,
            'password' => Hash::make($input['password']),
        ]);

        $role = Role::firstOrCreate([
            'name' => 'admin'
        ]);


        if($input['management']) $user->roles()->attach($role->id);

        return $user;

    }
}
