<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Huesped;

// Controlador de huéspedes
class HuespedController extends Controller
{
    public function index($request)
    {
        $this->requireAuth();
        $q = Huesped::query();
        if ($s = $request->query['search'] ?? null) {
            $q->where('nombre_completo', 'like', "%$s%")
              ->orWhere('numero_documento', 'like', "%$s%");
        }
        $items = $q->orderBy('id', 'desc')->get();
        Response::json(['data' => $items]);
    }

    public function show($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Huesped::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        Response::json(['data' => $item]);
    }

    public function store($request)
    {
        $this->requireAuth();
        $data = $request->all();
        $this->validate($data, [
            'nombre_completo' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
        ]);
        if (Huesped::where('numero_documento', $data['numero_documento'])->exists()) {
            Response::json(['errors' => ['numero_documento' => ['Documento ya registrado']]], 422);
        }
        $item = Huesped::create($data);
        Response::json(['data' => $item], 201);
    }

    public function update($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Huesped::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        $data = $request->all();
        $this->validate($data, [
            'nombre_completo' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
        ]);
        $item->fill($data);
        $item->save();
        Response::json(['data' => $item]);
    }

    public function destroy($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Huesped::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        $item->delete();
        Response::json(['message' => 'Eliminado']);
    }

    public function reservas($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $items = \App\Models\Reserva::where('huesped_id', $id)->with('habitacion')->orderBy('id', 'desc')->get();
        Response::json(['data' => $items]);
    }
}
