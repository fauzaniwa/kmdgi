<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $query = Faq::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('pertanyaan', 'like', '%' . $request->search . '%')
                  ->orWhere('jawaban', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('kategori') && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        $dataFaqs = $query->latest()->paginate(10)->withQueryString();

        return view('admin.faqs.index', compact('dataFaqs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'is_active'  => 'required|boolean',
        ]);

        Faq::create($request->all());

        return redirect()->back()->with('success', 'Data F&Q berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
            'kategori'   => 'required|string|max:100',
            'is_active'  => 'required|boolean',
        ]);

        $faq->update($request->all());

        return redirect()->back()->with('success', 'Data F&Q berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Faq::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Data F&Q berhasil dihapus permanen!');
    }
}