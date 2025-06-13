<style>
    /* Target the canvas element by its dynamic ID */
    #{{ $this->getId() }} {
        height: 300px !important;
        max-height: 300px;
    }
</style>

<x-filament::widget>
    <x-filament::card>
        <canvas id="{{ $this->getId() }}"></canvas>
    </x-filament::card>
</x-filament::widget>
