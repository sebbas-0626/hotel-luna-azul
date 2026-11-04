<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Habitacion;

// Controlador de habitaciones
class HabitacionController extends Controller
{
    public function index($request)
    {
        $this->requireAuth();
        $items = Habitacion::orderBy('numero')->get();
        Response::json(['data' => $items]);
    }

    public function store($request)
    {
        $this->requireAuth();
        $data = $request->all();
        $this->validate($data, ['numero' => 'required', 'tipo' => 'required']);
        $item = Habitacion::create($data);
        Response::json(['data' => $item], 201);
    }

    public function update($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Habitacion::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        $item->fill($request->all());
        $item->save();
        Response::json(['data' => $item]);
    }

    public function destroy($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Habitacion::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        $item->delete();
        Response::json(['message' => 'Eliminado']);
    }
}
