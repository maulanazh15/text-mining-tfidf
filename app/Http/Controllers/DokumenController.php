<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Http\Requests\StoreDokumenRequest;
use App\Http\Requests\UpdateDokumenRequest;
use App\Models\TFIDF;
use App\Models\VectorModel;
use Illuminate\Http\Request;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokumen = Dokumen::all();
        return view('dokumen.index', compact('dokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dokumen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDokumenRequest $request)
    {
        $data = $request->validated();

        Dokumen::create($data);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $dokumen = Dokumen::where('id', $id)->first();
        return view('dokumen.show', compact('dokumen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $dokumen = Dokumen::where('id', $id)->first();
        return view('dokumen.edit', compact('dokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDokumenRequest $request, int $id)
    {
        $data = $request->validated();
        $dokumen = Dokumen::where('id', $id)->first();
        $dokumen->update($data);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $dokumen = Dokumen::where('id', $id)->first();

        $dokumen->delete();

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }

    public function showTFIDF() {
        $tfidfs = TFIDF::all();

        return view('perhitungan.tfidf', compact('tfidfs'));
    }

    public function showVectorModel() {
        $vectorModels = VectorModel::all();

        return view('perhitungan.vectormodel', compact('vectorModels'));
    }

}
