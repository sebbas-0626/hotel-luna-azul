<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\Reserva;
use App\Models\Habitacion;

// Controlador de reservas
class ReservaController extends Controller
{
    protected function hasConflict(int $habitacionId, string $ingreso, string $salida, ?int $excludeId = null): bool
    {
        $q = Reserva::where('habitacion_id', $habitacionId)
            ->where('estado', '!=', 'cancelada')
            ->where('fecha_ingreso', '<', $salida)
            ->where('fecha_salida', '>', $ingreso);
        if ($excludeId) $q->where('id', '!=', $excludeId);
        return $q->exists();
    }

    public function index($request)
    {
        $this->requireAuth();
        $q = Reserva::with(['huesped', 'habitacion'])->orderBy('id', 'desc');
        if (isset($request->query['huesped_id'])) {
            $q->where('huesped_id', $request->query['huesped_id']);
        }
        Response::json(['data' => $q->get()]);
    }

    public function show($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Reserva::with(['huesped', 'habitacion'])->find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        Response::json(['data' => $item]);
    }

    public function store($request)
    {
        $this->requireAuth();
        $data = $request->all();
        $this->validate($data, [
            'huesped_id' => 'required|numeric',
            'fecha_ingreso' => 'required',
            'fecha_salida' => 'required',
        ]);
        if (strtotime($data['fecha_ingreso']) === false || strtotime($data['fecha_salida']) === false) {
            Response::json(['errors' => ['fecha' => ['Fechas inválidas']]], 422);
        }
        if (strtotime($data['fecha_ingreso']) >= strtotime($data['fecha_salida'])) {
            Response::json(['errors' => ['fecha_salida' => ['La salida debe ser posterior a la entrada']]], 422);
        }

        $habitacionId = $data['habitacion_id'] ?? null;
        $tipo = $data['tipo_habitacion'] ?? null;

        if (!$habitacionId && $tipo) {
            // find available room of type
            $candidates = Habitacion::where('tipo', $tipo)->get();
            foreach ($candidates as $h) {
                if (!$this->hasConflict($h->id, $data['fecha_ingreso'], $data['fecha_salida'])) {
                    $habitacionId = $h->id;
                    break;
                }
            }
            if (!$habitacionId) {
                Response::json(['error' => 'No hay habitaciones disponibles de ese tipo en las fechas'], 409);
            }
        }

        if (!$habitacionId) {
            Response::json(['error' => 'Debe indicar habitacion_id o tipo_habitacion'], 422);
        }

        $habitacion = Habitacion::find($habitacionId);
        if (!$habitacion) Response::json(['error' => 'Habitación no encontrada'], 404);

        if ($this->hasConflict((int)$habitacionId, $data['fecha_ingreso'], $data['fecha_salida'])) {
            Response::json(['error' => 'La habitación ya está reservada en esas fechas'], 409);
        }

        $data['habitacion_id'] = $habitacionId;
        if (!$tipo) $data['tipo_habitacion'] = $habitacion->tipo;
        $data['estado'] = $data['estado'] ?? 'confirmada';

        $item = Reserva::create($data);
        $item->load(['huesped', 'habitacion']);
        Response::json(['data' => $item], 201);
    }

    public function update($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Reserva::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        $data = $request->all();
        if (isset($data['fecha_ingreso'], $data['fecha_salida'])) {
            if (strtotime($data['fecha_ingreso']) >= strtotime($data['fecha_salida'])) {
                Response::json(['errors' => ['fecha_salida' => ['La salida debe ser posterior a la entrada']]], 422);
            }
        }
        $newHab = $data['habitacion_id'] ?? $item->habitacion_id;
        $newIngreso = $data['fecha_ingreso'] ?? $item->fecha_ingreso->format('Y-m-d');
        $newSalida = $data['fecha_salida'] ?? $item->fecha_salida->format('Y-m-d');
        if ($this->hasConflict((int)$newHab, $newIngreso, $newSalida, (int)$id)) {
            Response::json(['error' => 'La habitación ya está reservada en esas fechas'], 409);
        }
        $item->fill($data);
        $item->save();
        $item->load(['huesped', 'habitacion']);
        Response::json(['data' => $item]);
    }

    public function destroy($request)
    {
        $this->requireAuth();
        $id = $request->params['id'];
        $item = Reserva::find($id);
        if (!$item) Response::json(['error' => 'No encontrado'], 404);
        $item->delete();
        Response::json(['message' => 'Eliminado']);
    }

    public function disponibilidad($request)
    {
        $this->requireAuth();
        $ingreso = $request->query['fecha_ingreso'] ?? null;
        $salida = $request->query['fecha_salida'] ?? null;
        $tipo = $request->query['tipo'] ?? null;
        if (!$ingreso || !$salida) Response::json(['error' => 'Fechas requeridas'], 422);
        $q = Habitacion::query();
        if ($tipo) $q->where('tipo', $tipo);
        $available = [];
        foreach ($q->get() as $h) {
            if (!$this->hasConflict($h->id, $ingreso, $salida)) {
                $available[] = $h;
            }
        }
        Response::json(['data' => $available]);
    }
}
