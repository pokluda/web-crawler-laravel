<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Config;

class CrawlerTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public static function rules(): array
    {
        return [
            'url' => [
                'bail',
                'required',
                'starts_with:http://,https://',
                'min:' . Config::get('crawler.task.min_url_length'),
                'max:' . Config::get('crawler.task.max_url_length'),
                'url',
                'active_url',
            ],
        ];
    }
}
