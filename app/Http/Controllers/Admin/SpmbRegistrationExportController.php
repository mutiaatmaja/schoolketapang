<?php

namespace App\Http\Controllers\Admin;

use App\Exports\SpmbRegistrationExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SpmbRegistrationExportController extends Controller
{
    /** @var list<string> */
    private const array ALLOWED_STATUSES = ['submitted', 'verified', 'lulus', 'cadangan', 'ditolak'];

    public function __invoke(string $status): BinaryFileResponse
    {
        if (! in_array($status, self::ALLOWED_STATUSES, strict: true)) {
            abort(404);
        }

        $filename = 'peserta-spmb-'.$status.'-'.now()->format('Ymd-His').'.xlsx';

        return Excel::download(new SpmbRegistrationExport($status), $filename);
    }
}
