<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudoCelula extends Model
{
    protected $table = 'estudos_celula';

    protected $fillable = [
        'tema',
        'data',
        'pdf_path',
        'youtube_url',
        'ativo',
    ];

    protected $casts = [
        'data' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Retorna a URL completa do PDF
     */
    public function getPdfUrlAttribute()
    {
        if ($this->pdf_path) {
            return asset('uploads/estudos/' . $this->pdf_path);
        }
        return null;
    }

    /**
     * Extrai o ID do vídeo do YouTube
     */
    public function getYoutubeIdAttribute()
    {
        if (!$this->youtube_url) {
            return null;
        }

        $patterns = [
            '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
            '/youtube\.com\/embed\/([^\&\?\/]+)/',
            '/youtu\.be\/([^\&\?\/]+)/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->youtube_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }
}
