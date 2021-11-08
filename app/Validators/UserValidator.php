<?php


namespace App\Validators;


use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserValidator extends BaseValidator
{
    public function registerUser($requestInput)
    {
        $validator = Validator::make($requestInput, [
            'last_name' => 'required',
            'first_name' => 'required',
//            'name' => 'required',
            'phone_code' => 'required',
            'phone_number' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $this->throwException($validator);
        }
    }

    public function updateUser($requestInput)
    {
        $validator = Validator::make($requestInput, [
            'last_name' => 'required',
            'first_name' => 'required',
            'name' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $this->throwException($validator);
        }
    }
}