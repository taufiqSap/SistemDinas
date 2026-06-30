<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(private WhatsAppService $whatsApp) {}

    public function index(Request $request): View
    {
        $query = User::with('phone')
            ->where('role', 'user')
            ->orderBy('nama');

        if ($request->filled('q')) {
            $keyword = $request->string('q')->toString();

            $query->where(function ($builder) use ($keyword) {
                $builder->where('nama', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhereHas('phone', function ($q) use ($keyword) {
                        $q->where('no_hp', 'like', "%{$keyword}%");
                    });
            });
        }

        return view('admin.users.index', [
            'users'         => $query->paginate(10)->withQueryString(),
            'statusLabels'  => [
                'aktif'    => 'Aktif',
                'nonaktif' => 'Nonaktif',
            ],
            'statusClasses' => [
                'aktif'    => 'bg-emerald-100 text-emerald-800 ring-1 ring-inset ring-emerald-200',
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

    /**
     * Reset password user menjadi nomor HP, lalu kirim notifikasi WhatsApp 1x.
     */
    public function resetPassword(User $user): RedirectResponse
    {
        if ($user->role !== 'user') {
            abort(403, 'Hanya akun user yang dapat direset passwordnya.');
        }

        $noHp = $user->phone?->no_hp;

        if (empty($noHp)) {
            return back()->withErrors(['error' => 'User ini tidak memiliki nomor HP, reset password gagal.']);
        }

        $user->password = bcrypt($noHp);
        $user->save();

        // Kirim notifikasi WhatsApp 1x
        $message = "Halo {$user->nama}, password akun Anda telah direset oleh admin. Password baru Anda adalah nomor HP Anda. Segera ganti password setelah login.";
        $this->whatsApp->send($noHp, $message);

        return back()->with('success', 'Password user "' . $user->nama . '" berhasil direset ke nomor HP.');
    }
}