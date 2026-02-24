<?php

namespace App\Http\Controllers;

use App\Models\{Book, Author};
use Illuminate\Http\Request;
use App\Http\Requests\Book\StoreRequest;
use App\Services\BookService;

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
        $countries = \App\Models\Country::select('id', 'common_name', 'flag_svg_path')
            ->orderBy('common_name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'common_name' => $c->common_name,
                'flagUrl' => $c->flag_url,
            ]);
        return view('mvc.books.create', compact('authors', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $this->bookService->store($request);

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
