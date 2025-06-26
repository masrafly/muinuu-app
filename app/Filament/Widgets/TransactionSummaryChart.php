<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\DB;

class TransactionSummaryChart extends ChartWidget
{
    protected static ?string $heading = 'Transaction Summary';

    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
        ],
    ];

    protected function getData(): array
    {
        $data = Transaction::select(
            DB::raw('type, SUM(value) AS total'),
        )
        ->groupBy('type')
        ->get();

        $formattedData = [];
        foreach ($data as $item) {
            $formattedData['values'][] = $item->total;
            $formattedData['labels'][] = $item->type;
        }


        return [
            'datasets' => [
                [
                    'data' => isset($formattedData['values']) ? $formattedData['values'] : [],
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.6)', // Merah
                        'rgba(54, 162, 235, 0.6)', // Biru
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => isset($formattedData['labels']) ? $formattedData['labels'] : [],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}