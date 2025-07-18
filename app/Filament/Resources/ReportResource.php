<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReportResource\Pages;
use App\Filament\Resources\ReportResource\RelationManagers;
use App\Models\Report;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->filters([
                Tables\Filters\SelectFilter::make('report_type')
                    ->options([
                        'orders' => 'Orders Report',
                        'sales' => 'Sales Summary',
                        'products' => 'Products Report',
                        'users' => 'Users Report',
                        'payments' => 'Payments Report',
                    ]),
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
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Report')
                    ->icon('heroicon-o-plus'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
                    $data = static::generateOrdersReport($startDate, $endDate, $record->format ?? 'csv');
                    break;
                case 'sales':
                    $data = static::generateSalesReport($startDate, $endDate, $record->format ?? 'csv');
                    break;
                case 'products':
                    $data = static::generateProductsReport($startDate, $endDate, $record->format ?? 'csv');
                    break;
                case 'users':
                    $data = static::generateUsersReport($startDate, $endDate, $record->format ?? 'csv');
                    break;
                case 'payments':
                    $data = static::generatePaymentsReport($startDate, $endDate, $record->format ?? 'csv');
                    break;
                default:
                    throw new \Exception('Invalid report type');
            }

            if (($record->format ?? 'csv') === 'pdf') {
                // $data should be an array of rows for PDF
                if (is_string($data)) {
                    // If data is CSV string, convert to array
                    $lines = explode("\n", trim($data));
                    $headers = isset($lines[0]) ? str_getcsv($lines[0]) : [];
                    $rows = [];
                    foreach (array_slice($lines, 1) as $line) {
                        if (trim($line) === '') continue;
                        $rows[] = array_combine($headers, str_getcsv($line));
                    }
                    $dataForPdf = $rows;
                } else {
                    $dataForPdf = $data;
                }
                $pdf = Pdf::loadView('pdf.report', [
                    'type' => $record->report_type,
                    'data' => $dataForPdf,
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

    private static function generateOrdersReport($startDate, $endDate)
    {
        $orders = Order::with(['user', 'items.product', 'coupon'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $csv = "Order ID,Customer,Email,Phone,Status,Order Date,Product,Quantity,Price,Total,Subtotal,Discount,Coupon,Final Total,Shipping Address,Billing Address\n";
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $csv .= sprintf(
                    '"%s","%s","%s","%s","%s","%s","%s",%d,%s,%s,%s,%s,%s,%s,"%s","%s"\n',
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
                    $order->created_at->format('Y-m-d H:i:s'),
                    $item->product->name ?? 'Product Not Found',
                    $item->quantity,
                    number_format($item->price, 2),
                    number_format($item->price * $item->quantity, 2),
                    number_format($order->total_amount, 2),
                    number_format($order->discount_amount ?? 0, 2),
                    $order->coupon ? $order->coupon->code : '',
                    number_format($order->final_amount, 2),
                    $order->shipping_address,
                    $order->billing_address
                );
            }
        }
        return $csv;
    }

    private static function generateSalesReport($startDate, $endDate)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalSales = $orders->sum('final_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        $csv = "Report Period,Total Sales (INR),Total Orders,Average Order Value (INR),Total Discount (INR)\n";
        $csv .= sprintf(
            "%s to %s,%.2f,%d,%.2f,%.2f\n",
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d'),
            $totalSales,
            $totalOrders,
            $averageOrderValue,
            $orders->sum('discount_amount') ?? 0
        );

        return $csv;
    }

    private static function generateProductsReport($startDate, $endDate)
    {
        $products = Product::with(['category', 'subcategory'])
            ->where('is_active', true)
            ->get();

        $csv = "Product ID,Name,Category,Subcategory,Price (INR),Stock,Status,Created Date\n";
        
        foreach ($products as $product) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%.2f,%d,%s,%s\n",
                $product->id,
                $product->name,
                $product->category ? $product->category->name : 'N/A',
                $product->subcategory ? $product->subcategory->name : 'N/A',
                $product->price,
                $product->stock,
                $product->is_active ? 'Active' : 'Inactive',
                $product->created_at->format('Y-m-d')
            );
        }

        return $csv;
    }

    private static function generateUsersReport($startDate, $endDate)
    {
        $users = User::whereBetween('created_at', [$startDate, $endDate])->get();

        $csv = "User ID,Name,Email,Phone,Registration Date,Total Orders,Total Spent (INR)\n";
        
        foreach ($users as $user) {
            $totalOrders = $user->orders()->count();
            $totalSpent = $user->orders()->sum('final_amount');
            
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%d,%.2f\n",
                $user->id,
                $user->name,
                $user->email,
                $user->phone ?? 'N/A',
                $user->created_at->format('Y-m-d'),
                $totalOrders,
                $totalSpent
            );
        }

        return $csv;
    }

    private static function generatePaymentsReport($startDate, $endDate)
    {
        $payments = Payment::with(['order'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $csv = "Payment ID,Order ID,Amount (INR),Payment Method,Status,Transaction ID,Payment Date\n";
        
        foreach ($payments as $payment) {
            $csv .= sprintf(
                "%s,%s,%.2f,%s,%s,%s,%s\n",
                $payment->id,
                $payment->order_id,
                $payment->amount,
                $payment->payment_method,
                $payment->status,
                $payment->transaction_id ?? 'N/A',
                $payment->created_at->format('Y-m-d H:i:s')
            );
        }

        return $csv;
    }
}
