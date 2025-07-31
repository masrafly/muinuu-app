<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    // protected static ?string $navigationLabel = 'Author';
    // protected static ?string $breadcrumb = 'Users';

public static function mutateFormDataBeforeSave(array $data): array
{
    if (empty($data['password'])) {
        Notification::make()
            ->title('Gagal menyimpan data')
            ->body('Kolom password tidak boleh kosong.')
            ->danger()
            ->send();

        // Opsional: Lempar Exception untuk menghentikan proses simpan
        throw ValidationException::withMessages([
            'password' => 'Kolom password wajib diisi.',
        ]);
    }

    return $data;
}

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
        Forms\Components\TextInput::make('name')
            ->label('Name')
            ->required()
            ->maxLength(255),

        Forms\Components\TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->unique(ignoreRecord: true),

        Forms\Components\TextInput::make('password')
            ->label('Password')
            ->password()
            ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\EditRecord)
            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
            ->maxLength(255)
            ->hiddenOn('edit'),

        Forms\Components\TextInput::make('role')
            ->label('Role')
            ->required()
            ->maxLength(255),

        // Forms\Components\Toggle::make('is_active')
        //     ->label('Active')
        //     ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable() // Bisa dicari
                ->sortable(),  // Bisa diurutkan
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->sortable(),
            // Tambahkan kolom lain yang ingin Anda tampilkan
            Tables\Columns\TextColumn::make('role') // Tampilkan kolom role juga
                ->searchable()
                ->sortable(),
            // Tables\Columns\IconColumn::make('is_active') // Tampilkan status aktif (jika ada)
            //     ->boolean(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara default
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

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->isAdmin();
        // return true;
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereRaw('LOWER(role) != ?', ['admin']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
