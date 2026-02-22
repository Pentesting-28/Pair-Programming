<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::paginate(10);
        return view('mvc.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mvc.authors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        Author::create($validated);

        return redirect()->route('mvc.authors.index')->with('success', 'Autor creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        return view('mvc.authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        return view('mvc.authors.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
        ]);

        $author->update($validated);

        return redirect()->route('mvc.authors.index')->with('success', 'Autor actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('mvc.authors.index')->with('success', 'Autor eliminado con éxito.');
    }
}
