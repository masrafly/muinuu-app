<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccountResource\Pages;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('key')->required(),
                        Forms\Components\TextInput::make('name')->required(),
                        Forms\Components\Select::make('element_level')
                            ->required()
                            ->options([
                                'heading' => 'Heading',
                                'breakdown' => 'Breakdown'
                            ])
                            ->columnSpan('full')->live(),
                        Forms\Components\Select::make('key_parent')
                            ->label('Key Parent')
                            ->options(function (Forms\Get $get) {
                                if ($get('element_level') === 'breakdown') {
                                    $currentRecordId = $get('id');
                                    return Account::when($currentRecordId, fn ($query) => $query->where('id', '!=', $currentRecordId))
                                        ->pluck('name', 'key')
                                        ->toArray();
                                }
                                return [];
                            })
                            ->visible(fn (Forms\Get $get): bool => $get('element_level') === 'breakdown'),
                        Forms\Components\TextInput::make('acc_type')->label('Account Type')->required(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::id()) // Mengisi otomatis dengan ID user yang sedang login
                            ->required() // Pastikan ini diisi
                            ->hiddenOn('edit')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')->sortable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('element_level'),
                TextColumn::make('acc_type'),
                TextColumn::make('updated_at'),
                TextColumn::make('created_at'),
                TextColumn::make('user.name')
                ->label('Created By'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // public static function canAccess(): bool
    // {
    //     // Pastikan user sedang login
    //     if (!Auth::check()) {
    //         return false;
    //     }

    //     // Dapatkan user yang sedang login
    //     $user = Auth::user();

    //     // Izinkan akses jika user adalah admin ATAU staff
    //     return $user->isAdmin() || $user->hasRole('staff');
    // }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'view' => Pages\ViewAccount::route('/view/{record}'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}
