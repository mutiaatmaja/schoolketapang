<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\SpmbRegistration;
use Linkxtr\QrCode\Facades\QrCode;
use Spatie\LaravelPdf\Enums\Format;

use function Spatie\LaravelPdf\Support\pdf;

class SpmbRegistrationRecapPdfController extends Controller
{
    public function __invoke(string $registrationNumber)
    {
        $registration = SpmbRegistration::query()
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        $detailUrl = route('ppdb.detail', [
            'registrationNumber' => $registration->registration_number,
        ]);

        $qrCodePng = QrCode::size(150)
            ->format('png')
            ->margin(1)
            ->errorCorrection('H')
            ->generate($detailUrl);

        $qrCodeDataUri = 'data:image/png;base64,'.base64_encode($qrCodePng);

        return pdf()
            ->driver('dompdf')
            ->format(Format::A4)
            ->view('pdf.ppdb-registration-recap', [
                'registration' => $registration,
                'detailUrl' => $detailUrl,
                'qrCodeDataUri' => $qrCodeDataUri,
            ])
            ->name('rekap-pendaftaran-'.$registration->registration_number.'.pdf');
    }
}
