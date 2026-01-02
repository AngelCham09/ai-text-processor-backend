<?php

namespace App\Http\Controllers\Api;

use App\Enums\TextActionType;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcessTextRequest;
use App\Models\TextJob;
use App\Services\TextProcessors\TextProcessorService;
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
            // Invalid enum/action type
            Log::warning('Invalid action type', [
                'user_id' => $user?->id,
                'action' => $request->action
            ]);
            return ApiResponse::error('Invalid action type', null, 400);
        } catch (\Exception $e) {
            // Other exceptions
            Log::error('Text processing error', [
                'message' => $e->getMessage(),
                'user_id' => $user?->id,
                'action' => $request->action,
            ]);
            return ApiResponse::error('Text processing failed', null, 500);
        }
    }

    public function history()
    {
        return TextJob::latest()->limit(50)->get();
    }
}
