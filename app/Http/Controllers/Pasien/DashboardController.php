<?php
namespace App\Http\Controllers\Pasien;
use App\Http\Controllers\Controller;
use App\Models\DaftarPoli;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
class DashboardController extends Controller
{
    public function index()
    {
        return view('pasien.dashboard',);
    }
}