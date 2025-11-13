<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $auth = Auth::user();
        $users = User::join('level', 'user.level_id', 'level.id')
            ->select([
                'user.id',
                'user.name',
                'user.username',
                'user.email',
                'user.password',
                'user.duplicate',
                'user.level_id',
                'user.telp',
                'user.foto_profile',
                'user.is_deleted',
                'level.namalevel',
            ])
            ->where('user.id', $auth->id)
            ->where('user.is_deleted', '1')
            ->orderBy('user.id', 'desc')
            ->first();
        return view('dashboard.main.index', [
            'users' => $users,
        ]);
    }
}
