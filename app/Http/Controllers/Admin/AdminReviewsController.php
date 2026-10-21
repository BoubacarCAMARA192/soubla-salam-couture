<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewsController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'catalogModel', 'order'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.reviews', ['reviews' => $reviews]);
    }

    /** Modération des avis (CAT-10) */
    public function moderate(Request $request, Review $review)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Review::STATUSES))],
        ]);

        $review->update($data);

        \App\Models\ActivityLog::record('review.moderate', 'review', $review->id, ['statut' => Review::STATUSES[$data['status']]]);

        return back()->with('success', 'Avis '.mb_strtolower(Review::STATUSES[$data['status']]).'.');
    }

    public function destroy(Review $review)
    {
        \App\Models\ActivityLog::record('review.delete', 'review', $review->id);
        $review->delete();

        return back()->with('success', 'Avis supprimé.');
    }
}
