<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ArticleDatatableRequest extends FormRequest
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
            'title'  => 'sometimes|bail|string|nullable',
            'page'   => 'required|bail|numeric|min:1',
            'length' => 'required|bail|numeric',
            'status' => 'sometimes|bail|numeric|nullable|in:0,1',
            'order'   => 'required|bail|string',
            'sort'  => ['required', 'bail', 'string', Rule::in(['asc', 'desc', 'ASC', 'DESC'])],
        ];
    }

    public function messages(): array
    {
        return [
            'title.string'    => '標題格式必須是字串。',

            'page.required'   => '請指定頁碼。',
            'page.numeric'    => '頁碼必須是數字。',
            'page.min'        => '頁碼最小從 1 開始。',

            'length.required' => '請指定每頁顯示筆數。',
            'length.numeric'  => '顯示筆數必須是數字。',

            'status.numeric'  => '狀態格式錯誤。',
            'status.in'       => '狀態值無效，僅限 0 (停用) 或 1 (啟用)。',

            'order.required'   => '請指定排序欄位。',
            'order.string'     => '排序欄位名稱格式不正確。',

            'sort.required'  => '請指定排序方式。',
            'sort.string'    => '排序方式格式不正確。',
            'sort.in'        => '排序方式只能是 asc (升冪) 或 desc (降冪)。',
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
