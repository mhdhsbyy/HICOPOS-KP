<?php

namespace App\Support;

use App\Models\Transaction;
use Dompdf\Dompdf;

class ReceiptBuilder
{
    public static function build(Transaction $transaction): array
    {
        $transaction->loadMissing('items.menu.category', 'user', 'promos');

        $itemDiscountsTotal = $transaction->items->sum(fn ($i) => $i->discount);

        return [
            'code' => $transaction->code,
            'id' => $transaction->id,
            'created_at' => now()->format('d M Y H:i'),
            'cashier' => $transaction->user->username,
            'items' => $transaction->items->map(fn ($i) => [
                'name' => $i->menu->name ?? 'Menu Dihapus',
                'quantity' => $i->quantity,
                'price' => $i->price,
                'discount' => $i->discount,
                'total' => $i->total,
            ])->toArray(),
            'promos' => $transaction->promos->map(fn ($p) => [
                'name' => $p->name,
                'discount_amount' => (float) $p->pivot->discount_amount,
            ])->values()->toArray(),
            'subtotal' => $transaction->subtotal,
            'item_discounts_total' => $itemDiscountsTotal,
            'promo_discount' => $transaction->promo_discount,
            'manual_discount' => $transaction->discount,
            'total' => $transaction->total,
            'amount_paid' => $transaction->amount_paid,
            'change' => $transaction->change,
            'payment_method' => $transaction->payment_method,
        ];
    }

    /**
     * Build a PDF response for a receipt. Paper is 80mm wide with a height that
     * auto-fits the content (DomPDF does not support CSS `size: Xmm auto`, so
     * the smallest single-page height is found by testing). Returns an inline
     * response so the browser shows a PDF preview instead of downloading.
     */
    public static function toResponse(Transaction $transaction)
    {
        $receipt = self::build($transaction);
        $html = view('pdf.receipt', compact('receipt'))->render();

        $widthPt = 80 / 25.4 * 72;

        // Binary search the smallest page height (pt) that fits the content
        // on a single page. DomPDF has no content-height API, so we use the
        // page count as the fit signal.
        $min = 120;   // ~42mm
        $max = 1200;  // ~423mm
        $heightPt = $max;

        while ($max - $min > 2) {
            $mid = (int) (($min + $max) / 2);

            $dompdf = new Dompdf;
            $dompdf->setPaper([0, 0, $widthPt, $mid]);
            $dompdf->loadHtml($html);
            $dompdf->render();

            if ($dompdf->get_canvas()->get_page_count() > 1) {
                $min = $mid; // doesn't fit yet, needs to be taller
            } else {
                $max = $mid; // fits, try a shorter page
            }
        }

        $heightPt = $max;

        $dompdf = new Dompdf;
        $dompdf->setPaper([0, 0, $widthPt, $heightPt]);
        $dompdf->loadHtml($html);
        $dompdf->render();

        $filename = 'struk-transaksi-'.$transaction->id.'.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
            'Cache-Control' => 'public, must-revalidate, max-age=0',
        ]);
    }
}
