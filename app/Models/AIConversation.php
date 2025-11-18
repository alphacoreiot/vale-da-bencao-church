<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AIConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_conversations';

    protected $fillable = [
        'section_id',
        'user_session',
        'messages',
        'context',
    ];

    protected $casts = [
        'messages' => 'array',
        'context' => 'array',
    ];

    /**
     * Get the section that owns the conversation.
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Add a message to the conversation.
     */
    public function addMessage(string $role, string $content): void
    {
        $messages = $this->messages ?? [];
        $messages[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toIso8601String(),
        ];
        $this->messages = $messages;
        $this->save();
    }

    /**
     * Get the last message.
     */
    public function getLastMessage(): ?array
    {
        $messages = $this->messages ?? [];
        return end($messages) ?: null;
    }

    /**
     * Get messages for a specific role.
     */
    public function getMessagesByRole(string $role): array
    {
        return array_filter($this->messages ?? [], function ($message) use ($role) {
            return $message['role'] === $role;
        });
    }

    /**
     * Get the message count.
     */
    public function getMessageCount(): int
    {
        return count($this->messages ?? []);
    }

    /**
     * Clear all messages.
     */
    public function clearMessages(): void
    {
        $this->messages = [];
        $this->save();
    }
}
