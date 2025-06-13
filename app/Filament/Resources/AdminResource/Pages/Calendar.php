<?php

namespace App\Filament\Resources\AdminResource\Pages;

use Filament\Pages\Page;

class Calendar extends Page
{
    protected static string $resource = \App\Filament\Resources\AdminResource::class;

    protected static string $view = 'filament.resources.admin-resource.pages.calendar';

    public $events = [];

    public function mount()
    {
        // Example events — replace with your real activity data
        $this->events = [
            [
                'title' => 'Product Launch',
                'start' => '2025-06-30',
            ],
            [
                'title' => 'Team Meeting',
                'start' => '2025-06-29T14:00:00',
            ],
            [
                'title' => 'Inventory Check',
                'start' => '2025-06-28',
                'end' => '2025-06-29',
            ],
        ];
    }
}

