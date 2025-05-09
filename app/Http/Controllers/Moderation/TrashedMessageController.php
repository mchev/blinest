<?php

namespace App\Http\Controllers\Moderation;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrashedMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::onlyTrashed()
            ->with(['user', 'room'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('body', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->sort_by, function ($query, $sortBy) use ($request) {
                $direction = $request->sort_direction === 'asc' ? 'asc' : 'desc';
                $query->orderBy($sortBy, $direction);
            }, function ($query) {
                $query->latest('deleted_at');
            });

        $messages = $query->paginate($request->per_page ?? 10)
            ->withQueryString();

        return Inertia::render('Moderation/TrashedMessages', [
            'trashedMessages' => $messages,
            'filters' => $request->only(['search', 'per_page', 'sort_by', 'sort_direction']),
        ]);
    }

    public function restore(Message $message)
    {
        try {
            if (! $message->trashed()) {
                return back()->with('error', 'Message is not deleted.');
            }

            $message->restore();

            return back()->with('success', 'Message has been restored successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to restore message. Please try again.');
        }
    }

    public function destroy(Message $message)
    {
        try {
            if (! $message->trashed()) {
                return back()->with('error', 'Message is not deleted.');
            }

            $message->forceDelete();

            return back()->with('success', 'Message has been permanently deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete message. Please try again.');
        }
    }
}
