<?php

// app/Http/Controllers/PersonController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\Attendance;
use Carbon\Carbon;

class PersonController extends Controller
{
    // Retorna a lista de pessoas
    public function index()
    {
        return response()->json(Person::all());
    }

    // Armazena uma nova pessoa
    public function store(Request $request)
    {
        $person = Person::create($request->all());
        return response()->json($person, 201);
    }

    // Retorna uma pessoa específica
    public function show($id)
    {
        return response()->json(Person::findOrFail($id));
    }

    // Atualiza uma pessoa existente
    public function update(Request $request, $id)
    {
        $person = Person::findOrFail($id);
        $person->update($request->all());
        return response()->json($person);
    }

    // Remove uma pessoa
    public function destroy($id)
    {
        Person::destroy($id);
        return response()->json(null, 204);
    }

    // Retorna os dias de treino restantes
    public function remainingDays($id)
    {
        // Lógica para calcular e retornar os dias restantes
        // Exemplo fictício:
        $person = Person::findOrFail($id);
        $remainingDays = $person->calculateRemainingDays(); // Método fictício
        return response()->json(['remaining_days' => $remainingDays]);
    }

 public function getRemainingDaysByCode($unique_code)
    {
        // Verifica se a pessoa existe
        $person = Person::where('unique_code', $unique_code)->firstOrFail();

        // Define o total de dias permitidos no mês
        $totalDays = 25; // Número total de dias permitidos por mês

        // Obtém todos os dias de treino únicos no mês atual
        $attendances = $person->attendances()
                              ->whereMonth('attended_at', Carbon::now()->month)
                              ->selectRaw('DATE(attended_at) as date')
                              ->distinct()
                              ->get();

        // Conta os dias únicos de treino
        $usedDays = $attendances->count();

        // Calcula os dias restantes
        $remainingDays = $totalDays - $usedDays;

        return response()->json([
            'remaining_days' => max($remainingDays, 0) // Garante que o saldo não seja negativo
        ]);
    }
}