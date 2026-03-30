<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    /**
     * Affiche la liste des catégories.
     *
     * @return Response
     */
    public function listeCatégories()
    {
        return view('home', ['categories' => Categories::all()]);
    }
}