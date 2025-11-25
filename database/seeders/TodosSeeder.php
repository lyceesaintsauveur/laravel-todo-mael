<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $todos = [
            'Faire les courses',
            'Arroser les plantes',
            'Répondre aux e-mails',
            'Nettoyer la cuisine',
            'Sortir le chien',
            'Préparer la réunion de lundi',
        ];

        $data = [];

        foreach ($todos as $texte) {
            $data[] = [
                'texte' => $texte,
                'termine' => rand(0, 1),
                'important' => rand(0, 1),
                'id_user' => rand(1, 2),
            ];
        }
        // $data = [
        //        ['texte' => 'texte', 'termine' => 0,'important' => 0],
        //        ['texte' => 'texte 2', 'termine' => 0,'important' => 0]
        //    ];

        DB::table('todos')->insert($data);
    }
}
