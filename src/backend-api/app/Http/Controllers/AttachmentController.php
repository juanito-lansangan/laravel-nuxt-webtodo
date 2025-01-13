<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function download(Attachment $attachment)
    {
        return Storage::download($attachment->path, $attachment->name);
    }

    /**
     * Handle the incoming request.
     */
    public function destroy(Attachment $attachment): JsonResponse
    {
        Gate::authorize('viewOrModify', $attachment);

        $result = $attachment->delete();

        return response()->json(204);
    }
}
