<?php

namespace AwemaPL\Permission\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRoleToUser extends FormRequest
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
            'email' => 'required|string|exists:users,email',
            'role_id' => 'required|numeric|exists:roles,id',
        ];
    }
}
