<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Models\WalletRedeem;
use App\Models\WalletTransaction;
use domain\Facades\CurrencyPoolFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\DirectReferralBonusFacade;
use domain\Facades\InstitutionalBonusFacade;
use domain\Facades\InvestmentFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\SupportingBonusFacade;
use domain\Facades\TokenValueFacade;
use domain\Facades\WalletDepositFacade;
use domain\Facades\WalletFacade;
use domain\Facades\WalletRedeemFacade;
use domain\Facades\WalletTransactionFacade;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class WalletDepositController extends Controller
{
    /**
     * confirmDeposit
     * 
     * This function is called by the Node.js wallet transaction listener
     * when a deposit is detected on a monitored wallet address.
     *
     * @param  mixed $request
     * @return \Illuminate\Http\JsonResponse
     */
    function confirmDeposit(Request $request)
    {
        // Validate request
        $request->validate([
            'status' => 'required|string',
            'address' => 'required|string',
            'token_balance' => 'required|numeric|min:0',
        ]);

        if ($request->status == "success") {
            $walletAddress = $request->address;
            $depositedUSDTAmount = $request->token_balance;
            $transactionHash = $request->transaction_hash ?? null;
            $blockNumber = $request->block_number ?? null;

            DB::beginTransaction();

            try {
                $wallet = WalletFacade::getByWalletAddress($walletAddress);

                if (!$wallet) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Wallet not found for address: ' . $walletAddress
                    ], 404);
                }

                // TODO: Add transaction_hash and block_number fields to wallet_transactions table
                // to prevent duplicate processing and track blockchain transactions

                // calculate tex value
                $currencyPool = CurrencyPoolFacade::getFirst();

                if (!$currencyPool) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Currency pool not found'
                    ], 404);
                }

                $usdtAmount = floatval($currencyPool->usdt_amount);
                $texAmount = floatval($currencyPool->tex_amount);

                if (floatval($texAmount) > 0) {
                    $texValue = $usdtAmount / $texAmount;
                } else {
                    $texValue = 0;
                }

                // calculate tex amount based on tex value
                $depositedTEXAmount = $texValue > 0 ? floatval($depositedUSDTAmount) / $texValue : 0;

                // add deposit amount to customers wallet
                $walletUpdate = WalletFacade::update(
                    $wallet,
                    array(
                        'deposited_token_amount' => $wallet->deposited_token_amount + $depositedTEXAmount,
                        'usdt_amount' => $wallet->usdt_amount + $depositedUSDTAmount
                    )
                );

                if ($walletUpdate) {
                    // save current text value
                    TokenValueFacade::create(array('token_value' => $texValue));

                    // add usdt amount and reduce tex amount from pool
                    // CurrencyPoolFacade::update($currencyPool, array(
                    //     'usdt_amount' => $usdtAmount + $depositedUSDTAmount,
                    //     'tex_amount' => $texAmount - $depositedTEXAmount
                    // ));

                    // create wallet transaction
                    $walletTransaction = WalletTransactionFacade::create(
                        array(
                            'wallet_id' => $wallet->id,
                            'token_amount' => 0,
                            'usdt_amount' => $depositedUSDTAmount,
                            'from' => WalletTransaction::FROM['CUSTOMER']
                        )
                    );

                    // Log transaction hash and block number for reference (if provided)
                    if ($transactionHash || $blockNumber) {
                        Log::info('Wallet deposit transaction details', [
                            'wallet_id' => $wallet->id,
                            'transaction_id' => $walletTransaction->id,
                            'transaction_hash' => $transactionHash,
                            'block_number' => $blockNumber
                        ]);
                    }

                    $referral = ReferralFacade::getByCustomerId($wallet->customer_id);

                    // Delete wallet deposit request
                    $deposit = WalletDepositFacade::getByCustomerId($wallet->customer_id);
                    if ($deposit) {
                        WalletDepositFacade::delete($deposit);
                    }
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Deposit confirmed successfully',
                    'wallet_id' => $wallet->id,
                    'transaction_id' => $walletTransaction->id ?? null,
                    'deposited_usdt' => $depositedUSDTAmount,
                    'deposited_tex' => $depositedTEXAmount
                ], 200);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Wallet Deposit Exception: ' . $e->getMessage(), [
                    'exception' => $e,
                    'request' => $request->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet Deposit Exception: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid status'
        ], 400);
    }

    /**
     * getWalletAddresses
     * 
     * Returns all wallet addresses that need to be monitored by the listener
     * This endpoint is called by the Node.js listener service
     *
     * @param  Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function getWalletAddresses(Request $request)
    {
        try {
            $wallets = WalletFacade::all();
            $addresses = [];

            foreach ($wallets as $wallet) {
                if (!empty($wallet->eth_wallet_address)) {
                    $addresses[] = $wallet->eth_wallet_address;
                }
            }

            return response()->json([
                'success' => true,
                'addresses' => $addresses,
                'count' => count($addresses)
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching wallet addresses: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching wallet addresses: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get wallet subscriptions for the listener
     * Returns wallets with their network and monitored tokens
     */
    function getWalletSubscriptions()
    {
        try {
            $subscriptions = WalletFacade::getWalletSubscriptions();

            return response()->json([
                'success' => true,
                'subscriptions' => $subscriptions,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching wallet subscriptions: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching wallet subscriptions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * getWalletByCustomerId
     * 
     * Returns wallet details for a specific customer by customer ID
     * This endpoint is called to retrieve wallet information for a customer
     *
     * @param  Request $request
     * @param  int $customerId
     * @return \Illuminate\Http\JsonResponse
     */
    function getWalletByCustomerId(Request $request, $customerId)
    {
        try {
            $wallet = WalletFacade::getByCustomerId($customerId);

            if (!$wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet not found for customer ID: ' . $customerId
                ], 404);
            }

            return response()->json([
                'success' => true,
                'wallet' => [
                    'id' => $wallet->id,
                    'customer_id' => $wallet->customer_id,
                    'token_amount' => $wallet->token_amount,
                    'usdt_amount' => $wallet->usdt_amount,
                    'holding_tokens' => $wallet->holding_tokens,
                    'holding_usdt' => $wallet->holding_usdt,
                    'eth_wallet_address' => $wallet->eth_wallet_address,
                    'status' => $wallet->status,
                    'deposited_token_amount' => $wallet->deposited_token_amount,
                    'daily_share_cap' => $wallet->daily_share_cap,
                    'max_income_quota' => $wallet->max_income_quota,
                    'used_income_quota' => $wallet->used_income_quota,
                    'remaining_income_quota' => $wallet->max_income_quota - $wallet->used_income_quota,
                    'withdrawal_address' => $wallet->withdrawal_address,
                    'created_at' => $wallet->created_at,
                    'updated_at' => $wallet->updated_at,
                ]
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching wallet by customer ID: ' . $e->getMessage(), [
                'customer_id' => $customerId,
                'exception' => $e
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error fetching wallet: ' . $e->getMessage()
            ], 500);
        }
    }
}
