<?php

use App\Http\Controllers\ConnectedProjectsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerGiftController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\InstituteController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\SupportingBonusController;
use App\Http\Controllers\InstitutionalBonusController;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UrbxRedeemController;
use App\Http\Controllers\CryptoNetworkController;
use domain\Facades\WalletFacade;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, "home"])->middleware(['auth'])->name('home');
// Route::get('/test', [TestController::class, "test"])->name('test');

//User Management
Route::prefix('users')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, "all"])->name('users.all');
    Route::get('/new', [UserController::class, "new"])->name('users.new');
});

//Customer Management
Route::prefix('customers')->middleware(['auth'])->group(function () {
    Route::get('/', [CustomerController::class, "all"])->name('customers.all');
    Route::get('/new', [CustomerController::class, "new"])->name('customers.new');
    Route::get('/{id}', [CustomerController::class, "view"])->name('customers.view');
    Route::get('/{id}/edit', [CustomerController::class, "edit"])->name('customers.edit');
    Route::get('/{id}/delete', [CustomerController::class, "new"])->name('customers.delete');
    Route::get('/{id}/wallet', [CustomerController::class, "wallet"])->name('customers.wallet');
    Route::get('/{id}/referrals', [CustomerController::class, "referrals"])->name('customers.referrals');
});

//Referral Management
Route::prefix('referrals')->middleware(['auth'])->group(function () {
    Route::get('/tree', [ReferralController::class, "tree"])->name('referrals.tree');
    Route::get('/tree-view/{rootId?}', [ReferralController::class, "treeView"])->name('referrals.tree-view');
});

//Product Management
Route::prefix('products')->middleware(['auth'])->group(function () {
    Route::get('/', [ProductController::class, "all"])->name('products.all');
    Route::get('/new', [ProductController::class, "new"])->name('products.new');
    Route::get('/{id}', [ProductController::class, "view"])->name('products.view');
    Route::get('/{id}/edit', [ProductController::class, "edit"])->name('products.edit');
    Route::get('/{id}/terms', [ProductController::class, "terms"])->name('products.terms');
});

//Milestone Management
Route::prefix('milestones')->middleware(['auth'])->group(function () {
    Route::get('/', [MilestoneController::class, "all"])->name('milestones.all');
    Route::get('/new', [MilestoneController::class, "new"])->name('milestones.new');
    Route::get('/{id}', [MilestoneController::class, "view"])->name('milestones.view');
    Route::get('/{id}/edit', [MilestoneController::class, "edit"])->name('milestones.edit');
});

//Item Management
Route::prefix('items')->middleware(['auth'])->group(function () {
    Route::get('/', [ItemController::class, "all"])->name('items.all');
    Route::get('/new', [ItemController::class, "new"])->name('items.new');
    Route::get('/{id}', [ItemController::class, "view"])->name('items.view');
    Route::get('/{id}/edit', [ItemController::class, "edit"])->name('items.edit');
});

//Supporting Bonus
Route::prefix('supporting_bonus')->middleware(['auth'])->group(function () {
    Route::get('/', [SupportingBonusController::class, "all"])->name('supporting_bonus.all');
    Route::get('/allocated_shares', [SupportingBonusController::class, "allocatedShares"])->name('supporting_bonus.allocated_shares');
    Route::get('/generated_bonus', [SupportingBonusController::class, "generatedBonus"])->name('supporting_bonus.generated_bonus');
    Route::get('/supporting_bonus_calculate', [SupportingBonusController::class, "calculateSupportingBonus"])->name('calculate_bonus');
});

//Institutional Bonus
Route::prefix('institutional_bonus')->middleware(['auth'])->group(function () {
    Route::get('/', [InstitutionalBonusController::class, "all"])->name('institutional_bonus.all');
});

//Institutes
// Route::prefix('institutes')->middleware(['auth'])->group(function () {
//     Route::get('/', [InstituteController::class, "all"])->name('institutes.all');
//     Route::get('/{id}', [InstituteController::class, "view"])->name('institutes.view');
//     Route::get('/{id}/wallet', [InstituteController::class, "wallet"])->name('institutes.wallet');
//     Route::get('/{id}/members', [InstituteController::class, "refMembers"])->name('institutes.ref-members');
//     Route::get('/members', [InstituteController::class, "members"])->name('institutes.members');
// });

