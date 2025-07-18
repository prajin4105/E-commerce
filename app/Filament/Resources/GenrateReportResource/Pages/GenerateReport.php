<?php

namespace App\Filament\Resources\GenrateReportResource\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class GenerateReport extends Page
{
    protected static string $view = 'filament.resources.genrate-report-resource.pages.generate-report';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $title = 'Generate Report';

    public $reportType;

    public function mount()
    {
        $this->reportType = 'sales'; // default value
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('reportType')
                ->label('Report Type')
                ->options([
                    'sales' => 'Sales',
                    'orders' => 'Orders',
                    'users' => 'Users',
                ])
                ->required(),
        ];
    }

    public function generateReport()
    {
        // Redirect to controller for PDF generation
        return redirect()->route('admin.reports.generate', ['type' => $this->reportType]);
    }

    protected function getActions(): array
    {
        return [
            Forms\Components\Button::make('Generate PDF')
                ->action('generateReport')
                ->label('Generate PDF')
                ->color('primary'),
        ];
    }
} 