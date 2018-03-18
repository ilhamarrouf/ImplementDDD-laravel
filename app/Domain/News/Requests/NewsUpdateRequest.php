<?php

namespace App\Domain\News\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'required|numeric|exists:news,id',
            'title' => 'required|string|min:5|max:300',
            'body' => 'required|string|min:100',
            'status' => 'required|in:publish,draft,delete',
            'topics' => 'array',
            'topics.*' => 'numeric|exists:topics,id',
        ];
    }
}
