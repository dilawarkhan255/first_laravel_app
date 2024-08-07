<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public $image_extensions = [
        'jpg', 'jpeg', 'jpe', 'jif', 'jfif', 'jfi',
        'png',
        'gif',
        'webp',
        'tiff', 'tif',
        'bmp', 'dib',
        'jp2', 'j2k', 'jpf', 'jpx', 'jpm', 'mj2'
    ];

    public $video_extensions = ['flv', 'mp4', 'm3u8', 'ts', '3gp', 'mov', 'avi', 'wmv', 'webm', 'mkv', 'ogv', 'ogg', 'oga', 'ogx'];

    public $audio_extensions = [];

    public $document_extensions = ["doc", "docx", "pdf", 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf'];

    public function uploadSingle($file, $id, $model, $type)
    {
        $filePath = $file->store("/attachments/$model/$id", 'public');
        $link = "/storage/attachments/$model/$id/" . $file->hashName();

        $model = "App\\Models\\" . $model;

        $oldAttachment = Attachment::where('attachable_id', $id)
            ->where('attachment_type', $model)
            ->where('type', $type)
            ->first();

        if ($oldAttachment) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $oldAttachment->link));
            $oldAttachment->delete();
        }
        Attachment::create([
            'attachment_type' => $model,
            'attachable_id' => $id,
            'name' => $file->getClientOriginalName(),
            'link' => $link,
            'type' => $type,
            'extension' => $file->extension(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function uploadMultiple($files, $id, $model, $type)
    {

        $filePath = $files->store("/attachments/$model/$id", 'public');
        $link = "/storage/attachments/$model/$id/" . $files->hashName();

        $model = "App\\Models\\" . $model;

        $oldAttachment = Attachment::where('attachable_id', $id)
            ->where('attachment_type', $model)
            ->where('type', $type)
            ->first();

        if ($oldAttachment) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $oldAttachment->link));
            $oldAttachment->delete();
        }

        foreach ($files as $key => $file) {
                $filePath = $file->store("/attachments/$model/$id", 'public');
                $link = "/storage/attachments/$model/$id/" . $file->hashName();
                $model = "App\\Models\\".$model;
                Attachment::create([
                'attachment_type' => $model,
                'attachable_id' => $id,
                'name' => $file->getClientOriginalName(),
                'link' => $link,
                'type' => $type,
                'extension' => $file->extension(),
                'size' => $file->getSize(),
            ]);
        }
        return redirect()->back()->with('message','File uploaded.');
    }
}
