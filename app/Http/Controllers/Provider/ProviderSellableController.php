<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Sellable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;;

class ProviderSellableController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showSellable(Request $request){
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));
        return view('provider.list-sellable')->with(['sellables' => Sellable::where('owner_id', Auth::id())->orderBy('created_at')->get()]);
    }

    public function addSellable(Request $request){
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));

        return view('provider.add-sellable');
    }

    public function createSellable(Request $request){
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));

        $request->validate([
            'name' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
        ]);

        $sellable = new Sellable();
        $sellable->type = 1;
        $sellable->image = null;
        $sellable->name = $request->get('name');
        $sellable->price = $request->get('price');
        $sellable->owner()->associate(Auth::user());
        $sellable->description = $request->get('description');

        $sellable->save();

        return view('provider.manage-sellable')->with([
            'sellable' => $sellable,
            'success_message' => 'Sellable added successfully !',
        ]);
    }

    public function manageSellable(Request $request, $id){
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));

        return view('provider.update-sellable')->with('sellable', Sellable::find($id));
    }

    public function updateSellable(Request $request) {
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));

        $sellableId = $request->get('id');

        $sellable = Sellable::find($sellableId);

        $request->validate([
            'name' => ['required'],
            'price' => ['required'],
            'description' => ['required'],
        ]);

        $sellable->name = $request->get('name');
        $sellable->price = $request->get('price');
        $sellable->description = $request->get('description');

        $sellable->save();

        return view('provider.update-sellable')->with([
            'sellable' => Sellable::find($sellableId),
            'success_message' => 'Sellable updated successfully !',
        ]);
    }

    public function deleteSellable(Request $request){
        $request->user()->authorizeRoles(config('auth.ServiceProviderAuth'));

        Sellable::find($request->get('id'))->delete();

        return view('provider.list-sellable')->with([
            'sellables' => Sellable::where('owner_id', Auth::id())->orderBy('created_at')->get(),
            'success_message' => 'You have successfully deleted the sellable!',
        ]);

    }

}