//Withdrawals
Route::prefix('withdrawals')->middleware(['auth'])->group(function () {
    Route::get('/pending', [WithdrawalController::class, "pending"])->name('withdrawals.pending');
    Route::get('/approved', [WithdrawalController::class, "approved"])->name('withdrawals.approved');
    Route::get('/sent', [WithdrawalController::class, "sent"])->name('withdrawals.sent');
    Route::get('/rejected', [WithdrawalController::class, "rejected"])->name('withdrawals.rejected');
});

Route::prefix('urbx_withdrawals')->middleware(['auth'])->group(function () {
    Route::get('/pending', [UrbxRedeemController::class, "pending"])->name('urbx-withdrawals.pending');
    // Route::get('/approved', [WithdrawalController::class, "approved"])->name('withdrawals.approved');
    // Route::get('/sent', [WithdrawalController::class, "sent"])->name('withdrawals.sent');
    // Route::get('/rejected', [WithdrawalController::class, "rejected"])->name('withdrawals.rejected');
});

//Gift Management
Route::prefix('gifts')->middleware(['auth'])->group(function () {
    Route::get('/', [GiftController::class, "all"])->name('gifts.all');
    Route::get('/new', [GiftController::class, "new"])->name('gifts.new');
    Route::get('/{id}', [GiftController::class, "view"])->name('gifts.view');
    Route::get('/{id}/edit', [GiftController::class, "edit"])->name('gifts.edit');
});

Route::prefix('projects')->middleware(['auth'])->group(function () {
    Route::get('/', [ProjectController::class, "all"])->name('projects.all');
    Route::get('/new', [ProjectController::class, "new"])->name('projects.new');
    Route::get('/{id}', [ProjectController::class, "view"])->name('projects.view');
    Route::get('/{id}/edit', [ProjectController::class, "edit"])->name('projects.edit');
    Route::get('/{id}/updates', [ProjectController::class, "updates"])->name('projects.updates');
    Route::get('/{id}/terms', [ProjectController::class, "terms"])->name('projects.terms');
    Route::get('/{id}/updates/new', [ProjectController::class, "createUpdate"])->name('projects.updates.new');
    Route::get('/{id}/updates/edit', [ProjectController::class, "editUpdate"])->name('projects.updates.edit');
});

Route::prefix('gift_purchases')->middleware(['auth'])->group(function () {
    Route::get('/', [CustomerGiftController::class, "all"])->name('customer_gifts.all');
});

//Settings
Route::prefix('settings')->middleware(['auth'])->group(function () {
    Route::get('/', [SettingController::class, "all"])->name('settings.all');
});

//Connected Projects
Route::prefix('connected-projects')->middleware(['auth'])->group(function () {
    Route::get('/', [ConnectedProjectsController::class, "all"])->name('connected-projects.all');
    Route::get('/new', [ConnectedProjectsController::class, "new"])->name('connected-projects.new');
    Route::get('/{id}/edit', [ConnectedProjectsController::class, "edit"])->name('connected-projects.edit');

});

// Crypto Networks
Route::prefix('crypto-networks')->middleware(['auth'])->group(function () {
    Route::get('/', [CryptoNetworkController::class, "all"])->name('crypto-networks.all');
    Route::get('/new', [CryptoNetworkController::class, "new"])->name('crypto-networks.new');
    Route::get('/{id}', [CryptoNetworkController::class, "view"])->name('crypto-networks.view');
    Route::get('/{id}/edit', [CryptoNetworkController::class, "edit"])->name('crypto-networks.edit');
    Route::post('/', [CryptoNetworkController::class, "store"])->name('crypto-networks.store');
    Route::put('/{id}', [CryptoNetworkController::class, "update"])->name('crypto-networks.update');
    Route::delete('/{id}', [CryptoNetworkController::class, "destroy"])->name('crypto-networks.destroy');
});

Route::prefix('reports')->middleware(['auth'])->group(function () {
    Route::get('/', [ReportController::class, "index"])->name('reports.index');
    Route::get('/export/{reportId}', [ReportController::class, 'export'])->name('reports.export');
    Route::get('/view/{reportId}', [ReportController::class, 'view'])->name('reports.view');
});

Route::get('/test', [TestController::class, 'test']);

require __DIR__ . '/auth.php';
