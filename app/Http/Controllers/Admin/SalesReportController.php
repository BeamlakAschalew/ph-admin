<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SalesReportController extends Controller implements FromCollection, WithColumnFormatting, WithHeadings, WithMapping
{
    public function index(Request $request)
    {
        $query = Order::with([
            'consumer' => function ($query) {
                $query->withTrashed()->with('subcity');
            },
            'items.product.unit',
            'customItems.unit',
        ]);

        if ($request->has('fromDate') && $request->fromDate) {
            $query->whereDate('created_at', '>=', $request->fromDate);
        }

        if ($request->has('toDate') && $request->toDate) {
            $query->whereDate('created_at', '<=', $request->toDate);
        }

        return inertia('Admin/Sales', [
            'orders' => $query->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString(),
            'filters' => $request->only(['fromDate', 'toDate']),
        ]);
    }

    public function export(Request $request)
    {
        $this->request = $request; // Store request for use in other methods
        $fromDate = $request->input('fromDate', 'start');
        $toDate = $request->input('toDate', 'end');
        $filename = "sales_report_{$fromDate}_to_{$toDate}.xlsx";

        return Excel::download($this, $filename);
    }

    public function collection()
    {
        $query = Order::with([
            'consumer' => function ($query) {
                $query->withTrashed()->with('subcity');
            },
            'items.product.unit',
            'customItems.unit',
        ]);

        if ($this->request->has('fromDate') && $this->request->fromDate) {
            $query->whereDate('created_at', '>=', $this->request->fromDate);
        }

        if ($this->request->has('toDate') && $this->request->toDate) {
            $query->whereDate('created_at', '<=', $this->request->toDate);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Consumer Name',
            'Institution Name',
            'Subcity',
            'Woreda',
            'Special Place',
            'Primary Phone',
            'Secondary Phone',
            'Ordered At',
            'Status',
            'Product Name',
            'Unit',
            'Quantity',
            'Is Custom Item',
        ];
    }

    public function map($order): array
    {
        $rows = [];
        $commonData = [
            $order->id,
            $order->consumer ? $order->consumer->first_name.' '.$order->consumer->last_name : 'N/A',
            $order->consumer ? $order->consumer->institution_name : 'N/A',
            $order->consumer && $order->consumer->subcity ? $order->consumer->subcity->subcity_name : 'N/A',
            $order->consumer ? $order->consumer->woreda : 'N/A',
            $order->consumer ? $order->consumer->special_place : 'N/A',
            $order->consumer ? "'".'0'.$order->consumer->primary_phone : 'N/A',
            $order->consumer ? "'".'0'.$order->consumer->secondary_phone : 'N/A',
            Date::dateTimeToExcel($order->created_at), // This already includes time
            $order->status,
        ];

        if ($order->items->isNotEmpty()) {
            foreach ($order->items as $item) {
                $rows[] = array_merge($commonData, [
                    $item->product ? $item->product->product_name : 'N/A',
                    $item->product && $item->product->unit ? $item->product->unit->unit_name : 'N/A',
                    $item->quantity,
                    'No',
                ]);
            }
        }

        if ($order->customItems->isNotEmpty()) {
            foreach ($order->customItems as $customItem) {
                $rows[] = array_merge($commonData, [
                    $customItem->product_name,
                    $customItem->unit ? $customItem->unit->unit_name : 'N/A',
                    $customItem->quantity,
                    'Yes',
                ]);
            }
        }

        // If an order has no items or custom items, add a row with N/A for item details
        if (empty($rows)) {
            $rows[] = array_merge($commonData, ['N/A', 'N/A', 'N/A', 'N/A']);
        }

        return $rows;
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_TEXT, // Primary Phone
            'H' => NumberFormat::FORMAT_TEXT, // Secondary Phone
            'I' => NumberFormat::FORMAT_DATE_DATETIME, // Ordered At (Date and Time)
        ];
    }

    // Add a private property to hold the request for collection method
    private $request;
}
