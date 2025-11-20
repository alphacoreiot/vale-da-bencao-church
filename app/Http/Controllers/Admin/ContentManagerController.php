<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentManagerController extends Controller
{
    public function index()
    {
        return view('admin.content.index');
    }

    public function eventos()
    {
        $section = Section::where('slug', 'eventos')->firstOrFail();
        $media = $section->media()
            ->latest()
            ->paginate(12);

        return view('admin.content.eventos', compact('section', 'media'));
    }

    public function eventosStore(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpeg,jpg,png,gif,mp4,mov,avi|max:51200', // 50MB
                'alt_text' => 'nullable|string|max:255',
            ]);

            $section = Section::where('slug', 'eventos')->first();
            
            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seção Eventos não encontrada. Execute: php artisan db:seed --class=EventosSectionSeeder'
                ], 404);
            }

            $file = $request->file('file');
            
            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nenhum arquivo foi enviado.'
                ], 400);
            }
            
            $path = $file->store('eventos', 'public');
            
            $type = str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image';

            Media::create([
                'section_id' => $section->id,
                'type' => $type,
                'path' => $path,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'alt_text' => $request->alt_text,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mídia adicionada com sucesso!'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Erro ao fazer upload de mídia: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar upload: ' . $e->getMessage()
            ], 500);
        }
    }

    public function eventosDestroy(Media $media)
    {
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Mídia removida com sucesso!'
        ]);
    }
}
