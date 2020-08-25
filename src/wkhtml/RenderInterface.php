<?php

namespace App\wkhtml;

use Symfony\Component\HttpFoundation\Response;

interface RenderInterface
{
    /**
     * Generation of an image or PDF file based on an HTML template.
     *
     * @param string $html HTML template.
     * @param string $output name of file.
     *
     * @return Response
     */
    public function render(string $html, string $output): Response;

    /**
     * Generation of an image or PDF file based on an URL by website.
     *
     * @param string $url url website.
     * @param string $output name of file.
     *
     * @return Response
     */
    public function url(string $url, string $output): Response;
}
