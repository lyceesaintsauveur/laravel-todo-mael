<?php

namespace App\Http\Controllers;

use App\Models\Listes;
use App\Models\Todos;
use Illuminate\Http\Request;

class ListesController extends Controller
{
    public function liste(Request $request)
    {
        // partie formulaire
        // Récupération de la liste des todos non encore attribués à une liste
        $todos = Todos::whereNull('listes_id')->where('id_user', $request->user()->id)->get();
        // Partie d'affichage de la liste
        // Récupération de la liste des listes avec leurs Todos
        $listes = Listes::with('todos')->get();

        return view('liste', compact('todos', 'listes'));
        // Ancien truc
        // return view("liste", ["listes" => Listes::all(), "todos" => Todos::all()]);

    }

    public function saveListe(Request $request)
    {
        $texte = $request->input('texte');
        $todos = $request->input('todos');
        // dd($request->input('priority')); // fonction de débug

        if ($texte) {
            // création d'un nouvel élément Todos et enregistrement dans la base de donnée
            $liste = new Listes;
            $liste->libelle = $texte;
            $liste->save();

            foreach ($todos as $todo) {
                $todo = Todos::find($todo);
                $todo->listes_id = $liste->id;
                $todo->save();
            }

            // save() pour mettre a jour et insérer des éléments dans la base
            $liste->save();

            // après la modification on retourne sur notre vue "home" qui a comme nom "todo.liste"
            return redirect()->route('liste.liste');
        } else {
            return redirect()->route('liste.liste')->with('message', 'Veuillez saisir une note à ajouter');
        }

    }
}
