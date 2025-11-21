<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class YouTubeService
{
    private $apiKey;
    private $channelId;
    
    public function __construct()
    {
        $this->apiKey = config('services.youtube.api_key');
        $this->channelId = config('services.youtube.channel_id');
    }
    
    /**
     * Busca o último vídeo do canal (transmissão ao vivo ou vídeo mais recente)
     */
    public function getLatestVideo()
    {
        // Cache por 5 minutos para não fazer muitas requisições à API
        return Cache::remember('youtube_latest_video', 300, function () {
            try {
                // Primeiro tenta buscar transmissão ao vivo
                $liveVideo = $this->getLiveStream();
                
                if ($liveVideo) {
                    return $liveVideo;
                }
                
                // Se não houver transmissão ao vivo, busca o vídeo mais recente
                return $this->getLatestUpload();
                
            } catch (\Exception $e) {
                \Log::error('Erro ao buscar vídeo do YouTube: ' . $e->getMessage());
                return $this->getDefaultVideo();
            }
        });
    }
    
    /**
     * Busca transmissão ao vivo ativa
     */
    private function getLiveStream()
    {
        if (!$this->apiKey || !$this->channelId) {
            return null;
        }
        
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'channelId' => $this->channelId,
            'eventType' => 'live',
            'type' => 'video',
            'key' => $this->apiKey,
            'maxResults' => 1,
        ]);
        
        if ($response->successful() && !empty($response->json('items'))) {
            $video = $response->json('items')[0];
            return [
                'id' => $video['id']['videoId'],
                'title' => $video['snippet']['title'],
                'description' => $video['snippet']['description'],
                'thumbnail' => $video['snippet']['thumbnails']['high']['url'] ?? null,
                'is_live' => true,
            ];
        }
        
        return null;
    }
    
    /**
     * Busca o vídeo mais recente do canal
     */
    private function getLatestUpload()
    {
        if (!$this->apiKey || !$this->channelId) {
            return $this->getDefaultVideo();
        }
        
        $response = Http::get('https://www.googleapis.com/youtube/v3/search', [
            'part' => 'snippet',
            'channelId' => $this->channelId,
            'order' => 'date',
            'type' => 'video',
            'key' => $this->apiKey,
            'maxResults' => 1,
        ]);
        
        if ($response->successful() && !empty($response->json('items'))) {
            $video = $response->json('items')[0];
            return [
                'id' => $video['id']['videoId'],
                'title' => $video['snippet']['title'],
                'description' => $video['snippet']['description'],
                'thumbnail' => $video['snippet']['thumbnails']['high']['url'] ?? null,
                'is_live' => false,
            ];
        }
        
        return $this->getDefaultVideo();
    }
    
    /**
     * Retorna vídeo padrão caso não consiga buscar da API
     */
    private function getDefaultVideo()
    {
        return [
            'id' => 'hM9YbvTNOOg', // ID do vídeo padrão atual
            'title' => 'Culto ao Vivo - Igreja Vale da Benção',
            'description' => 'Assista nossas transmissões ao vivo',
            'thumbnail' => null,
            'is_live' => false,
        ];
    }
    
    /**
     * Limpa o cache do vídeo (útil para forçar atualização)
     */
    public function clearCache()
    {
        Cache::forget('youtube_latest_video');
    }
}
