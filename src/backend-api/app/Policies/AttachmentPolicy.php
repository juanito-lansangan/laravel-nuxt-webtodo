<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewOrModify(User $user, Attachment $attachment): Response
    {
        if ($user->id === $attachment->task->user_id) {
            return Response::allow();
        }

        return Response::denyWithStatus(401);
    }
}
