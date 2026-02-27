<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author, Country};
use Illuminate\Http\Request;
use App\Http\Requests\Book\{StoreRequest, UpdateRequest};
use App\Services\BookService;
use App\DTOs\BookData;

class BookController extends Controller
{
    public function __construct(
        protected BookService $bookService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::select('id', 'title', 'isbn', 'num_pages', 'author_id')
            ->with('author')
            ->paginate(10);
        return view('mvc.books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = Author::select('id', 'name', 'last_name')
            ->orderBy('name')
            ->get();
        $countries = Country::getForSelect();

        return view('mvc.books.create', compact('authors', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $dto = BookData::fromArray($request->validated());
        $this->bookService->store($dto);

        return redirect()->route('mvc.books.index')
            ->with('success', 'Libro creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $authors = Author::select('id', 'name', 'last_name')
            ->orderBy('name')
            ->get();
        $countries = Country::getForSelect();

        return view('mvc.books.edit', compact('book', 'authors', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Book $book)
    {
        $dto = BookData::fromArray($request->validated());
        $this->bookService->update($book, $dto);

        return redirect()->route('mvc.books.index')
            ->with('success', 'Libro actualizado con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->bookService->delete($book);

        return redirect()->route('mvc.books.index')
            ->with('success', 'Libro eliminado con éxito.');
    }
}
