<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Notifications\Notification;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?string $navigationLabel = 'Generate Reports';
    protected static ?string $modelLabel = 'Report';
    protected static ?string $pluralModelLabel = 'Reports';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('report_type')
                    ->label('Report Type')
                    ->options([
                        'orders' => 'Orders Report',
                        'sales' => 'Sales Summary',
                        'products' => 'Products Report',
                        'users' => 'Users Report',
                        'payments' => 'Payments Report',
                    ])
                    ->required()
                    ->default('orders'),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Start Date')
                    ->required()
                    ->default(now()->subMonth()->format('Y-m-d')),        
                Forms\Components\DatePicker::make('end_date')
                    ->label('End Date')
                    ->required()
                    ->default(now()->format('Y-m-d')),
                Forms\Components\Select::make('format')
                    ->label('Export Format')
                    ->options([
                        'csv' => 'CSV',
                        'excel' => 'Excel (if available)',
                        'pdf' => 'PDF',
                    ])
                    ->required()
                    ->default('csv'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('report_type')
                    ->label('Report Type')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->date(),
                Tables\Columns\TextColumn::make('format')
                    ->label('Format')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Generated At')
                    ->dateTime(),
            ])
            
            ->actions([
                Tables\Actions\Action::make('generate')
                    ->label('Generate Report')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        return static::generateReport($record);
                    }),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
            // Note: headerActions block is REMOVED
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReports::route('/'),
            'create' => Pages\CreateReport::route('/create'),
        ];
    }

    public static function generateReport($record)
    {
        try {
            $startDate = Carbon::parse($record->start_date)->startOfDay();
            $endDate = Carbon::parse($record->end_date)->endOfDay();

            switch ($record->report_type) {
                case 'orders':
                    $data = static::generateOrdersReport($startDate, $endDate);
                    break;
                case 'sales':
                    $data = static::generateSalesReport($startDate, $endDate);
                    break;
                case 'products':
                    $data = static::generateProductsReport($startDate, $endDate);
                    break;
                case 'users':
                    $data = static::generateUsersReport($startDate, $endDate);
                    break;
                case 'payments':
                    $data = static::generatePaymentsReport($startDate, $endDate);
                    break;
                default:
                    throw new \Exception('Invalid report type');
            }

            if (($record->format ?? 'csv') === 'pdf') {
                // For PDF, convert CSV to array
                $lines = explode("\r\n", trim($data));
                $headers = isset($lines[0]) ? str_getcsv($lines[0]) : [];
                $rows = [];
                foreach (array_slice($lines, 1) as $line) {
                    if (trim($line) === '') continue;
                    $rows[] = array_combine($headers, str_getcsv($line));
                }
                $pdf = Pdf::loadView('pdf.report', [
                    'type' => $record->report_type,
                    'data' => $rows,
                ]);
                $filename = $record->report_type . '_report_' . now()->format('Ymd_His') . '.pdf';
                Notification::make()
                    ->title('Report generated successfully!')
                    ->body('Your PDF report is ready for download.')
                    ->success()
                    ->send();
                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->output();
                }, $filename);
            }

            // Default: CSV/Excel
            $filename = $record->report_type . '_report_' . now()->format('Ymd_His') . '.csv';
            Storage::disk('local')->put($filename, $data);

            Notification::make()
                ->title('Report generated successfully!')
                ->body('Your report is ready for download.')
                ->success()
                ->send();

            return response()->download(storage_path("app/{$filename}"))->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Notification::make()
                ->title('Error generating report')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    // --- CSV Export Functions ---
    private static function generateOrdersReport($startDate, $endDate)
    {
        $orders = Order::with(['user', 'items.product', 'coupon'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $header = [
            "Order ID",
            "Customer",
            "Email",
            "Phone",
            "Status",
            "Order Date",
            "Product",
            "Quantity",
            "Price",
            "Total",
            "Subtotal",
            "Discount",
            "Coupon",
            "Final Total",
            "Shipping Address",
            "Billing Address",
        ];
        $rows = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $rows[] = [
                    $order->order_number ?? $order->id,
                    $order->user ? $order->user->name : 'Guest',
                    $order->email,
                    $order->phone_number,
                    match($order->status) {
                        'placed' => 'Order Placed',
                        'on_the_way' => 'On the Way',
                        'delivered' => 'Delivered',
                        'return_requested' => 'Return Requested',
                        'return_approved' => 'Return Approved',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($order->status),
                    },
                    $order->created_at->format('d-m-Y H:i'),
                    $item->product->name ?? 'Product Not Found',
                    $item->quantity,
                    number_format($item->price, 2),
                    number_format($item->price * $item->quantity, 2),
                    number_format($order->total_amount, 2),
                    number_format($order->discount_amount ?? 0, 2),
                    $order->coupon ? $order->coupon->code : '',
                    number_format($order->final_amount, 2),
                    static::escapeForCsv($order->shipping_address),
                    static::escapeForCsv($order->billing_address),
                ];
            }
        }
        return static::arrayToCsv(array_merge([$header], $rows));
    }

    private static function generateSalesReport($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $header = [
            "Report Period",
            "Total Sales (INR)",
            "Total Orders",
            "Average Order Value (INR)",
            "Total Discount (INR)"
        ];
        $totalSales = $orders->sum('final_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;
        $row = [
            $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'),
            number_format($totalSales, 2),
            $totalOrders,
            number_format($averageOrderValue, 2),
            number_format($orders->sum('discount_amount') ?? 0, 2)
        ];
        return static::arrayToCsv([$header, $row]);
    }

    private static function generateProductsReport($startDate, $endDate)
    {
        $products = Product::with(['category', 'subcategory'])
            ->where('is_active', true)
            ->get();

        $header = [
            "Product ID",
            "Name",
            "Category",
            "Subcategory",
            "Price (INR)",
            "Stock",
            "Status",
            "Created Date"
        ];
        $rows = [];
        foreach ($products as $product) {
            $rows[] = [
                $product->id,
                $product->name,
                $product->category ? $product->category->name : 'N/A',
                $product->subcategory ? $product->subcategory->name : 'N/A',
                number_format($product->price, 2),
                $product->stock,
                $product->is_active ? 'Active' : 'Inactive',
                $product->created_at->format('Y-m-d')
            ];
        }
        return static::arrayToCsv(array_merge([$header], $rows));
    }

    private static function generateUsersReport($startDate, $endDate)
    {
        $users = User::whereBetween('created_at', [$startDate, $endDate])->get();

        $header = [
            "User ID",
            "Name",
            "Email",
            "Phone",
            "Registration Date",
            "Total Orders",
            "Total Spent (INR)"
        ];
        $rows = [];
        foreach ($users as $user) {
            $totalOrders = $user->orders()->count();
            $totalSpent = $user->orders()->sum('final_amount');
            $rows[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->phone ?? 'N/A',
                $user->created_at->format('Y-m-d'),
                $totalOrders,
                number_format($totalSpent, 2)
            ];
        }
        return static::arrayToCsv(array_merge([$header], $rows));
    }

    private static function generatePaymentsReport($startDate, $endDate)
    {
        $payments = Payment::with(['order'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $header = [
            "Payment ID",
            "Order ID",
            "Amount (INR)",
            "Payment Method",
            "Status",
            "Transaction ID",
            "Payment Date"
        ];
        $rows = [];
        foreach ($payments as $payment) {
            $rows[] = [
                $payment->id,
                $payment->order_id,
                number_format($payment->amount, 2),
                $payment->payment_method,
                $payment->status,
                $payment->transaction_id ?? 'N/A',
                $payment->created_at->format('Y-m-d H:i:s')
            ];
        }
        return static::arrayToCsv(array_merge([$header], $rows));
    }

    // --- Helper Functions ---
    private static function escapeForCsv($value)
    {
        $escaped = str_replace('"', '""', $value ?? '');
        $escaped = str_replace(["\r", "\n"], [' ', ' '], $escaped); // Remove newlines
        return $escaped;
    }

    private static function arrayToCsv($data)
    {
        $lines = [];
        foreach ($data as $row) {
            $fields = array_map(fn($v) => '"' . static::escapeForCsv($v) . '"', $row);
            $lines[] = implode(',', $fields);
        }
        return implode("\r\n", $lines) . "\r\n";
    }
}
