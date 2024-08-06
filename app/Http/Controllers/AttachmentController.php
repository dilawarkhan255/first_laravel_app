<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function uploadSingle($file, $id, $model)
    {

        $filePath = $file->store("attachments/$model/$id", 'public');
        $model = "App\\Models\\".$model;
        Attachment::create([
            'attachment_type' => $model,
            'attachable_id' => $id,
            'name' => $file->getClientOriginalName(),
            'link' => $filePath,
            'type' => $file->extension(),
            'extension' => $file->extension(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }
}
