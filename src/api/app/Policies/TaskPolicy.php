<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function viewOrModify(User $user, Task $task): Response
    {
        if ($user->id === $task->user_id) {
            return Response::allow();
        }

        return Response::denyWithStatus(401);
    }
}
