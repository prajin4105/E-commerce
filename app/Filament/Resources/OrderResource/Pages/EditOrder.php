<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Mail\OrderStatusChanged;
use Illuminate\Support\Facades\Mail;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $oldStatus = $record->status;
        $updated = $record->update($data);
        $newStatus = $data['status'] ?? $record->status;

        if ($oldStatus !== $newStatus) {
            // Custom message for each status
            $statusMessages = [
                'placed' => 'Your order has been placed successfully.',
                'on_the_way' => 'Your order is on the way.',
                'delivered' => 'Your order has been delivered.',
                'return_requested' => 'Your request for return has been received.',
                'return_approved' => 'Your return request has been approved.',
                'returned' => 'Your order has been returned successfully.',
                'cancelled' => 'Your order has been cancelled.',
            ];
            $message = $statusMessages[$newStatus] ?? ('Order status updated to ' . ucfirst(str_replace('_', ' ', $newStatus)) . '.');
            Mail::to($record->email)->send(new OrderStatusChanged($record, $message));
        }

        return $record;
    }
} 