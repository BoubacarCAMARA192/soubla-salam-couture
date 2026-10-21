<?php

namespace App\Http\Controllers\Tailleur;

use Symfony\Component\HttpFoundation\StreamedResponse;

/** Utilitaire d'export CSV pour les sauvegardes de données (ADM-07). */
class ExportsCsv
{
    /** @param iterable<array> $rows */
    public static function download(array $headers, iterable $rows, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 pour Excel
            fputcsv($out, $headers, ';');
            foreach ($rows as $row) {
                fputcsv($out, $row, ';');
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
