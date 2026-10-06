<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminCustomerController extends Controller
{
    private function guard(): void
    {
        abort_unless(Auth::check() && Auth::user()->isAdmin(), 403);
    }

    public function index(Request $request)
    {
        $this->guard();

        $validated = $request->validate(['q' => ['nullable', 'string', 'max:100']]);
        $search = trim((string) ($validated['q'] ?? ''));

        $customers = User::query()
            ->where('role', 'customer')
            ->withCount('applications')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function destroy(User $user)
    {
        $this->guard();
        abort_unless($user->isCustomer(), 404);

        $applicationIds = $user->applications()->pluck('id');
        $filePaths = DB::table('application_documents')
            ->whereIn('application_id', $applicationIds)
            ->pluck('file_path')
            ->filter(fn ($path) => filled($path) && ! str_starts_with($path, 'database://'))
            ->unique()->values()->all();

        DB::transaction(function () use ($user, $applicationIds) {
            DB::table('notifications')
                ->where('notifiable_type', User::class)
                ->where('notifiable_id', $user->id)
                ->delete();

            if ($applicationIds->isNotEmpty()) {
                DB::table('notifications')->where(function ($query) use ($applicationIds) {
                    foreach ($applicationIds as $applicationId) {
                        $query->orWhere('data', 'like', '%"application_id":'.$applicationId.'%');
                    }
                })->delete();
            }

            DB::table('sessions')->where('user_id', $user->id)->delete();

            // applications and application_documents cascade from the user FK.
            $user->forceDelete();
        });

        foreach ($filePaths as $path) {
            Storage::disk('local')->delete($path);
        }

        return redirect()->route('admin.customers.index')->with('success',
            __('Customer account and all related application history, notifications, sessions and uploaded files were permanently deleted. The customer can register again as a completely new user.')
        );
    }
}
