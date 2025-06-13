<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Calendar extends Component
{
    public $events = [];

    public function mount()
    {
        // Fill with example ecommerce activities
        $this->events = [
            [
                'title' => 'New Product Launch: SmartWatch',
                'start' => '2025-06-10',
                'color' => '#10B981', // green
            ],
            [
                'title' => 'Summer Sale Campaign',
                'start' => '2025-06-15',
                'end' => '2025-06-30',
                'color' => '#3B82F6', // blue
            ],
            [
                'title' => 'Customer Review Deadline',
                'start' => '2025-06-20',
                'color' => '#F59E0B', // amber
            ],
            [
                'title' => 'Vendor Meeting',
                'start' => '2025-06-25T14:00:00',
                'color' => '#EF4444', // red
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.calendar');
    }
}
