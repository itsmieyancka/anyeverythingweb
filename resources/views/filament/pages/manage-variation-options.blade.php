<x-filament::page>
    <h1 class="text-xl font-bold mb-4">Variation Options for: {{ $product->name }}</h1>

    {{-- You could use a Filament Table/Repeater/RelationManager here --}}
    {{-- Or link to VariationOptionResource with a filter --}}

    <a href="{{ route('filament.admin.resources.variation-options.index') }}" class="btn btn-primary">
        View All Variation Options
    </a>
</x-filament::page>

