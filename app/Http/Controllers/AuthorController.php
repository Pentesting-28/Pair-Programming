<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Country;
use App\Services\AuthorService;
use App\Services\FileService;
use App\DTOs\AuthorData;
use App\Http\Requests\Author\StoreRequest;
use App\Http\Requests\Author\UpdateRequest;

class AuthorController extends Controller
{
    public function __construct(
        protected AuthorService $authorService,
        protected FileService $fileService
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
        $countries = Country::orderBy('common_name')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'common_name' => $c->common_name,
                'flagUrl' => $c->flag_url,
            ];
        });
        
        return view('mvc.authors.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $photoPath = null;
        if ($request->hasFile('photo_path')) {
            $photoPath = $this->fileService->upload($request->file('photo_path'), 'authors');
        }

        $dto = AuthorData::fromArray($request->validated(), $photoPath);
        $author = $this->authorService->store($dto);

        if ($request->expectsJson()) {
            return response()->json([
                'id' => $author->id,
                'name' => $author->name . ' ' . $author->last_name,
                'message' => 'Autor creado con éxito y seleccionado automáticamente.'
            ]);
        }

        return redirect()->route('mvc.authors.index')
            ->with('success', 'Autor creado con éxito.');
    }

    public function show(Author $author)
    {
        $author->load('books', 'country');
        return view('mvc.authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        $countries = Country::orderBy('common_name')->get()->map(function($c) {
            return [
                'id' => $c->id,
                'common_name' => $c->common_name,
                'flagUrl' => $c->flag_url,
            ];
        });
        return view('mvc.authors.edit', compact('author', 'countries'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Author $author)
    {
        $photoPath = $author->photo_path;

        // Caso 1: Se solicita eliminar la foto actual
        if ($request->boolean('remove_photo') && !$request->hasFile('photo_path')) {
            if ($author->photo_path) {
                $this->fileService->delete($author->photo_path);
            }
            $photoPath = null;
        }

        // Caso 2: Se sube una nueva foto (reemplaza la anterior)
        if ($request->hasFile('photo_path')) {
            if ($author->photo_path) {
                $this->fileService->delete($author->photo_path);
            }
            $photoPath = $this->fileService->upload($request->file('photo_path'), 'authors');
        }

        $dto = AuthorData::fromArray($request->validated(), $photoPath);
        $this->authorService->update($author, $dto);

        return redirect()->route('mvc.authors.index')
            ->with('success', 'Autor actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        try {
            $this->authorService->delete($author);
            return redirect()->route('mvc.authors.index')->with('success', 'Autor eliminado con éxito.');
        } catch (\Exception $e) {
            return redirect()->route('mvc.authors.index')->with('error', $e->getMessage());
        }
    }
}
