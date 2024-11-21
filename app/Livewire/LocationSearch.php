<?php

namespace App\Livewire;

use Livewire\Component;

class LocationSearch extends Component
{
    public $query = '';
    public $suggestions = [];

    public function updatedQuery() {
        $locations = [
            'New York',
            'London',
            'Beijing',
            'Cardiff',
            'Swansea',
        ];

        $this->suggestions = $locations;
    }

    public function selectLocation($location) {
        $this->query = $location;
        $this->suggestions = [];
    }

    public function render()
    {
        return view('livewire.location-search');
    }
}
