<?php

namespace App\Http\Controllers;

use App\Models\Catalogue;
use App\Models\CatalogueFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatalogueController extends Controller
{
    public function apiIndex()
    {
        $catalogues = Catalogue::with('latestFile')->get()->map(function ($catalogue) {
            return [
                'id' => $catalogue->id,
                'name' => $catalogue->name,
                'logo' => $catalogue->logo,
                'latest_file' => $catalogue->latestFile ? [
                    'id' => $catalogue->latestFile->id,
                    'file_path' => $catalogue->latestFile->file_path,
                    'status' => $catalogue->latestFile->status,
                    'uploaded_at' => optional($catalogue->latestFile->created_at)->toDateTimeString(),
                ] : null,
            ];
        });

        return response()->json(['data' => $catalogues]);
    }

    public function apiLatest(Catalogue $catalogue)
    {
        $catalogue->load('latestFile');

        if (!$catalogue->latestFile) {
            return response()->json([
                'message' => 'No visible file available for this catalogue.'
            ], 404);
        }

        return response()->json([
            'catalogue' => [
                'id' => $catalogue->id,
                'name' => $catalogue->name,
            ],
            'file' => [
                'id' => $catalogue->latestFile->id,
                'file_path' => $catalogue->latestFile->file_path,
                'status' => $catalogue->latestFile->status,
                'uploaded_at' => optional($catalogue->latestFile->created_at)->toDateTimeString(),
            ],
        ]);
    }

    public function apiOpen(Catalogue $catalogue)
    {
        $catalogue->load('latestFile');
        if (!$catalogue->latestFile) {
            return response()->json([
                'message' => 'No visible file available for this catalogue.'
            ], 404);
        }

        return redirect()->to($catalogue->latestFile->file_path);
    }

    public function redirect()
    {
        return redirect()->route('login');
    }

    public function catalogue()
    {
        $catalogues = Catalogue::with('latestFile')->get();

        return view('frontend.catalogue', compact('catalogues'));
    }

    public function index()
    {
        $catalogues = Catalogue::all();
        return view('catalogue.index', compact('catalogues'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:catalogues,name',
            'logo' => 'required|image|max:4048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();

            // Store in storage/app/public/logo
            $logo->storeAs('public/logo', $logoName);

            // Save path for Blade asset()
            $logoPath = 'storage/logo/' . $logoName;
        }

        Catalogue::create([
            'name' => $request->name,
            'logo' => $logoPath,
        ]);

        return redirect()->back()->with('success', 'Catalogue added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $catalogue = Catalogue::findOrFail($id);
        return view('catalogue.show', compact('catalogue'));
    }


    public function storeFile(Request $request, $catalogueId)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:51200', // 50MB
            'user_id' => 'required|string'
        ]);

        $catalogue = Catalogue::findOrFail($catalogueId);

        // Expire existing visible file
        CatalogueFile::where('catalogue_id', $catalogueId)
            ->where('status', 'visible')
            ->update(['status' => 'expired']);

        $filePath = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Store in storage/app/public/catalogue_files
            $file->storeAs('public/catalogue_files', $fileName);

            // Save path for Blade asset()
            $filePath = 'storage/catalogue_files/' . $fileName;
        }

        CatalogueFile::create([
            'catalogue_id' => $catalogueId,
            'file_path' => $filePath,
            'status' => 'visible',
            'user_id' => $request->user_id
        ]);

        return redirect()->back()->with('success', 'New catalogue file uploaded.');
    }

    public function destroy($id)
    {
        $catalogue = Catalogue::findOrFail($id);

        if ($catalogue->logo && file_exists(public_path($catalogue->logo))) {
            unlink(public_path($catalogue->logo));
        }

        $catalogue->delete();

        return redirect()->back()->with('success', 'Catalogue deleted successfully.');
    }
}
