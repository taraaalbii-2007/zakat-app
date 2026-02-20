<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Masjid;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    // ── Index ─────────────────────────────────────────────────────────────────

    public function index(Request $request): View
    {
        $query = Pengguna::with('masjid')->latest();

        // Search
        if ($search = $request->get('q')) {
            $query->search($search);
        }

        // Filter peran
        if ($peran = $request->get('peran')) {
            $query->byPeran($peran);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'aktif');
        }

        // Filter masjid
        if ($masjidId = $request->get('masjid_id')) {
            $query->where('masjid_id', $masjidId);
        }

        $pengguna = $query->paginate(10);

        $masjidList = Masjid::orderBy('nama')->get(['id', 'nama']);

        return view('superadmin.pengguna.index', compact('pengguna', 'masjidList'));
    }

    // ── Create ────────────────────────────────────────────────────────────────

    public function create(): View
    {
        $masjidList = Masjid::orderBy('nama')->get(['id', 'uuid', 'nama']);

        return view('superadmin.pengguna.create', compact('masjidList'));
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'peran'     => ['required', Rule::in(['superadmin', 'admin_masjid', 'amil'])],
            'masjid_id' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($request->peran, ['admin_masjid', 'amil'])),
                'exists:masjid,id',
            ],
            'username'  => ['nullable', 'string', 'max:255', 'unique:pengguna,username'],
            'email'     => ['required', 'email', 'max:255', 'unique:pengguna,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ], [
            'masjid_id.required' => 'Masjid wajib dipilih untuk peran Admin Masjid atau Amil.',
        ]);

        $validated['password']         = Hash::make($validated['password']);
        $validated['is_active']        = $request->boolean('is_active', true);
        $validated['email_verified_at'] = now();

        Pengguna::create($validated);

        return redirect()
            ->route('superadmin.pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(string $uuid): View
    {
        $pengguna = Pengguna::with('masjid')->where('uuid', $uuid)->firstOrFail();

        return view('superadmin.pengguna.show', compact('pengguna'));
    }

    // ── Edit ──────────────────────────────────────────────────────────────────

    public function edit(string $uuid): View
    {
        $pengguna   = Pengguna::where('uuid', $uuid)->firstOrFail();
        $masjidList = Masjid::orderBy('nama')->get(['id', 'uuid', 'nama']);

        return view('superadmin.pengguna.edit', compact('pengguna', 'masjidList'));
    }

    // ── Update ────────────────────────────────────────────────────────────────

    public function update(Request $request, string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'peran'     => ['required', Rule::in(['superadmin', 'admin_masjid', 'amil'])],
            'masjid_id' => [
                'nullable',
                Rule::requiredIf(fn () => in_array($request->peran, ['admin_masjid', 'amil'])),
                'exists:masjid,id',
            ],
            'username'  => [
                'nullable', 'string', 'max:255',
                Rule::unique('pengguna', 'username')->ignore($pengguna->id),
            ],
            'email'     => [
                'required', 'email', 'max:255',
                Rule::unique('pengguna', 'email')->ignore($pengguna->id),
            ],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
            'is_active' => ['boolean'],
        ], [
            'masjid_id.required' => 'Masjid wajib dipilih untuk peran Admin Masjid atau Amil.',
        ]);

        // Kosongkan masjid_id jika superadmin
        if ($validated['peran'] === 'superadmin') {
            $validated['masjid_id'] = null;
        }

        // Password opsional saat update
        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $pengguna->update($validated);

        return redirect()
            ->route('superadmin.pengguna.show', $pengguna->uuid)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    // ── Destroy ───────────────────────────────────────────────────────────────

    public function destroy(string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        // Prevent deleting own account
        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $pengguna->delete();

        return redirect()
            ->route('superadmin.pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    // ── Toggle Status ─────────────────────────────────────────────────────────

    public function toggleStatus(string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }

        $pengguna->update(['is_active' => ! $pengguna->is_active]);

        $status = $pengguna->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Pengguna berhasil {$status}.");
    }

    // ── Reset Password ────────────────────────────────────────────────────────

    public function resetPassword(Request $request, string $uuid): RedirectResponse
    {
        $pengguna = Pengguna::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $pengguna->update([
            'password'                    => Hash::make($request->password),
            'password_reset_token'        => null,
            'password_reset_token_expires_at' => null,
        ]);

        return back()->with('success', 'Password pengguna berhasil direset.');
    }
}