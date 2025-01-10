<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Task;

class FileAttachmentService 
{
    public function upload(array $files, Task $task): void
    {
        $uploadFiles = [];

        foreach ($files as $file) {
            $path = $file->store('attachments');

            $uploadFiles[] = new Attachment([
                'path' => $path,
                'type' => $file->getClientOriginalExtension(),
            ]);
        }

        $task->attachments()->saveMany($uploadFiles);
    }
}