<?php

namespace App\Filament\Resources\Transactions\Schemas;

use App\Models\Item;
use Dom\Text;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
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
                        TextInput::make('Pay_total')->prefix('Rp.')->numeric()->inlineLabel()->required(),
                        TextInput::make('change')->prefix('Rp.')->numeric()->inlineLabel()->required()
                    ]),
                Section::make('cart')
                    ->schema([
                        Repeater::make('detail')->hiddenLabel() 
                        ->relationship()
                        ->schema([
                           Select::make('item_id')->hiddenLabel()
                           ->options(Item::all()->pluck('name', 'id'))
                           ->Required()
                           ->Reactive(),
                        TextInput::make('qty')->numeric()->default(1)->reactive()->inlineLabel()->Required(),
                        TextInput::make('subtotal')->prefix('Rp.')->numeric()->inlineLabel()->required()->readonly()
                        ]),

                        TextInput::make('total')->numeric()->inlineLabel()->required()->readonly()
                ])
            ]);
    }
}