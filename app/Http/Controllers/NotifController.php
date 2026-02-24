<?php

// Tambahkan file ini di: app/Http/Controllers/NotifController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifController extends Controller
{
    /**
     * Tandai satu notifikasi sebagai sudah dibaca.
     * Menerima: { no: 'TRX-XXXX-XXXX' }
     * Menyimpan array no_transaksi yang sudah dibaca ke session.
     */
    public function markRead(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['ok' => false], 401);
        }

        $no = $request->input('no') ?? $request->input('id'); // support keduanya

        if ($no) {
            $sessionKey = 'notif_read_ids_' . $user->id;
            $readIds    = session($sessionKey, []);

            if (!in_array($no, $readIds)) {
                $readIds[] = $no;
            }

            // Batasi max 100 item supaya session tidak membengkak
            if (count($readIds) > 100) {
                $readIds = array_slice($readIds, -100);
            }

            session([$sessionKey => $readIds]);
        }

        return response()->json(['ok' => true]);
    }
}