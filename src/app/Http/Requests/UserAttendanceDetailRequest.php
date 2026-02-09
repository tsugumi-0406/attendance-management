<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAttendanceDetailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attendance' => ['required', 'before:leaving'],
            'leaving' => ['nullable', 'after:attendance'],
            'break_requests.*.start' => ['nullable', 'after:attendance', 'before:leaving'],
            'break_requests.*.stop' => ['nullable', 'after:attendance', 'before:leaving'],
            'remarks' => ['required', 'string'],
        ];
    }

    public function messages()
    {
        return [
            'attendance.required' => '出勤時間を入力してください',
            'attendance.before' => '出勤時間が不適切な値です',
            'leaving.after' => '退勤時間が不適切な値です',
            'break_requests.*.start.after' => '休憩時間が不適切な値です',
            'break_requests.*.start.before' => '休憩時間が不適切な値です',
            'break_requests.*.stop.before' => '休憩時間もしくは退勤時間が不適切な値です',
            'break_requests.*.stop.after' => '休憩時間が不適切な値です',
            'remarks.required' => '備考を記入してください'
        ];
    }
}
