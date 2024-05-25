<?php

namespace App\services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfService
{
    private Dompdf $domPdf;
    public function __construct(){
        $this->domPdf = new DomPdf();
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Garamond ');
        $this->domPdf->setOptions($pdfOptions);
    }
    public function generatePdfContent($html): string
    {
        $this->domPdf->loadHtml($html);
        $this->domPdf->render();
        return $this->domPdf->output();
    }

}