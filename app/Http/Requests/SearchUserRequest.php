<?php 

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchUserRequest extends FormRequest
{
    public function authorize() { return true; }

    public function rules()
    {
        return [
            'role'  => 'nullable|in:doctor,patient',
            'name'  => 'nullable|string|max:100',
            'email' => 'nullable|email',
        ];
    }
}
