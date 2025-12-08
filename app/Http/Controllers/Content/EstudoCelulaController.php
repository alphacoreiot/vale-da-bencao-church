<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\EstudoCelula;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstudoCelulaController extends Controller
{
    /**
     * Lista todos os estudos
     */
    public function index()
    {
        $estudos = EstudoCelula::orderBy('data', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $estudos->map(function ($estudo) {
                return [
                    'id' => $estudo->id,
                    'tema' => $estudo->tema,
                    'data' => $estudo->data->format('d/m/Y'),
                    'data_raw' => $estudo->data->format('Y-m-d'),
                    'pdf_url' => $estudo->pdf_url,
                    'pdf_path' => $estudo->pdf_path,
                    'youtube_url' => $estudo->youtube_url,
                    'youtube_id' => $estudo->youtube_id,
                    'ativo' => $estudo->ativo,
                ];
            })
        ]);
    }

    /**
     * Cria um novo estudo
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tema' => 'required|string|max:255',
            'data' => 'required|date',
            'pdf' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
            'youtube_url' => 'nullable|url',
        ], [
            'tema.required' => 'O tema é obrigatório',
            'data.required' => 'A data é obrigatória',
            'pdf.mimes' => 'O arquivo deve ser um PDF',
            'pdf.max' => 'O PDF deve ter no máximo 10MB',
            'youtube_url.url' => 'URL do YouTube inválida',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            
            // Caminho absoluto para uploads/estudos
            $uploadPath = '/home/u817008098/domains/valedabencao.com.br/public_html/uploads/estudos';
            
            // Fallback para ambiente local
            if (!file_exists('/home/u817008098')) {
                $uploadPath = public_path('uploads/estudos');
            }
            
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            $pdfPath = $fileName;
        }

        $estudo = EstudoCelula::create([
            'tema' => $request->tema,
            'data' => $request->data,
            'pdf_path' => $pdfPath,
            'youtube_url' => $request->youtube_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estudo cadastrado com sucesso!',
            'data' => [
                'id' => $estudo->id,
                'tema' => $estudo->tema,
                'data' => $estudo->data->format('d/m/Y'),
                'data_raw' => $estudo->data->format('Y-m-d'),
                'pdf_url' => $estudo->pdf_url,
                'pdf_path' => $estudo->pdf_path,
                'youtube_url' => $estudo->youtube_url,
                'youtube_id' => $estudo->youtube_id,
                'ativo' => $estudo->ativo,
            ]
        ]);
    }

    /**
     * Atualiza um estudo
     */
    public function update(Request $request, $id)
    {
        $estudo = EstudoCelula::find($id);

        if (!$estudo) {
            return response()->json([
                'success' => false,
                'message' => 'Estudo não encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'tema' => 'required|string|max:255',
            'data' => 'required|date',
            'pdf' => 'nullable|file|mimes:pdf|max:10240',
            'youtube_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload de novo PDF se enviado
        if ($request->hasFile('pdf')) {
            // Remove PDF antigo
            if ($estudo->pdf_path) {
                $oldPath = '/home/u817008098/domains/valedabencao.com.br/public_html/uploads/estudos/' . $estudo->pdf_path;
                if (!file_exists('/home/u817008098')) {
                    $oldPath = public_path('uploads/estudos/' . $estudo->pdf_path);
                }
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('pdf');
            $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '_', $file->getClientOriginalName());
            
            $uploadPath = '/home/u817008098/domains/valedabencao.com.br/public_html/uploads/estudos';
            if (!file_exists('/home/u817008098')) {
                $uploadPath = public_path('uploads/estudos');
            }
            
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $file->move($uploadPath, $fileName);
            $estudo->pdf_path = $fileName;
        }

        // Remove PDF se solicitado
        if ($request->has('remove_pdf') && $request->remove_pdf) {
            if ($estudo->pdf_path) {
                $oldPath = '/home/u817008098/domains/valedabencao.com.br/public_html/uploads/estudos/' . $estudo->pdf_path;
                if (!file_exists('/home/u817008098')) {
                    $oldPath = public_path('uploads/estudos/' . $estudo->pdf_path);
                }
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $estudo->pdf_path = null;
        }

        $estudo->tema = $request->tema;
        $estudo->data = $request->data;
        $estudo->youtube_url = $request->youtube_url;
        $estudo->save();

        return response()->json([
            'success' => true,
            'message' => 'Estudo atualizado com sucesso!',
            'data' => [
                'id' => $estudo->id,
                'tema' => $estudo->tema,
                'data' => $estudo->data->format('d/m/Y'),
                'data_raw' => $estudo->data->format('Y-m-d'),
                'pdf_url' => $estudo->pdf_url,
                'pdf_path' => $estudo->pdf_path,
                'youtube_url' => $estudo->youtube_url,
                'youtube_id' => $estudo->youtube_id,
                'ativo' => $estudo->ativo,
            ]
        ]);
    }

    /**
     * Remove um estudo
     */
    public function destroy($id)
    {
        $estudo = EstudoCelula::find($id);

        if (!$estudo) {
            return response()->json([
                'success' => false,
                'message' => 'Estudo não encontrado'
            ], 404);
        }

        // Remove o PDF se existir
        if ($estudo->pdf_path) {
            $pdfPath = '/home/u817008098/domains/valedabencao.com.br/public_html/uploads/estudos/' . $estudo->pdf_path;
            if (!file_exists('/home/u817008098')) {
                $pdfPath = public_path('uploads/estudos/' . $estudo->pdf_path);
            }
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }

        $estudo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Estudo removido com sucesso!'
        ]);
    }
}
