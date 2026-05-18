<?php

namespace App\Http\Controllers\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\SpmbRegistration;
use Illuminate\Contracts\View\View;

class SpmbRegistrationDetailController extends Controller
{
    public function __invoke(string $registrationNumber): View
    {
        $registration = SpmbRegistration::query()
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('ppdb.detail', [
            'registration' => $registration,
        ]);
    }
}
