<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TodoCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* By default it returns false, change it to
         * something likethis if u are checking
         * authentication
         */
//        return Auth::check();
        return true;

        /* Or something more granular like this:
         * return auth()->user()->can('udpate-profile')
         * if you are using the Laratrust package.
         */
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
