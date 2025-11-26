<?php

namespace App\Http\Controllers;

use App\Models\{Categories, Todos};
use Illuminate\Http\Request;

class TodosController extends Controller
{
    public function liste()
    {
        return view('home', ['todos' => Todos::all(), 'categories' => Categories::all()]);

    }

    public function saveTodo(Request $request)
    {
        $texte = $request->input('texte');
        $priority = $request->input('priority');
        // dd($request->input('priority')); // fonction de débug

        if ($texte) {
            // création d'un nouvel élément Todos et enregistrement dans la base de donnée
            $todo = new Todos;
            $todo->texte = $texte;
            $todo->termine = 0;
            $todo->important = $priority;

            // save() pour mettre a jour et insérer des éléments dans la base
            $todo->save();

            // après la modification on retourne sur notre vue "home" qui a comme nom "todo.liste"
            return redirect()->route('todo.liste');
        } else {
            return redirect()->route('todo.liste')->with('message', 'Veuillez saisir une note à ajouter');
        }

    }

    public function upImportance($id)
    {
        // Récupère le Todo par son id on utilise find() ou findOrFail()
        $todo = Todos::find($id);
        $todo->important = 1;
        $todo->save(); // save() pour mettre a jour et insérer des éléments dans la base

        return redirect()->route('todo.liste');
    }

    public function downImportance($id)
    {
        $todo = Todos::find($id);
        $todo->important = 0;
        $todo->save();

        return redirect()->route('todo.liste');
    }

    public function done($id)
    {
        $todo = Todos::find($id);
        // Inverser la valeur actuelle de "termine"
        $todo->termine = ! $todo->termine;
        $todo->save();

        return redirect()->route('todo.liste');
    }

    public function delete($id)
    {
        $todo = Todos::find($id);
        if ($todo->termine) {
            $todo->delete();

            return redirect()->route('todo.liste');
        } else {
            return redirect()
                ->route('todo.liste')
                ->with('message', 'Veuillez terminé la tache avant de la supprimé');
        }
    }

    public function stats()
    {
        $terminees = Todos::where('termine', 1)->count();
        $nonTerminees = Todos::where('termine', 0)->count();
        $supprimees = Todos::onlyTrashed()->count();

        return view('compteur', compact('terminees', 'nonTerminees', 'supprimees'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('q');

        $todos = [];

        if ($keyword) {
            $todos = Todos::where('texte', 'LIKE', "%{$keyword}%")
                ->get();
        }

        return view('search', compact('todos', 'keyword'));
    }
}
