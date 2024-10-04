<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Validator;

class UserApiValidation{

    public static function returnValidation($validate)
    {
        if ($validate->fails()) {
            return ['status' => 'error', 'message' => $validate->errors()->first()];
        } else {
            return ['status' => 'success'];
        }
    }

    public static function loginValidation($request)
    {
        $validate = Validator::make($request->all(),[

            'email' => 'required|email|regex:/(.*)@*\.*/i',
            'password' => 'required|min:6',
        ]);
        return self::returnValidation($validate);
    }

    public static function taskStoreValidation($request)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|integer|between:0,2',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ]);
        return self::returnValidation($validate);
    }

    public static function taskUpdateValidation($request)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'string|max:255',
            'description' => 'string',
            'status' => 'in:pending,in_progress,completed',
            'due_date' => 'nullable|date',
            'priority' => 'integer|between:0,2',
            'attachment' => 'nullable|file|mimes:jpeg,png,jpg|max:2048'
        ]);
        return self::returnValidation($validate);
    }

    public static function assignTaskValidation($request)
    {
        $validate = Validator::make($request->all(),[

            'task_id' => 'required|exists:tasks,id',
            'assign_to_id' => 'required|exists:users,id',
        ]);
        return self::returnValidation($validate);
    }



}