<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Retorna dados do dashboard
     */
    public function index()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'data' => [
                'welcome_message' => "Bem-vindo(a), {$user->name}!",
                'stats' => [
                    // Adicione estatísticas conforme necessário
                ],
            ]
        ]);
    }
}
