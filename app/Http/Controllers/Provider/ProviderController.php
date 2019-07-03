<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Sellable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));

        return view('provider.dashboard')->with([
            'sellable' => Sellable::where('owner_id', Auth::id())->orderBy('created_at')->first(),
        ]);
    }

}
