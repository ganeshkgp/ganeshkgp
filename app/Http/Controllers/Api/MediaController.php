<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class MediaController extends Controller
{
    /**
     * Display a listing of uploaded media files.
     */
    public function index()
    {
        try {
            $mediaFiles = [];
            $directories = ['uploads/images', 'uploads/models', 'uploads/documents'];

            foreach ($directories as $directory) {
                if (Storage::disk('public')->exists($directory)) {
                    $files = Storage::disk('public')->files($directory);

                    foreach ($files as $file) {
                        $mediaFiles[] = [
                            'id' => Str::uuid(),
                            'filename' => basename($file),
                            'path' => $file,
                            'url' => Storage::url($file),
                            'size' => Storage::size($file),
                            'type' => $this->getFileType(basename($file)),
                            'uploaded_at' => Storage::lastModified($file)
                        ];
                    }
                }
            }

            return response()->json([
                'success' => true,
                'data' => $mediaFiles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve media files: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly uploaded file.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:10240', // Max 10MB
            'type' => 'required|in:image,model,document'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $type = $request->input('type');

            // Generate unique filename
            $filename = time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();

            // Determine directory based on type
            $directory = match($type) {
                'image' => 'uploads/images',
                'model' => 'uploads/models',
                'document' => 'uploads/documents',
                default => 'uploads/misc'
            };

            // Process and store file
            if ($type === 'image') {
                $this->processImage($file, $directory, $filename);
            } else {
                $file->storeAs($directory, $filename, 'public');
            }

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'id' => Str::uuid(),
                    'filename' => $filename,
                    'original_name' => $file->getClientOriginalName(),
                    'path' => $directory . '/' . $filename,
                    'url' => Storage::url($directory . '/' . $filename),
                    'size' => $file->getSize(),
                    'type' => $type,
                    'mime_type' => $file->getMimeType()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified media file info.
     */
    public function show(string $id)
    {
        // This would require a database table to track files
        // For now, return a placeholder response
        return response()->json([
            'success' => false,
            'message' => 'File details not implemented yet'
        ], 501);
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy(Request $request, string $filename)
    {
        try {
            // Find file in all possible directories
            $directories = ['uploads/images', 'uploads/models', 'uploads/documents'];
            $found = false;

            foreach ($directories as $directory) {
                $path = $directory . '/' . $filename;
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process uploaded images with optimization.
     */
    private function processImage($file, $directory, $filename)
    {
        $image = Image::make($file);

        // Resize if too large (max width 1920px, max height 1080px)
        if ($image->width() > 1920 || $image->height() > 1080) {
            $image->resize(1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }

        // Create thumbnails for images
        $thumbnailPath = $directory . '/thumbnails/' . $filename;
        $thumbnail = clone $image;
        $thumbnail->resize(300, 300, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // Ensure directories exist
        Storage::disk('public')->makeDirectory($directory);
        Storage::disk('public')->makeDirectory($directory . '/thumbnails');

        // Store optimized image and thumbnail
        $image->save(storage_path('app/public/' . $directory . '/' . $filename), 85);
        $thumbnail->save(storage_path('app/public/' . $thumbnailPath), 75);
    }

    /**
     * Determine file type based on extension.
     */
    private function getFileType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return match($extension) {
            'jpg', 'jpeg', 'png', 'gif', 'webp' => 'image',
            'obj', 'fbx', 'gltf', 'glb' => 'model',
            'pdf', 'doc', 'docx', 'txt' => 'document',
            default => 'misc'
        };
    }
}
