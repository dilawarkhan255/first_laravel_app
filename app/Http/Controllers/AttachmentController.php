<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

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

    public function uploadMultiple($files, $id, $model)
    {
        // Define file type categories
        $imageExtensions = ['jpg', 'jpeg', 'png', 'avif', 'gif', 'jfif', 'webp'];
        $videoExtensions = ['mp4', 'avi'];
        $audioExtensions = ['mp3', 'wav'];
        $documentExtensions = ['pdf', 'doc', 'docx'];

        foreach ($files as $file) {


            $filePath = $file->store("/attachments/$model/$id", 'public');
            $link = "/storage/attachments/$model/$id/" . $file->hashName();

            $modelClass = "App\\Models\\" . $model;

            $type = 'other';
            $extension = $file->extension();
            if (in_array($extension, $imageExtensions)) {
                $type = 'image';
            } elseif (in_array($extension, $videoExtensions)) {
                $type = 'video';
            } elseif (in_array($extension, $audioExtensions)) {
                $type = 'audio';
            } elseif (in_array($extension, $documentExtensions)) {
                $type = 'document';
            }

            $oldAttachment = Attachment::where('attachable_id', $id)
            ->where('attachment_type', $modelClass)
            ->where('type', $type)
            ->first();

            if ($oldAttachment) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldAttachment->link));
                $oldAttachment->delete();
            }

            $path = $file->store('attachments');
            Attachment::create([
                'attachment_type' => $modelClass,
                'attachable_id' => $id,
                'name' => $file->getClientOriginalName(),
                'link' => $link,
                'type' => $type,
                'extension' => $extension,
                'size' => $file->getSize(),
            ]);
        }

        return redirect()->back()->with('message', 'Files uploaded.');
    }

}
