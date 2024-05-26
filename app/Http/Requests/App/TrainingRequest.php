<?php

namespace App\Http\Requests\App;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;

class TrainingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() instanceof Student || auth()->user() instanceof Admin;
    }
}
