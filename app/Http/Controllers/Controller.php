<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    protected function __imageSave($request, $key = '', $folder_name = '', $old_img = ''): ?string
    {
        $fileName = null;
        if ($request->hasFile($key) && !empty($key) && !empty($folder_name)) {
            $image = $request->file($key);
            $originalName = $image->getClientOriginalName();
            $file_name = time() . '_' . $originalName; // Create unique filename

            // Store the file in the public disk
            $fileName = $image->storeAs($folder_name, $file_name, 'public');

            // Handle old image deletion
            if (!empty($old_img)) {
                // Assuming $old_img contains the path relative to the public disk
                if (Storage::disk('public')->exists($old_img)) {
                    Storage::disk('public')->delete($old_img);
                }
            }
        }

        return $fileName ?: null; // Return filename or null
    }
}
