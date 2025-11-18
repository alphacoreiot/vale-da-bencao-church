<?php

namespace App\Services;

use App\Models\Section;
use App\Models\HighlightLog;
use App\Models\RotationConfig;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SectionRotationService
{
    /**
     * Rotate sections based on the current configuration.
     */
    public function rotate(): ?Section
    {
        $config = RotationConfig::current();

        if (!$config) {
            Log::warning('No active rotation configuration found');
            return null;
        }

        $section = match ($config->rotation_type) {
            'circular' => $this->circularRotation(),
            'priority' => $this->priorityBasedRotation(),
            'scheduled' => $this->scheduledRotation(),
            'random' => $this->randomWeightedRotation(),
            default => $this->priorityBasedRotation(),
        };

        if ($section) {
            $this->setHighlight($section, $config->interval_minutes);
            Log::info("Section '{$section->name}' highlighted via {$config->rotation_type} rotation");
        }

        return $section;
    }

    /**
     * Circular rotation (round-robin).
     */
    private function circularRotation(): ?Section
    {
        $lastHighlighted = Section::active()
            ->whereNotNull('last_highlighted_at')
            ->orderBy('last_highlighted_at', 'desc')
            ->first();

        $query = Section::active()->ordered();

        if ($lastHighlighted) {
            // Get the next section after the last highlighted
            $nextSection = $query->where('display_order', '>', $lastHighlighted->display_order)
                ->first();

            if (!$nextSection) {
                // If no next section, start from the beginning
                $nextSection = $query->first();
            }

            return $nextSection;
        }

        // If no section was highlighted before, start with the first one
        return $query->first();
    }

    /**
     * Priority-based rotation.
     */
    private function priorityBasedRotation(): ?Section
    {
        // Select section with highest priority that hasn't been highlighted recently
        return Section::active()
            ->orderBy('priority', 'desc')
            ->orderBy('last_highlighted_at', 'asc')
            ->orderBy('display_order', 'asc')
            ->first();
    }

    /**
     * Scheduled rotation based on date/time.
     */
    private function scheduledRotation(): ?Section
    {
        // Check if there's a section that should be highlighted now
        $section = Section::active()
            ->where('next_highlight_at', '<=', now())
            ->orderBy('priority', 'desc')
            ->first();

        if ($section) {
            return $section;
        }

        // Fallback to priority-based if no scheduled section
        return $this->priorityBasedRotation();
    }

    /**
     * Random weighted rotation (based on priority).
     */
    private function randomWeightedRotation(): ?Section
    {
        $sections = Section::active()->get();

        if ($sections->isEmpty()) {
            return null;
        }

        // Create a weighted array based on priority
        $weighted = [];
        foreach ($sections as $section) {
            $weight = max($section->priority, 1); // Ensure at least weight of 1
            for ($i = 0; $i < $weight; $i++) {
                $weighted[] = $section;
            }
        }

        // Randomly select from the weighted array
        return $weighted[array_rand($weighted)];
    }

    /**
     * Set a section as highlighted.
     */
    public function setHighlight(Section $section, int $durationMinutes = null): void
    {
        // End any currently active highlights
        $this->endCurrentHighlights();

        $duration = $durationMinutes ?? $section->highlight_duration;
        $startedAt = now();
        $endsAt = now()->addMinutes($duration);

        // Update section
        $section->update([
            'last_highlighted_at' => $startedAt,
            'next_highlight_at' => $endsAt,
        ]);

        // Create highlight log
        HighlightLog::create([
            'section_id' => $section->id,
            'started_at' => $startedAt,
            'reason' => 'Automatic rotation',
        ]);
    }

    /**
     * End all currently active highlights.
     */
    private function endCurrentHighlights(): void
    {
        HighlightLog::active()->each(function ($log) {
            $log->end('Replaced by new highlight');
        });
    }

    /**
     * Get the currently highlighted section.
     */
    public function getCurrentHighlight(): ?Section
    {
        return Section::active()
            ->whereNotNull('last_highlighted_at')
            ->whereNotNull('next_highlight_at')
            ->where('next_highlight_at', '>', now())
            ->orderBy('last_highlighted_at', 'desc')
            ->first();
    }

    /**
     * Get sections that need to be rotated.
     */
    public function getSectionsNeedingRotation(): Collection
    {
        return Section::active()
            ->where(function ($query) {
                $query->whereNull('next_highlight_at')
                    ->orWhere('next_highlight_at', '<=', now());
            })
            ->get();
    }

    /**
     * Force highlight a specific section.
     */
    public function forceHighlight(Section $section, int $durationMinutes = null): void
    {
        $this->setHighlight($section, $durationMinutes);
        Log::info("Section '{$section->name}' manually highlighted");
    }

    /**
     * Get rotation statistics.
     */
    public function getStatistics(): array
    {
        $totalSections = Section::count();
        $activeSections = Section::active()->count();
        $currentHighlight = $this->getCurrentHighlight();
        $config = RotationConfig::current();

        return [
            'total_sections' => $totalSections,
            'active_sections' => $activeSections,
            'current_highlight' => $currentHighlight?->name,
            'rotation_type' => $config?->rotation_type,
            'interval_minutes' => $config?->interval_minutes,
            'last_rotation' => HighlightLog::latest()->first()?->started_at,
        ];
    }
}
