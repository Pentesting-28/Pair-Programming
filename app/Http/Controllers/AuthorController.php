<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Country;
use App\Services\AuthorService;
use App\Http\Requests\Author\StoreRequest;
use App\Http\Requests\Author\UpdateRequest;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorService $authorService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::with('country')->paginate(
            perPage: 10,
            columns: ['id', 'name', 'last_name', 'birth_date', 'country_id', 'photo_path']
        );
        return view('mvc.authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::orderBy('common_name')->get([
            'id',
            'common_name',
            'flag_svg_path'
        ]);
        return view('mvc.authors.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        // El controlador ya no sabe nada de imágenes ni de DTOs.
        // Solo delega al servicio especializado.
        $this->authorService->store($request);

        return redirect()->route('mvc.authors.index')
            ->with('success', 'Autor creado con éxito.');
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
        $countries = Country::orderBy('common_name')->get(['id', 'common_name']);
        return view('mvc.authors.edit', compact('author', 'countries'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Author $author)
    {
        $this->authorService->update(
            $author, 
            $request->validated(), 
            $request->file('photo_path')
        );

        return redirect()->route('mvc.authors.index')
            ->with('success', 'Autor actualizado con éxito.');
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
