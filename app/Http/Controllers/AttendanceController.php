<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Person;

class AttendanceController extends Controller
{
    // Exibe uma lista de entradas
    public function index()
    {
        // Inclua os dados da pessoa associada a cada entrada
        $attendances = Attendance::with('person')->get();
        return response()->json($attendances);
    }

    // Cria um novo registro de entrada
    public function store(Request $request)
    {
        // Valida os dados da requisição
        $request->validate([
            'unique_code' => 'required|string',
        ]);

        // Busca a pessoa pelo código único
        $person = Person::where('unique_code', $request->unique_code)->first();

        if (!$person) {
            return response()->json(['error' => 'Person not found.'], 404);
        }

        // Verifica se a pessoa tem dias restantes para o mês
        $totalDays = 25; // Exemplo: número de dias permitidos por mês
        $usedDays = $person->attendances()->whereMonth('attended_at', now()->month)->count();
        $remainingDays = $totalDays - $usedDays;

        if ($remainingDays <= 0) {
            return response()->json(['error' => 'No remaining days for this month.'], 400);
        }

        // Determina o período com base no horário atual
        $currentHour = now()->hour;

        if ($currentHour >= 6 && $currentHour < 12) {
            $session = 'morning';
        } elseif ($currentHour >= 12 && $currentHour < 18) {
            $session = 'afternoon';
        } else {
            $session = 'evening';
        }

        // Cria a nova entrada
        $attendance = Attendance::create([
            'person_id' => $person->id,
            'attended_at' => now(),
            'session' => $session, // Define o período automaticamente
        ]);

        return response()->json($attendance, 201);
    }

    // Exibe um registro de entrada específico
    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        return response()->json($attendance);
    }

    // Atualiza um registro de entrada específico
    public function update(Request $request, $id)
    {
        // Valida os dados da requisição
        $request->validate([
            'person_id' => 'sometimes|exists:people,id',
            'session' => 'sometimes|in:morning,afternoon,evening',
        ]);

        $attendance = Attendance::findOrFail($id);

        // Atualiza os dados da entrada
        $attendance->update($request->all());

        return response()->json($attendance);
    }

    // Remove um registro de entrada específico
    public function destroy($id)
    {
        Attendance::destroy($id);
        return response()->json(null, 204);
    }

    // Recupera as entradas de uma pessoa específica
    public function getByPerson($personId)
    {
        $person = Person::findOrFail($personId);
        $attendances = $person->attendances;
        return response()->json($attendances);
    }

    // Recupera a quantidade de entradas para um mês específico
    public function getMonthlyStats($month)
    {
        $attendances = Attendance::whereMonth('attended_at', $month)->get();
        return response()->json([
            'total_entries' => $attendances->count(),
            'entries' => $attendances,
        ]);
    }
}
