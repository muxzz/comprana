<?php

namespace App\Listeners;

use App\Events\CreateOrderEvent;
use App\Models\Product;
use Codedge\Fpdf\Fpdf\Fpdf;
use Illuminate\Support\Facades\Storage;

class GenerateInvoiceListener
{
    /**
     * Create the event listener.
     */
    public function __construct(protected Fpdf $pdf)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreateOrderEvent $event): void
    {

        $nameInvoice = "order{$event->order->id}.pdf";

        $total = 0;
        $user = $event->order->user;

        $this->pdf->SetMargins(4, 6, 4);
        $this->pdf->AddPage(size: [80, 258]);

        // Encabezado y datos de la empresa #
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetTextColor(0, 0, 0);
        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', strtoupper('COMPRANA')), 0, 'C', false);
        $this->pdf->SetFont('Arial', '', 9);
        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', 'Direccion comprana'), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', 'Teléfono: 00000000'), 0, 'C', false);

        $this->pdf->Ln(1);
        $this->pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1', '------------------------------------------------------'), 0, 0, 'C');
        $this->pdf->Ln(5);

        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', 'Fecha: '.date('d/m/Y', strtotime('13-09-2022')).' '.date('h:s A')), 0, 'C', false);
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', strtoupper("Ticket order{$event->order->id}")), 0, 'C', false);
        $this->pdf->SetFont('Arial', '', 9);

        $this->pdf->Ln(1);
        $this->pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1', '------------------------------------------------------'), 0, 0, 'C');
        $this->pdf->Ln(5);

        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', "Cliente: {$user->name}"), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', "Teléfono: {$event->order->phone}"), 0, 'C', false);
        $this->pdf->MultiCell(0, 5, iconv('UTF-8', 'ISO-8859-1', "Dirección: {$event->order->address}"), 0, 'C', false);

        $this->pdf->Ln(1);
        $this->pdf->Cell(0, 5, iconv('UTF-8', 'ISO-8859-1', '-------------------------------------------------------------------'), 0, 0, 'C');
        $this->pdf->Ln(3);

        // Tabla de productos #
        $this->pdf->Cell(10, 5, iconv('UTF-8', 'ISO-8859-1', 'Cant.'), 0, 0, 'C');
        $this->pdf->Cell(19, 5, iconv('UTF-8', 'ISO-8859-1', 'Precio'), 0, 0, 'C');
        $this->pdf->Cell(28, 5, iconv('UTF-8', 'ISO-8859-1', 'Total'), 0, 0, 'C');

        $this->pdf->Ln(3);
        $this->pdf->Cell(72, 5, iconv('UTF-8', 'ISO-8859-1', '-------------------------------------------------------------------'), 0, 0, 'C');
        $this->pdf->Ln(3);

        /* ----------  Detalles de la tabla  ---------- */
        foreach ($event->products as $product) {
            $this->pdf->MultiCell(0, 4, iconv('UTF-8', 'ISO-8859-1', $product['title']), 0, 'C', false);
            $this->pdf->Cell(10, 4, iconv('UTF-8', 'ISO-8859-1', $product['quantity']), 0, 0, 'C');
            $this->pdf->Cell(19, 4, iconv('UTF-8', 'ISO-8859-1', '$'.$product['unit_price']), 0, 0, 'C');
            $this->pdf->Cell(28, 4, iconv('UTF-8', 'ISO-8859-1', '$'.$product['unit_price'] * $product['quantity']), 0, 0, 'C');
            $this->pdf->Ln(4);
            /* ----------  Fin Detalles de la tabla  ---------- */
            $total += $product['unit_price'] * $product['quantity'];
        }

        $this->pdf->Cell(72, 5, iconv('UTF-8', 'ISO-8859-1', '-------------------------------------------------------------------'), 0, 0, 'C');

        $this->pdf->Ln(5);

        $this->pdf->Cell(18, 5, iconv('UTF-8', 'ISO-8859-1', ''), 0, 0, 'C');
        $this->pdf->Cell(22, 5, iconv('UTF-8', 'ISO-8859-1', 'TOTAL PAGADO'), 0, 0, 'C');
        $this->pdf->Cell(32, 5, iconv('UTF-8', 'ISO-8859-1', "$ {$total}"), 0, 0, 'C');

        $this->pdf->Ln(10);

        $this->pdf->SetFont('Arial', 'B', 9);
        $this->pdf->Cell(0, 7, iconv('UTF-8', 'ISO-8859-1','Gracias por su compra'), '', 0, 'C');

        $this->pdf->Ln(9);

        // Nombre del archivo PDF #
        Storage::disk('invoices')->put($nameInvoice, $this->pdf->Output('S'));

        $event->order->update([
            'invoice' => $nameInvoice,
            'total' => $total,
        ]);

        $this->pdf->close();

        foreach ($event->products as $product) {
            $p = Product::select('id','stock')->firstWhere('name', $product['title']);

            $p->update([
                'stock' => $p->stock -= $product['quantity'],
            ]);
        }

    }
}
