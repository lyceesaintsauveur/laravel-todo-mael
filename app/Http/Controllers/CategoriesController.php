<?php

namespace App\Http\Controllers;

use App\Models\Categories;

class CategoriesController extends Controller
{
    /**
     * Affiche la liste des catégories.
     *
     * @return \Illuminate\Http\Response
     */
    public function listeCatégories()
    {
        return view('home', ['categories' => Categories::all()]);
    }
}
