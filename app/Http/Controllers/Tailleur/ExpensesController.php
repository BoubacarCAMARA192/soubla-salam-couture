<?php

namespace App\Http\Controllers\Tailleur;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    /** Suivi des dépenses de l'atelier (TAI-10) */
    public function index(Request $request)
    {
        $month = (int) ($request->query('month') ?: now()->month);

        $expenses = Expense::whereMonth('spent_at', $month)
            ->orderBy('spent_at', 'desc')
            ->paginate(20);

        return view('tailleur.expenses', [
            'expenses' => $expenses,
            'totalMonth' => Expense::whereMonth('spent_at', $month)->sum('amount'),
            'month' => $month,
            'categories' => Expense::query()->select('category')->distinct()->pluck('category')->filter()->all(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'category' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'spent_at' => ['required', 'date', 'before_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'label.required' => 'Indiquez un libellé pour la dépense.',
            'amount.gt' => 'Le montant doit être supérieur à 0.',
        ]);

        Expense::create($data + ['created_by' => $request->user()->id]);

        return back()->with('success', 'Dépense enregistrée.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return back()->with('success', 'Dépense supprimée.');
    }
}
