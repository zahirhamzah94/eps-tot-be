<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class PdfExportService
{
    /**
     * Generate PDF from HTML template.
     */
    public function generateFromTemplate(string $template, array $data = []): mixed
    {
        try {
            $pdf = Pdf::loadView($template, $data);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'margin_top' => 10,
                'margin_right' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
            ]);

            return $pdf;
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate styled audit logs PDF.
     */
    public function generateAuditLogsPdf(array $logs, array $filters = [])
    {
        return $this->generateFromTemplate('exports.audit-logs-pdf', [
            'logs' => $logs,
            'filters' => $filters,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Generate styled users report PDF.
     */
    public function generateUsersPdf(array $users)
    {
        return $this->generateFromTemplate('exports.users-pdf', [
            'users' => $users,
            'generatedAt' => now()->format('Y-m-d H:i:s'),
            'totalUsers' => count($users),
        ]);
    }

    /**
     * Download PDF file.
     */
    public function download($pdf, string $filename)
    {
        if (!$pdf) {
            return null;
        }

        return $pdf->download($filename);
    }

    /**
     * Stream PDF to browser.
     */
    public function stream($pdf)
    {
        if (!$pdf) {
            return null;
        }

        return $pdf->stream();
    }
}
