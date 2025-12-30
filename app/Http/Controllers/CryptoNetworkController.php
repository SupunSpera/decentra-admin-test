<?php

namespace App\Http\Controllers;

use domain\Facades\CryptoNetworkFacade;
use Illuminate\Http\Request;
use App\Models\CryptoNetwork;

class CryptoNetworkController extends Controller
{
    /**
     * Display all crypto networks.
     */
    public function all()
    {
        $networks = CryptoNetworkFacade::all();

        return view('pages.crypto_networks.all', compact('networks'));
    }

    /**
     * Show create form.
     */
    public function new()
    {
        return view('pages.crypto_networks.new');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $network = CryptoNetworkFacade::get($id);

        return view('pages.crypto_networks.edit', compact('id', 'network'));
    }

    /**
     * Show view page.
     */
    public function view($id)
    {
        $network = CryptoNetworkFacade::get($id);

        return view('pages.crypto_networks.view', compact('id', 'network'));
    }

    /**
     * Store a newly created CryptoNetwork.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'chain_id' => 'required|string|max:255',
            'rpc_http' => 'required|string|max:255',
            'rpc_ws' => 'nullable|string|max:255',
            'tokens' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        CryptoNetworkFacade::create($data);

        return redirect()->route('crypto-networks.all')
            ->with('success', 'Crypto network created successfully.');
    }

    /**
     * Update the specified CryptoNetwork.
     */
    public function update(Request $request, $id)
    {
        $network = CryptoNetworkFacade::get($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'chain_id' => 'required|string|max:255',
            'rpc_http' => 'required|string|max:255',
            'rpc_ws' => 'nullable|string|max:255',
            'tokens' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');

        CryptoNetworkFacade::update($network, $data);

        return redirect()->route('crypto-networks.all')
            ->with('success', 'Crypto network updated successfully.');
    }

    /**
     * Remove the specified CryptoNetwork.
     */
    public function destroy($id)
    {
        $network = CryptoNetworkFacade::get($id);
        CryptoNetworkFacade::delete($network);

        return redirect()->route('crypto-networks.all')
            ->with('success', 'Crypto network deleted successfully.');
    }

    /**
     * API: return active crypto networks for the listener service.
     */
    public function apiIndex()
    {
        $networks = CryptoNetworkFacade::getActive();

        return response()->json([
            'success' => true,
            'networks' => $networks,
        ]);
    }

    /**
     * API: Get network tokens grouped by chain_id
     * Returns format: { tokens: { chain_id: { TOKEN_SYMBOL: contract_address } } }
     */
    public function apiNetworkTokens()
    {
        try {
            $tokensMap = CryptoNetworkFacade::getNetworkTokensMap();

            return response()->json([
                'success' => true,
                'tokens' => $tokensMap,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching network tokens: ' . $e->getMessage()
            ], 500);
        }
    }
}


