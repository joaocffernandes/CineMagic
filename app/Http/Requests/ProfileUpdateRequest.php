<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user()->id,
            'photo_filename' => 'sometimes|image|mimes:jpeg,png,jpg|max:4096',
        ];

        if ($this->user()->type == 'C') {
            $rules = array_merge($rules, [
                'nif' => 'required|digits:9',
                'payment_type' => 'required|string|in:VISA,PAYPAL,MBWAY',
            ]);
        }

        if ($this->input('payment_type') == 'MBWAY'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|regex:/^9\d{8}$/',
            ]);
        }

        if ($this->input('payment_type') == 'PAYPAL'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|string|email|max:255',
            ]);
        }

        if ($this->input('payment_type') == 'VISA'){
            $rules = array_merge($rules, [
                'payment_ref' => 'required|regex:/^4[0-9]{15}$/',
            ]);
        }

        return $rules;
    }
}
