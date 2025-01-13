<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AttachmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Attachment $attachment): JsonResponse
    {
        Gate::authorize('viewOrModify', $attachment);

        $result = $attachment->delete();

        return response()->json(204);
    }
}
