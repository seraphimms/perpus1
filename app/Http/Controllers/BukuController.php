<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                    ->orWhere('penulis', 'like', '%' . $request->search . '%')
                    ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->latest()->paginate(10)->withQueryString();
        $kategori = Kategori::all();

        return view('buku.index', compact('buku', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:100',
            'penerbit'    => 'required|string|max:100',
            'tahun'       => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn'        => 'nullable|string|max:20|unique:buku,isbn',
            'jumlah'      => 'required|integer|min:1',
            'kategori_id' => 'required|exists:kategori,id',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        $data = $request->except('cover');

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
    {
        $kategori = Kategori::all();
        return view('buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'       => 'required|string|max:255',
            'penulis'     => 'required|string|max:100',
            'penerbit'    => 'required|string|max:100',
            'tahun'       => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'isbn'        => 'nullable|string|max:20|unique:buku,isbn,' . $buku->id,
            'jumlah'      => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'cover'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'deskripsi'   => 'nullable|string',
        ]);

        $data = $request->except('cover');

        if ($request->hasFile('cover')) {
            // Hapus cover lama kalau ada
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->detailPinjam()->whereHas('pinjam', fn($q) => $q->where('status', 'pinjam'))->exists()) {
            return back()->with('error', 'Buku tidak bisa dihapus karena sedang dipinjam.');
        }

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();
        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
    }
}