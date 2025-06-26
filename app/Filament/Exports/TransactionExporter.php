<?php

namespace App\Filament\Exports;

use App\Models\Transaction;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TransactionExporter extends Exporter
{
    protected static ?string $model = Transaction::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            
            ExportColumn::make('key')
                ->label('Account Key'),
            
            ExportColumn::make('receipt')
                ->label('Receipt'),
            
            ExportColumn::make('value')
                ->label('Value')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            
            ExportColumn::make('type')
                ->label('Type'),
            
            ExportColumn::make('transaction_date')
                ->label('Transaction Date')
                ->formatStateUsing(fn ($state) => $state ? date('d/m/Y H:i:s', strtotime($state)) : ''),
            
            ExportColumn::make('description')
                ->label('Description'),
            
            ExportColumn::make('user.name')
                ->label('Created By'),
            
            ExportColumn::make('created_at')
                ->label('Created At')
                ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i:s')),
            
            ExportColumn::make('updated_at')
                ->label('Updated At')
                ->formatStateUsing(fn ($state) => $state?->format('d/m/Y H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export transaksi Anda telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil di-export.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal di-export.';
        }

        return $body;
    }

    public function getFileName(Export $export): string
    {
        return "transactions-export-" . now()->format('Y-m-d-H-i-s');
    }

    public static function modifyQuery(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->with(['user']); // Load user relationship untuk performa
    }
}