<?php

namespace App\Http\Controllers;

use App\Models\FichaGrooming;
use App\Models\Cita;
use App\Models\Insumo;
use App\Models\ServicioInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GroomingController extends Controller
{
    public function show($cita_id)
    {
        $cita = Cita::with(['mascota', 'servicio', 'groomer.user'])->findOrFail($cita_id);
        
        $user = auth()->user();
        if ($user->id_rol != 1 && $user->id_rol != 3 && $cita->groomer->id_usuario != $user->id) {
            abort(403, 'No tienes permiso para ver esta ficha.');
        }
        
        // CORREGIDO: usar 'id_cita' en lugar de 'cita_id'
        $ficha = FichaGrooming::firstOrCreate(
            ['id_cita' => $cita_id],
            ['estado_ficha' => 'ABIERTA']
        );

        // Obtener insumos asociados al servicio
        $insumosServicio = ServicioInsumo::where('id_servicio', $cita->id_servicio)->with('insumo')->get();
        
        // Obtener insumos ya registrados en esta ficha
        $insumosUsados = DB::table('grooming_insumos')->where('id_ficha', $ficha->id_ficha)->get();
        
        return view('grooming.show', compact('cita', 'ficha', 'insumosServicio', 'insumosUsados'));
    }

    public function updateChecklist(Request $request, $id_ficha)
    {
        $ficha = FichaGrooming::findOrFail($id_ficha);
        
        $checklist = [
            'baño' => $request->has('checklist_baño'),
            'corte' => $request->has('checklist_corte'),
            'uñas' => $request->has('checklist_uñas'),
            'oídos' => $request->has('checklist_oídos'),
            'glándulas' => $request->has('checklist_glándulas'),
            'perfume' => $request->has('checklist_perfume'),
        ];
        
        $ficha->checklist_json = $checklist;
        $ficha->save();
        
        return redirect()->back()->with('success', 'Checklist guardado correctamente');
    }

    public function updateEstado(Request $request, $id_ficha)
    {
        $ficha = FichaGrooming::findOrFail($id_ficha);
        
        $ficha->nudos = $request->has('nudos');
        $ficha->pulgas = $request->has('pulgas');
        $ficha->heridas = $request->heridas;
        $ficha->recomendaciones = $request->recomendaciones;
        $ficha->save();
        
        return redirect()->back()->with('success', 'Estado guardado correctamente');
    }

    public function updateInsumos(Request $request, $id_ficha)
    {
        $ficha = FichaGrooming::findOrFail($id_ficha);
        
        if ($request->has('insumos')) {
            foreach ($request->insumos as $item) {
                if (isset($item['usado']) && $item['usado'] == 1) {
                    DB::table('grooming_insumos')->updateOrInsert(
                        ['id_ficha' => $id_ficha, 'id_insumo' => $item['id_insumo']],
                        [
                            'cantidad_usada' => $item['cantidad'] ?? 1,
                            'usado' => true,
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Insumos registrados correctamente');
    }

    public function uploadFoto(Request $request)
    {
        $request->validate([
            'ficha_id' => 'required|exists:fichas_grooming,id_ficha',
            'tipo' => 'required|in:antes,despues',
            'foto' => 'required|image|max:2048'
        ]);

        $ficha = FichaGrooming::findOrFail($request->ficha_id);
        
        if ($request->tipo == 'antes' && $ficha->foto_antes) {
            Storage::delete('public/' . $ficha->foto_antes);
        } elseif ($request->tipo == 'despues' && $ficha->foto_despues) {
            Storage::delete('public/' . $ficha->foto_despues);
        }

        $path = $request->file('foto')->store('grooming_fotos', 'public');
        
        if ($request->tipo == 'antes') {
            $ficha->foto_antes = $path;
        } else {
            $ficha->foto_despues = $path;
        }
        $ficha->save();

        return redirect()->back()->with('success', 'Foto subida correctamente');
    }

    public function deleteFoto($id, $tipo)
    {
        $ficha = FichaGrooming::findOrFail($id);
        
        if ($tipo == 'antes' && $ficha->foto_antes) {
            Storage::delete('public/' . $ficha->foto_antes);
            $ficha->foto_antes = null;
        } elseif ($tipo == 'despues' && $ficha->foto_despues) {
            Storage::delete('public/' . $ficha->foto_despues);
            $ficha->foto_despues = null;
        }
        $ficha->save();

        return redirect()->back()->with('success', 'Foto eliminada correctamente');
    }

    public function cerrarServicio($id_ficha)
    {
        $ficha = FichaGrooming::findOrFail($id_ficha);
        
        // Verificar que se hayan registrado insumos
        $insumosRegistrados = DB::table('grooming_insumos')->where('id_ficha', $id_ficha)->exists();
        
        if (!$insumosRegistrados) {
            return redirect()->back()->with('error', 'Debes registrar los insumos utilizados antes de cerrar el servicio.');
        }
        
        $ficha->estado_ficha = 'COMPLETADA';
        $ficha->fecha_cierre = now();
        $ficha->save();

        // Descontar stock de los insumos usados
        $insumosUsados = DB::table('grooming_insumos')->where('id_ficha', $id_ficha)->get();
        foreach ($insumosUsados as $item) {
            DB::table('insumos')->where('id_insumo', $item->id_insumo)->decrement('stock_actual', $item->cantidad_usada);
        }

        return redirect()->route('citas.index')->with('success', 'Servicio completado correctamente');
    }

    public function recibirInsumos(Request $request, $id_ficha)
    {
        $ficha = FichaGrooming::findOrFail($id_ficha);

        if ($request->has('insumos_recibir')) {
            foreach ($request->insumos_recibir as $item) {
                if (isset($item['recibir']) && $item['recibir'] == 1) {
                    $cantidad = $item['cantidad'] ?? 1;

                    // Buscar si ya existe un registro
                    $existe = DB::table('grooming_insumos')
                        ->where('id_ficha', $id_ficha)
                        ->where('id_insumo', $item['id_insumo'])
                        ->first();

                    if ($existe) {
                        // Actualizar sumando la cantidad
                        DB::table('grooming_insumos')
                            ->where('id_ficha', $id_ficha)
                            ->where('id_insumo', $item['id_insumo'])
                            ->update([
                                'cantidad_recibida' => $existe->cantidad_recibida + $cantidad,
                                'updated_at' => now(),
                            ]);
                    } else {
                        // Crear nuevo registro
                        DB::table('grooming_insumos')->insert([
                            'id_ficha' => $id_ficha,
                            'id_insumo' => $item['id_insumo'],
                            'cantidad_recibida' => $cantidad,
                            'cantidad_usada' => 0,
                            'usado' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // Descontar del stock global
                    DB::table('insumos')->where('id_insumo', $item['id_insumo'])->decrement('stock_actual', $cantidad);
                }
            }
        }

        return redirect()->back()->with('success', 'Materiales recibidos registrados correctamente');
    }
}