<?php

namespace App\Http\Controllers\Api;

use App\Enums\TextActionType;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessTextRequest;
use App\Http\Resources\TextJobResource;
use App\Models\TextJob;
use App\Services\TextProcessors\TextProcessorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TextProcessorController extends Controller
{
    protected $service;

    public function __construct(TextProcessorService $service)
    {
        $this->service = $service;
    }

    public function process(ProcessTextRequest $request)
    {
        $user = $request->user();

        Log::info('Text action requested', [
            'user_id' => $user?->id,
            'action' => $request->action,
        ]);

        try {
            $validated = $request->validated();

            $action = TextActionType::from($validated['action']);

            $result = $this->service->process($validated['text'], $action);

            TextJob::create([
                'input_text' => $validated['text'],
                'action_type' => $action->value,
                'output_text' => $result,
                'user_id' => $user?->id,
            ]);

            return ApiResponse::success('Text processed successfully', [
                'result' => $result
            ]);
        } catch (\InvalidArgumentException $e) {
            Log::warning('Invalid action type', [
                'user_id' => $user?->id,
                'action' => $request->action
            ]);
            return ApiResponse::error('Invalid action type', null, 400);
        } catch (\Exception $e) {
            Log::error('Text processing error', [
                'message' => $e->getMessage(),
                'user_id' => $user?->id,
                'action' => $request->action,
            ]);
            return ApiResponse::error('Text processing failed', null, 500);
        }
    }

    public function history(Request $request)
    {
        $jobs = TextJob::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return ApiResponse::success('History fetched successfully', TextJobResource::collection($jobs));
    }

    public function deleteHistory(Request $request)
    {
        $userId = $request->user()->id;
        $ids = $request->input('ids');

        $query = TextJob::where('user_id', $userId);

        if (!empty($ids) && is_array($ids)) {
            $query->whereIn('id', $ids)->delete();
            $message = 'Selected history cleared.';
        } else {
            $query->delete();
            $message = 'All history cleared.';
        }

        return ApiResponse::success($message);
    }
}
