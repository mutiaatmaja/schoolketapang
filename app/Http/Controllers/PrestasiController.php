<?php

namespace App\Http\Controllers;

use App\Models\SchoolAchievement;
use App\Models\SchoolInformation;
use Illuminate\Contracts\View\View;

class PrestasiController extends Controller
{
    public function index(): View
    {
        $schoolInformation = SchoolInformation::query()->where('label', 'Nama Sekolah')->value('value');

        return view('prestasi.index', [
            'schoolName' => $schoolInformation,
            'achievementCount' => SchoolAchievement::query()->count('id'),
            'achievements' => SchoolAchievement::query()
                ->ordered()
                ->paginate(12),
        ]);
    }
}
