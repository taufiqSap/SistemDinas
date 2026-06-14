<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()
            ->where('role', 'user')
            ->orderBy('nama');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();

            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('no_hp', 'like', "%{$keyword}%");
            });
        }

        return view('admin.users.index', [
            'users' => $query->paginate(10)->withQueryString(),
            'statusLabels' => [
                'aktif' => 'Aktif',
                'nonaktif' => 'Nonaktif',
            ],
            'statusClasses' => [
                'aktif' => 'bg-emerald-100 text-emerald-800 ring-1 ring-inset ring-emerald-200',
                'nonaktif' => 'bg-rose-100 text-rose-800 ring-1 ring-inset ring-rose-200',
            ],
        ]);
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->role !== 'user') {
            abort(403, 'Status hanya bisa diubah untuk akun user.');
        }

        $user->status = ($user->status ?? 'aktif') === 'aktif' ? 'nonaktif' : 'aktif';
        $user->save();

        return back()->with('success', 'Status user berhasil diperbarui.');
    }
}
