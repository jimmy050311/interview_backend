<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArticleDatatableRequest;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Services\Article\ArticleService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $service;

    public function __construct(ArticleService $service) {
        $this->service = $service;
    }

    public function datatable(ArticleDatatableRequest $request) {
        try {

            $data = $this->service->search($request->all());
            $count = $this->service->searchCount($request->all());

            $response = [
                'success' => 200,
                'message' => '查找成功',
                'data' => ArticleResource::collection($data),
                'count' => $count
            ];

        }catch(Exception $e) {
            $response = [
                'success' => 400,
                'message' => $e->getMessage()
            ];
        }

        return response()->json($response, $response['success']);
    }

    public function create(ArticleRequest $request) {
        try {

            $data = $this->service->create($request->all());

            $response = [
                'success' => 200,
                'message' => '成功',
                'data' => new ArticleResource($data),
            ];

        }catch(Exception $e) {
            $response = [
                'success' => 400,
                'message' => $e->getMessage()
            ];
        }

        return response()->json($response, $response['success']);
    }

    public function update($id, ArticleRequest $request): JsonResponse {
        try {

            $data = $this->service->update($id, $request->all());

            $response = [
                'success' => 200,
                'message' => '成功',
                'data' => new ArticleResource($data),
            ];

        }catch(Exception $e) {
            $response = [
                'success' => 400,
                'message' => $e->getMessage(),
            ];
        }

        return response()->json($response, $response['success']);
    }

    public function findById($id) {
        try {

            $data = $this->service->findById($id);

            $response = [
                'success' => 200,
                'message' => '成功',
                'data' => new ArticleResource($data),
            ];

        }catch(Exception $e) {
            $response = [
                'success' => 400,
                'message' => $e->getMessage()
            ];
        }

        return response()->json($response, $response['success']);
    }

    public function delete($id) {
        try {

            $data = $this->service->delete($id);

            $response = [
                'success' => 200,
                'message' => '成功',
            ];

        }catch(Exception $e) {
            $response = [
                'success' => 400,
                'message' => $e->getMessage()
            ];
        }

        return response()->json($response, $response['success']);
    }
}
