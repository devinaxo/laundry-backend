<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewClientRequest;
use App\Http\Requests\PaginatedClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller {
    public function index() {
        return Client::where('active', true)->get();
    }

    public function all() {
        return Client::all();
    }

    public function paginated(PaginatedClientRequest $request) {
        $query = Client::query();

        if (!is_null($request->input('active'))) {
            $query->where('active', filter_var($request->input('active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('forename', 'like', "%{$search}%")
                    ->orWhere('surname', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $clients = $query->paginate($request->input('per_page', 10));
        return response()->json($clients);
    }

    public function show($id) {
        return Client::findOrFail($id);
    }

    public function store(NewClientRequest $request) {
        $client = Client::create($request->validated());
        return response()->json($client, 201);
    }

    public function update(NewClientRequest $request, Client $client) {
        $client->update($request->validated());
        return response()->json($client);
    }

    public function destroy(Client $client) {
        $client->update(['active' => false]);
        $client->update(['active' => false]);
        return response()->json(['message' => 'Client deactivated successfully']);
    }

    public function restore(Client $client) {
        $client->update(['active' => true]);
        return response()->json(['message' => 'Client reactivated successfully']);
    }
}
