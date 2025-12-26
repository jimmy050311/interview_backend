<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' =>'required|bail|string',
            'title' => 'required|bail|string',
            'content' => 'required|bail|string',
            'status' => 'sometimes|bail|numeric|nullable|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [

            'image.required' => '請上傳或提供圖片路徑。',
            'image.string'   => '圖片格式必須是字串。',


            'title.required' => '請輸入標題。',
            'title.string'   => '標題格式不正確。',


            'content.required' => '內容正文不能為空。',
            'content.string'   => '內容格式不正確。',


            'status.numeric'  => '狀態必須是數字。',
            'status.in'       => '狀態值無效，僅限 0 或 1。',
        ];
    }
    protected function failedValidation(Validator $validator){
        // 取得錯誤資訊
        $responseData = [
            'success' => 400,
            'message' => $validator->errors()->first()
        ];
        // 產生 JSON 格式的 response，(422 是 Laravel 預設的錯誤 http status，可自行更換) 
        $response = response()->json($responseData, 400);
        // 丟出 exception
        throw new HttpResponseException($response);
    }
}
