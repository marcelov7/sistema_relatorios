<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MotorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Motor::orderBy('data_criacao', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tag', 'like', "%{$search}%")
                  ->orWhere('equipment', 'like', "%{$search}%")
                  ->orWhere('manufacturer', 'like', "%{$search}%")
                  ->orWhere('frame_manufacturer', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', "%{$request->location}%");
        }

        if ($request->filled('stock_reserve')) {
            $query->where('stock_reserve', $request->stock_reserve);
        }

        // Filtros de faixa de potência (kW)
        if ($request->filled('power_min')) {
            $query->where('power_kw', '>=', $request->power_min);
        }

        if ($request->filled('power_max')) {
            $query->where('power_kw', '<=', $request->power_max);
        }

        // Filtros de faixa de corrente nominal
        if ($request->filled('current_min')) {
            $query->where('rated_current', '>=', $request->current_min);
        }

        if ($request->filled('current_max')) {
            $query->where('rated_current', '<=', $request->current_max);
        }

        $perPage = $request->get('per_page', 12);
        $perPage = in_array($perPage, [6, 12, 24, 48]) ? $perPage : 12;
        
        $motores = $query->paginate($perPage);

        // Dados para filtros
        $stockReserveOptions = Motor::getStockReserveOptions();

        // Estatísticas
        $stats = [
            'total' => Motor::count(),
            'com_foto' => Motor::whereNotNull('photo')->count(),
            'com_estoque' => Motor::where('stock_reserve', 'Sim')->count(),
            'tipos_unicos' => Motor::distinct('equipment_type')->count('equipment_type'),
        ];

        return view('motores.index', compact(
            'motores', 
            'stockReserveOptions',
            'stats'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stockReserveOptions = Motor::getStockReserveOptions();
        
        return view('motores.create', compact('stockReserveOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tag' => 'nullable|string|max:100',
            'equipment' => 'required|string|max:255',
            'frame_manufacturer' => 'nullable|string|max:255',
            'power_kw' => 'nullable|numeric|min:0|max:9999.99',
            'power_cv' => 'nullable|numeric|min:0|max:9999.99',
            'rotation' => 'nullable|integer|min:0|max:999999',
            'rated_current' => 'nullable|numeric|min:0|max:9999.99',
            'configured_current' => 'nullable|numeric|min:0|max:9999.99',
            'equipment_type' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'stock_reserve' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168',
            'storage' => 'nullable|string|max:255'
        ]);

        // Processar imagem se enviada
        if ($request->hasFile('photo')) {
            $imagem = $request->file('photo');
            $nomeArquivo = Str::uuid() . '.' . $imagem->getClientOriginalExtension();
            $caminho = $imagem->storeAs('motores', $nomeArquivo, 'public');
            $validated['photo'] = $caminho;
        }

        $motor = Motor::create($validated);

        return redirect()
            ->route('motores.show', $motor)
            ->with('success', 'Motor criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Motor $motor)
    {
        return view('motores.show', compact('motor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Motor $motor)
    {
        $stockReserveOptions = Motor::getStockReserveOptions();
        
        return view('motores.edit', compact('motor', 'stockReserveOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Motor $motor)
    {
        $validated = $request->validate([
            'tag' => 'nullable|string|max:100',
            'equipment' => 'required|string|max:255',
            'frame_manufacturer' => 'nullable|string|max:255',
            'power_kw' => 'nullable|numeric|min:0|max:9999.99',
            'power_cv' => 'nullable|numeric|min:0|max:9999.99',
            'rotation' => 'nullable|integer|min:0|max:999999',
            'rated_current' => 'nullable|numeric|min:0|max:9999.99',
            'configured_current' => 'nullable|numeric|min:0|max:9999.99',
            'equipment_type' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'stock_reserve' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:7168',
            'storage' => 'nullable|string|max:255'
        ]);

        // Processar nova imagem se enviada
        if ($request->hasFile('photo')) {
            // Deletar imagem anterior se existir
            if ($motor->photo && Storage::disk('public')->exists($motor->photo)) {
                Storage::disk('public')->delete($motor->photo);
            }

            $imagem = $request->file('photo');
            $nomeArquivo = Str::uuid() . '.' . $imagem->getClientOriginalExtension();
            $caminho = $imagem->storeAs('motores', $nomeArquivo, 'public');
            $validated['photo'] = $caminho;
        }

        $motor->update($validated);

        return redirect()
            ->route('motores.show', $motor)
            ->with('success', 'Motor atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Motor $motor)
    {
        // Deletar imagem se existir
        if ($motor->photo && Storage::disk('public')->exists($motor->photo)) {
            Storage::disk('public')->delete($motor->photo);
        }

        $motor->delete();

        return redirect()
            ->route('motores.index')
            ->with('success', 'Motor excluído com sucesso!');
    }

    /**
     * Duplicar motor
     */
    public function duplicate(Motor $motor)
    {
        $novoMotor = $motor->replicate();
        $novoMotor->tag = $motor->tag ? 'Cópia de ' . $motor->tag : null;
        $novoMotor->equipment = 'Cópia de ' . $motor->equipment;
        $novoMotor->photo = null; // Não duplicar imagem
        $novoMotor->save();

        return redirect()
            ->route('motores.edit', $novoMotor)
            ->with('success', 'Motor duplicado com sucesso!');
    }

    /**
     * Excluir foto do motor
     */
    public function deletePhoto(Motor $motor)
    {
        if ($motor->photo) {
            // Remove o arquivo físico
            if (Storage::disk('public')->exists($motor->photo)) {
                Storage::disk('public')->delete($motor->photo);
            }
            
            // Remove a referência do banco
            $motor->update(['photo' => null]);
            
            return response()->json([
                'success' => true,
                'message' => 'Foto removida com sucesso!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nenhuma foto encontrada para remover.'
        ], 404);
    }
}
