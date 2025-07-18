<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use App\Models\Account;
use Filament\Forms\Form;
use Filament\Forms\Components;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Exports\TransactionExporter;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Forms;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('key') // Sesuaikan dengan nama kolom di tabel transactions
                            ->options(Account::all()->pluck('name', 'key')) // Mengambil semua key dari tabel accounts
                            ->searchable() // Opsional: Tambahkan fitur pencarian
                            ->required(),
                        Forms\Components\TextInput::make('receipt')->required(),
                        Forms\Components\TextInput::make('value')
                            ->numeric()
                            ->required()
                            ->prefix('Rp'),
                        Forms\Components\Select::make('type')
                            ->required()
                            ->options([
                                'Debit' => 'Debit',
                                'Credit' => 'Credit'
                            ]),
                        Forms\Components\DateTimePicker::make('transaction_date')->required(),
                        Forms\Components\TextArea::make('description')->required(),
                        Forms\Components\Hidden::make('user_id')
                            ->default(Auth::id()) // Mengisi otomatis dengan ID user yang sedang login
                            ->required() // Pastikan ini diisi
                            ->hiddenOn('edit'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->sortable(),
            TextColumn::make('receipt')->searchable(),
            TextColumn::make('value')->numeric()->money('IDR', false, 'id'),
            TextColumn::make('type'),
            TextColumn::make('transaction_date'),
            TextColumn::make('description'),
            TextColumn::make('updated_at'),
            TextColumn::make('created_at'),
            TextColumn::make('user.name')
                ->label('Created By'),
        ])
        ->headerActions([
            // Export semua data (dengan filter yang aktif)
            ExportAction::make()
                ->exporter(TransactionExporter::class)
                ->label('Export to Excel')
                ->color('success')
                ->icon('heroicon-o-document-arrow-down'),
        ])
        ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Export data yang dipilih
                    ExportBulkAction::make()
                        ->exporter(TransactionExporter::class)
                        ->label('Export Terpilih')
                        ->color('success')
                        ->icon('heroicon-o-document-arrow-down'),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
