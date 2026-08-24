<?php

namespace App\Http\Controllers;

use App\Models\FacebookDataDeletionRequest;
use App\Services\Auth\FacebookDataDeletionService;
use App\Services\Auth\FacebookSignedRequestParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use InvalidArgumentException;

class FacebookDataDeletionController extends Controller
{
    public function store(
        Request $request,
        FacebookSignedRequestParser $parser,
        FacebookDataDeletionService $facebookDataDeletion,
    ): JsonResponse {
        $signedRequest = $request->string('signed_request')->toString();

        if ($signedRequest === '') {
            return response()->json(['error' => 'Missing signed_request.'], 400);
        }

        try {
            $payload = $parser->parse($signedRequest);
        } catch (InvalidArgumentException) {
            return response()->json(['error' => 'Invalid signed_request.'], 400);
        }

        $facebookUserId = (string) ($payload['user_id'] ?? '');

        if ($facebookUserId === '') {
            return response()->json(['error' => 'Missing user_id.'], 400);
        }

        $deletionRequest = $facebookDataDeletion->queue($facebookUserId);

        return response()->json([
            'url' => $facebookDataDeletion->statusUrl($deletionRequest),
            'confirmation_code' => $deletionRequest->confirmation_code,
        ]);
    }

    public function show(Request $request, string $confirmationCode): View|JsonResponse
    {
        $deletionRequest = FacebookDataDeletionRequest::query()
            ->where('confirmation_code', $confirmationCode)
            ->firstOrFail();

        $payload = [
            'confirmation_code' => $deletionRequest->confirmation_code,
            'status' => $deletionRequest->action->value,
            'processed_at' => $deletionRequest->processed_at?->toIso8601String(),
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return view('facebook.data-deletion-status', [
            'deletionRequest' => $deletionRequest,
        ]);
    }
}
