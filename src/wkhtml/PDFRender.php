<?php


namespace App\wkhtml;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;

class PDFRender implements RenderInterface
{
    private Pdf $pdf;

    public function __construct(Pdf $pdf)
    {
        $this->pdf = $pdf;
    }

    public function render(string $html, string $output): Response
    {
        return new PdfResponse(
            $this->pdf->getOutputFromHtml($html),
            $output
        );
    }

    public function url(string $url, string $output): Response
    {
        return new PdfResponse(
            $this->pdf->getOutput($url),
            $output
        );
    }
}
