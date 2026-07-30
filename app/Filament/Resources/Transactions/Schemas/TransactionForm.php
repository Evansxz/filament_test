<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Item;
use Dom\Text;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use League\Uri\Idna\Option;
use Livewire\Attributes\Reactive;
use Symfony\Contracts\Service\Attribute\Required;

class TransactionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')->default(auth()->id()),
                Hidden::make('date')->default(now())->required(),
                Section::make('Payment')
                    ->schema([
                        TextInput::make('Pay_total')->prefix('Rp.')->numeric()->inlineLabel()->required()->afterStateUpdated(function ($state, Set $set, Get $get){
                            $change = $state - $get('total');
                            $set('change', $change);
                        }),
                        TextInput::make('change')->prefix('Rp.')->numeric()->inlineLabel()->required()->readOnly()
                    ])->live(),
                Section::make('cart')
                    ->schema([
                        Repeater::make('detail')->hiddenLabel() 
                        ->relationship()
                        ->schema([
                           Select::make('item_id')->hiddenLabel()
                           ->options(Item::all()->pluck('name', 'id'))
                           ->Required()
                           ->Reactive(),
                        TextInput::make('qty')->numeric()->default(0)->minValue(0)->reactive()->inlineLabel()->Required()->afterStateUpdated(function ($state, Set $set, Get $get){
                            $price = Item::find($get('item_id'))?->price ?? 0;
                            $subtotal = $price * $state;
                            $set('subtotal', $subtotal);

                            $total = collect($get('../../detail'))->sum('subtotal');
                            $set('../../total', $total);
                        }),
                        TextInput::make('subtotal')->prefix('Rp.')->numeric()->inlineLabel()->required()->readonly()
                        ])->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get){
                            $total = collect($get('../../detail'))->sum('subtotal');
                            $set('../../total', $total);
                        })
                        ->deleteAction(
                fn ($action) => $action->after(function (Get $get, Set $set) {
                    $total = collect($get('detail'))->sum('subtotal');
                    $set('total', $total);
                })
            )
                        ,

                        TextInput::make('total')->prefix('Rp.')->numeric()->inlineLabel()->required()->readOnly()
                        
                ])
            ]);
    }
}