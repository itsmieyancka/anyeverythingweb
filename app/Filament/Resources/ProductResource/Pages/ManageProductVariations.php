<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\Page; // Correct Page class
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Illuminate\View\View;

class ManageProductVariations extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;


    public $product;
    public $variations = [];

    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.manage-product-variations';

    public static function getRoute(): string
    {
        return '/{record}/variations';
    }

    public function mount($record): void
    {
        $this->product = ProductResource::getModel()::findOrFail($record);

        $this->variations = $this->product->variations()->get()->toArray();

        $this->form->fill([
            'variations' => $this->variations,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()->schema([
                Repeater::make('variations')
                    ->label('Product Variations')
                    ->schema([
                        TextInput::make('type')
                            ->required()
                            ->placeholder('Variation Type (e.g., Size, Color)'),

                        TextInput::make('option')
                            ->required()
                            ->placeholder('Option (e.g., Large, Red)'),
                    ])
                    ->columns(2)
                    ->createItemButtonLabel('Add Variation'),
            ]),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $this->product->variations()->delete();

        foreach ($data['variations'] ?? [] as $variation) {
            $this->product->variations()->create([
                'type' => $variation['type'],
                'option' => $variation['option'],
            ]);
        }

        Notification::make()
            ->title('Product variations updated successfully.')
            ->success()
            ->send();
    }

}
