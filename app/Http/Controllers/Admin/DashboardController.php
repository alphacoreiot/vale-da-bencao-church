<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        $totalSections = Section::count();
        $activeSections = Section::active()->count();
        $totalContents = \App\Models\SectionContent::count();
        $publishedContents = \App\Models\SectionContent::published()->count();
        $totalMedia = \App\Models\Media::count();
        
        $recentSections = Section::latest()->limit(5)->get();
        $recentContents = \App\Models\SectionContent::with('section')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSections',
            'activeSections',
            'totalContents',
            'publishedContents',
            'totalMedia',
            'recentSections',
            'recentContents'
        ));
    }
}
