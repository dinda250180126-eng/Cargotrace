<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    public function index()
    {
        // Pastikan hanya admin yang bisa melihat CCTV ini
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil log dari yang paling baru
        $logs = AuditLog::latest()->get(); 
        return view('audit_logs.index', compact('logs'));
    }
}