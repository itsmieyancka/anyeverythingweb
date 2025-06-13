{{-- resources/views/filament/resources/product-resource/pages/manage-product-variations.blade.php --}}
<x-filament::page>
    {{ $this->form }}

    <x-filament::button wire:click="save">
        Save Variations
    </x-filament::button>
</x-filament::page>
