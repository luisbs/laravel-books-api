<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::all();
        return BookResource::collection($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookRequest $request)
    {
        $book = new Book($request->only('title'));
        $book->save();
        return BookResource::make($book);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string|int  $title
     * @return \Illuminate\Http\Response
     */
    public function destroy($title = '')
    {
        $book = null;
        if (is_numeric($title)) {
            $book = Book::findOrFail($title);
        } else if (is_string($title)) {
            $book = Book::where('title', $title)->firstOrFail();
        }

        if (is_null($book)) {
            throw (new ModelNotFoundException)->setModel(Book::class, $title);
        }

        $book->delete();
        return BookResource::make($book);
    }
}
