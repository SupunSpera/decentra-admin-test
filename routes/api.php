<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\WalletDepositController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\CryptoNetworkController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/confirm-deposit', [WalletDepositController::class, "confirmDeposit"]);
Route::get('/wallet-addresses', [WalletDepositController::class, "getWalletAddresses"]);
Route::get('/wallet-subscriptions', [WalletDepositController::class, "getWalletSubscriptions"]);
Route::get('/wallet/customer/{customerId}', [WalletDepositController::class, "getWalletByCustomerId"]);
Route::get('/crypto-networks', [CryptoNetworkController::class, "apiIndex"]);
Route::get('/network-tokens', [CryptoNetworkController::class, "apiNetworkTokens"]);
Route::post('/confirm-withdraw', [WithdrawalController::class, "confirmWithdraw"]);

Route::post('/upload/image', [ImageController::class, "uploadImage"]);

Route::post('/validate-simulated-session', [CustomerController::class, "validateSimulatedSession"])->middleware('auth.admin-api');

Route::post('/register/third-party-user', [CustomerController::class, "registerThirdPartyUser"])->middleware('auth.admin-api');

Route::post('/validate/third-party-user', [CustomerController::class, "validateThirdPartyUser"])->middleware('auth.admin-api');

Route::post('/add-points/third-party-user', [CustomerController::class, "addPointsThirdPartyUser"])->middleware('auth.admin-api');

Route::post('/get-commission/third-party-user', [CustomerController::class, "getCommissionsThirdPartyUser"])->middleware('auth.admin-api');

// Testing Routes - For development and testing purposes only
// Access via: /api/testing/*
Route::prefix('testing')->group(function () {
    // Add customers to tree
    Route::post('/customers/add-to-tree', [TestingController::class, 'addCustomersToTree'])
        ->name('api.testing.customers.add-to-tree');
    
    // Add points to users
    Route::post('/points/add-to-user', [TestingController::class, 'addPointsToUser'])
        ->name('api.testing.points.add-to-user');
    
    Route::post('/points/add-to-multiple-users', [TestingController::class, 'addPointsToMultipleUsers'])
        ->name('api.testing.points.add-to-multiple-users');
    
    // Generate and check supporting bonuses
    Route::post('/bonuses/generate', [TestingController::class, 'generateSupportingBonuses'])
        ->name('api.testing.bonuses.generate');
    
    Route::get('/bonuses/check', [TestingController::class, 'checkSupportingBonuses'])
        ->name('api.testing.bonuses.check');
    
    // Get tree statistics
    Route::get('/tree/stats', [TestingController::class, 'getTreeStats'])
        ->name('api.testing.tree.stats');
    
    // Get customer details
    Route::get('/customer/details', [TestingController::class, 'getCustomerDetails'])
        ->name('api.testing.customer.details');
    
    // Clear test data (use with caution!)
    Route::post('/clear-test-data', [TestingController::class, 'clearTestData'])
        ->name('api.testing.clear-test-data');
});
