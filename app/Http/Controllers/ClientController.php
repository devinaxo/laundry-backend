<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index() {
        return Client::where('active', true)->get();
    }

    public function all() {
        return Client::all();
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
