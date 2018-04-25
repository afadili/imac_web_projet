<?php

namespace App\Http\Controllers;

use App\Statistique;
use Illuminate\Http\Request;

class StatistiqueController extends Controller
{

    public function showAllStatistiques()
    {
        return response()->json(Statistique::all());
    }

    public function showOneStatistique($id)
    {
        return response()->json(Statistique::find($id));
    }

    public function create(Request $request)
    {
        $statistique = Statistique::create($request->all());

        return response()->json($statistique, 201);
    }

    public function update($id, Request $request)
    {
        $statistique = Statistique::findOrFail($id);
        $statistique->update($request->all());

        return response()->json($statistique, 200);
    }

    public function delete($id)
    {
        Statistique::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}