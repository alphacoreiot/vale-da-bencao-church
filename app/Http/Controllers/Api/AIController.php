<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Services\AIAgentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AIController extends Controller
{
    public function __construct(
        private AIAgentService $aiService
    ) {}

    /**
     * Chat with AI agent for a specific section.
     */
    public function chat(Request $request, string $sectionSlug)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $section = Section::where('slug', $sectionSlug)
            ->where('is_active', true)
            ->firstOrFail();

        // Generate session ID if not provided
        $sessionId = $validated['session_id'] ?? Str::uuid()->toString();

        $response = $this->aiService->chat(
            $section,
            $validated['message'],
            $sessionId
        );

        return response()->json([
            'success' => true,
            'session_id' => $sessionId,
            'response' => $response['message'],
            'conversation_id' => $response['conversation_id'],
        ]);
    }

    /**
     * Clear conversation history.
     */
    public function clearConversation(Request $request, string $sectionSlug)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
        ]);

        $section = Section::where('slug', $sectionSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $cleared = $this->aiService->clearConversation(
            $validated['session_id'],
            $section
        );

        return response()->json([
            'success' => $cleared,
            'message' => $cleared ? 'Conversa limpa com sucesso' : 'Nenhuma conversa encontrada',
        ]);
    }

    /**
     * Get conversation history.
     */
    public function getConversation(Request $request, string $sectionSlug)
    {
        $validated = $request->validate([
            'session_id' => 'required|string',
        ]);

        $section = Section::where('slug', $sectionSlug)
            ->where('is_active', true)
            ->firstOrFail();

        $conversation = $this->aiService->getConversationHistory(
            $validated['session_id'],
            $section
        );

        return response()->json([
            'success' => true,
            'messages' => $conversation?->messages ?? [],
        ]);
    }
}
