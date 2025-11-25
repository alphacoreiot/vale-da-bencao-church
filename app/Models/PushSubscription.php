<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'p256dh_key',
        'auth_token',
        'user_agent',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Obter todas as subscriptions ativas
     */
    public static function getActive()
    {
        return self::where('active', true)->get();
    }

    /**
     * Desativar subscription por endpoint
     */
    public static function deactivateByEndpoint(string $endpoint)
    {
        return self::where('endpoint', $endpoint)->update(['active' => false]);
    }
}
