<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NewsImageUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'files.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $uploadedFile = Arr::first(Arr::flatten($request->allFiles()));

        abort_unless($uploadedFile !== null, 422, 'Tidak ada gambar yang diunggah.');

        $originalName = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = Str::of($originalName)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '_')
            ->trim('_')
            ->value();

        $filename = now()->format('YmdHis').'_'.($safeName !== '' ? $safeName : 'gambar').'_'.Str::lower(Str::random(6)).'.'.$uploadedFile->getClientOriginalExtension();
        $path = $uploadedFile->storeAs('berita/'.now()->format('Y/m'), $filename, 'public');

        return response()->json([
            'files' => [asset('storage/'.$path)],
            'path' => '',
            'baseurl' => '',
            'error' => 0,
            'msg' => 'Gambar berhasil diunggah.',
        ]);
    }
}
