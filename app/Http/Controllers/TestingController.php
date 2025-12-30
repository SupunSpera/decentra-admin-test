<?php

/**
 * TestingController
 * 
 * This controller provides comprehensive testing endpoints for the binary tree referral system.
 * 
 * ENDPOINTS:
 * 
 * 1. POST /testing/customers/add-to-tree
 *    - Create multiple customers in a balanced binary tree
 *    - Parameters: count (default: 1000), start_index (default: 1)
 * 
 * 2. POST /testing/points/add-to-user
 *    - Add points to a single customer with tree propagation
 *    - Parameters: customer_id, points, side (left/right)
 * 
 * 3. POST /testing/points/add-to-multiple-users
 *    - Add points to multiple customers in bulk
 *    - Parameters: users[] (customer_id, points, side)
 * 
 * 4. POST /testing/bonuses/generate
 *    - Generate supporting bonuses for customers
 *    - Parameters: amount (optional), customer_ids[] (optional)
 * 
 * 5. GET /testing/bonuses/check?customer_id=X
 *    - Check supporting bonuses for a specific customer
 *    - Parameters: customer_id
 * 
 * 6. GET /testing/tree/stats
 *    - Get comprehensive tree statistics
 * 
 * 7. GET /testing/customer/details?customer_id=X
 *    - Get detailed customer information with tree relationships
 *    - Parameters: customer_id
 * 
 * 8. POST /testing/clear-test-data
 *    - ⚠️ DANGEROUS: Clear all test data
 *    - Parameters: confirm (must be "YES_DELETE_ALL")
 * 
 * See TESTING_API_DOCUMENTATION.md for detailed usage examples.
 */

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerSupportingBonus;
use App\Models\Referral;
use App\Models\Wallet;
use Carbon\Carbon;
use domain\Facades\CustomerFacade;
use domain\Facades\CustomerSupportingBonusFacade;
use domain\Facades\ReferralFacade;
use domain\Facades\WalletFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestingController extends Controller
{
    /**
     * Generate a fake ETH wallet address
     *
     * @param int $index
     * @return string
     */
    private function generateFakeWalletAddress($index)
    {
        return '0x' . str_pad(dechex($index), 40, '0', STR_PAD_LEFT);
    }

    /**
     * Generate a fake private key
     *
     * @param int $index
     * @return string
     */
    private function generateFakePrivateKey($index)
    {
        return '0x' . hash('sha256', 'private_key_' . $index);
    }

    /**
     * Calculate level index for binary tree
     *
     * @param int $parentLevelIndex
     * @param int $childPosition (1 for left, 2 for right)
     * @return int
     */
    private function calculateLevelIndex($parentLevelIndex, $childPosition)
    {
        return ($parentLevelIndex * 2) - (2 - $childPosition);
    }

    /**
     * Add 1000 customers to the binary tree with hardcoded wallet addresses
     * Creates customers in a balanced binary tree structure
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCustomersToTree(Request $request)
    {
        $count = $request->input('count', 1000);
        $startIndex = $request->input('start_index', 1);

        DB::beginTransaction();

        try {
            $createdCustomers = [];
            $rootReferral = null;

            // Check if root customer exists
            $rootReferral = Referral::where('level', 1)->where('level_index', 1)->first();

            if (!$rootReferral) {
                // Create root customer
                $rootCustomer = Customer::create([
                    'first_name' => 'Test',
                    'last_name' => 'Root',
                    'email' => 'test_root_' . time() . '@test.com',
                    'telephone' => '1234567890',
                    'mobile' => '1234567890',
                    'password' => Hash::make('password123'),
                    'type' => Customer::TYPE['INDIVIDUAL'],
                    'status' => Customer::STATUS['ACTIVE'],
                    'active_status' => Customer::ACTIVE_STATUS['ACTIVE'],
                    'purchased_status' => Customer::PURCHASED_STATUS['ACTIVE'],
                ]);

                // Create wallet for root
                Wallet::create([
                    'customer_id' => $rootCustomer->id,
                    'token_amount' => 0,
                    'usdt_amount' => 0,
                    'holding_tokens' => 0,
                    'holding_usdt' => 0,
                    'eth_wallet_address' => $this->generateFakeWalletAddress($startIndex),
                    'eth_wallet_private_key' => $this->generateFakePrivateKey($startIndex),
                    'status' => 1,
                    'daily_share_cap' => 1000,
                    'max_income_quota' => 10000,
                    'used_income_quota' => 0,
                ]);

                // Create root referral
                $rootReferral = Referral::create([
                    'customer_id' => $rootCustomer->id,
                    'parent_referral_id' => null,
                    'direct_referral_id' => null,
                    'left_child_id' => null,
                    'right_child_id' => null,
                    'level' => 1,
                    'level_index' => 1,
                    'left_points' => 0,
                    'right_points' => 0,
                    'status' => 1,
                ]);

                $createdCustomers[] = [
                    'customer_id' => $rootCustomer->id,
                    'email' => $rootCustomer->email,
                    'referral_code' => $rootCustomer->referral_code,
                    'level' => 1,
                    'wallet_address' => $this->generateFakeWalletAddress($startIndex),
                ];

                $count--; // Reduce count since we created root
                $startIndex++;
            }

            // Create remaining customers
            for ($i = 0; $i < $count; $i++) {
                $customerIndex = $startIndex + $i;

                // Create customer
                $customer = Customer::create([
                    'first_name' => 'Test',
                    'last_name' => 'User' . $customerIndex,
                    'email' => 'test_user_' . $customerIndex . '_' . time() . '@test.com',
                    'telephone' => '123456' . str_pad($customerIndex, 4, '0', STR_PAD_LEFT),
                    'mobile' => '123456' . str_pad($customerIndex, 4, '0', STR_PAD_LEFT),
                    'password' => Hash::make('password123'),
                    'type' => Customer::TYPE['INDIVIDUAL'],
                    'status' => Customer::STATUS['ACTIVE'],
                    'active_status' => Customer::ACTIVE_STATUS['ACTIVE'],
                    'purchased_status' => Customer::PURCHASED_STATUS['ACTIVE'],
                ]);

                // Create wallet
                Wallet::create([
                    'customer_id' => $customer->id,
                    'token_amount' => 0,
                    'usdt_amount' => 0,
                    'holding_tokens' => 0,
                    'holding_usdt' => 0,
                    'eth_wallet_address' => $this->generateFakeWalletAddress($customerIndex),
                    'eth_wallet_private_key' => $this->generateFakePrivateKey($customerIndex),
                    'status' => 1,
                    'daily_share_cap' => 1000,
                    'max_income_quota' => 10000,
                    'used_income_quota' => 0,
                ]);

                // Find parent referral (find first referral with empty slot)
                $parentReferral = $this->findAvailableParent();

                if ($parentReferral) {
                    $level = $parentReferral->level + 1;

                    // Determine if this will be left or right child
                    if (!$parentReferral->left_child_id) {
                        $levelIndex = $this->calculateLevelIndex($parentReferral->level_index, 1);

                        // Create referral as left child
                        $referral = Referral::create([
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $parentReferral->id,
                            'direct_referral_id' => $parentReferral->id,
                            'left_child_id' => null,
                            'right_child_id' => null,
                            'level' => $level,
                            'level_index' => $levelIndex,
                            'left_points' => 0,
                            'right_points' => 0,
                            'status' => 1,
                        ]);

                        // Update parent's left child
                        $parentReferral->update(['left_child_id' => $referral->id]);
                    } else {
                        $levelIndex = $this->calculateLevelIndex($parentReferral->level_index, 2);

                        // Create referral as right child
                        $referral = Referral::create([
                            'customer_id' => $customer->id,
                            'parent_referral_id' => $parentReferral->id,
                            'direct_referral_id' => $parentReferral->id,
                            'left_child_id' => null,
                            'right_child_id' => null,
                            'level' => $level,
                            'level_index' => $levelIndex,
                            'left_points' => 0,
                            'right_points' => 0,
                            'status' => 1,
                        ]);

                        // Update parent's right child
                        $parentReferral->update(['right_child_id' => $referral->id]);
                    }

                    $createdCustomers[] = [
                        'customer_id' => $customer->id,
                        'email' => $customer->email,
                        'referral_code' => $customer->referral_code,
                        'referral_id' => $referral->id,
                        'level' => $level,
                        'level_index' => $levelIndex,
                        'parent_referral_id' => $parentReferral->id,
                        'wallet_address' => $this->generateFakeWalletAddress($customerIndex),
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Successfully created ' . count($createdCustomers) . ' customers in the tree',
                'customers' => $createdCustomers,
                'total_customers' => Customer::count(),
                'total_referrals' => Referral::count(),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create customers: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Find the next available parent in the tree
     *
     * @return Referral|null
     */
    private function findAvailableParent()
    {
        // Find first referral with at least one empty child slot
        return Referral::where(function ($query) {
            $query->whereNull('left_child_id')
                ->orWhereNull('right_child_id');
        })
            ->orderBy('level', 'asc')
            ->orderBy('level_index', 'asc')
            ->first();
    }

    /**
     * Add points to a specific user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPointsToUser(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'points' => 'required|numeric|min:0',
            'side' => 'required|in:left,right',
        ]);

        try {
            $customerId = $request->input('customer_id');
            $points = $request->input('points');
            $side = $request->input('side');

            $referral = Referral::where('customer_id', $customerId)->first();

            if (!$referral) {
                return response()->json([
                    'success' => false,
                    'message' => 'Referral not found for this customer',
                ], 404);
            }

            // Add points to the specified side and propagate up the tree
            $this->addPointsToParents($referral->id, $points, $side, $customerId);

            $referral->refresh();

            return response()->json([
                'success' => true,
                'message' => "Successfully added {$points} points to {$side} side of customer {$customerId}",
                'referral' => [
                    'id' => $referral->id,
                    'customer_id' => $referral->customer_id,
                    'level' => $referral->level,
                    'left_points' => $referral->left_points,
                    'right_points' => $referral->right_points,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add points: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add points to multiple users
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addPointsToMultipleUsers(Request $request)
    {
        $request->validate([
            'users' => 'required|array',
            'users.*.customer_id' => 'required|integer|exists:customers,id',
            'users.*.points' => 'required|numeric|min:0',
            'users.*.side' => 'nullable|in:left,right',
        ]);

        DB::beginTransaction();

        try {
            $users = $request->input('users');
            $results = [];

            foreach ($users as $userData) {
                $customerId = $userData['customer_id'];
                $points = $userData['points'];
                $side = $userData['side'] ?? 'left'; // Default to left if not specified

                $referral = Referral::where('customer_id', $customerId)->first();

                if ($referral) {
                    $this->addPointsToParents($referral->id, $points, $side, $customerId);

                    $referral->refresh();

                    $results[] = [
                        'customer_id' => $customerId,
                        'success' => true,
                        'points_added' => $points,
                        'side' => $side,
                        'left_points' => $referral->left_points,
                        'right_points' => $referral->right_points,
                    ];
                } else {
                    $results[] = [
                        'customer_id' => $customerId,
                        'success' => false,
                        'message' => 'Referral not found',
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Points added to multiple users',
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add points: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add points to parents recursively
     *
     * @param int $referralId
     * @param float $points
     * @param string $side
     * @param int $originCustomerId
     */
    private function addPointsToParents($referralId, $points, $side, $originCustomerId)
    {
        $currentReferral = Referral::find($referralId);

        if (!$currentReferral || !$currentReferral->parent_referral_id) {
            return; // Stop if no parent
        }

        $parentReferral = Referral::find($currentReferral->parent_referral_id);

        if (!$parentReferral) {
            return;
        }

        // Create supporting bonus record
        CustomerSupportingBonus::create([
            'customer_id' => $originCustomerId,
            'referral_id' => $parentReferral->id,
            'amount' => $points,
            'status' => CustomerSupportingBonus::STATUS['PENDING'],
        ]);

        // Determine which side to add points to
        $parentSide = null;
        if ($parentReferral->left_child_id == $currentReferral->id) {
            $parentSide = 'left';
            $parentReferral->left_points += $points;
        } elseif ($parentReferral->right_child_id == $currentReferral->id) {
            $parentSide = 'right';
            $parentReferral->right_points += $points;
        }

        $parentReferral->save();

        // Recursively add to grandparents
        if ($parentSide) {
            $this->addPointsToParents($parentReferral->id, $points, $parentSide, $originCustomerId);
        }
    }

    /**
     * Generate supporting bonuses for customers based on their tree structure
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateSupportingBonuses(Request $request)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'customer_ids' => 'nullable|array',
            'customer_ids.*' => 'integer|exists:customers,id',
        ]);

        DB::beginTransaction();

        try {
            $amount = $request->input('amount', 100); // Default amount
            $customerIds = $request->input('customer_ids', []);

            $referrals = collect();

            if (empty($customerIds)) {
                // Generate for all referrals that have children
                $referrals = Referral::whereNotNull('left_child_id')
                    ->orWhereNotNull('right_child_id')
                    ->get();
            } else {
                // Generate for specific customers
                $referrals = Referral::whereIn('customer_id', $customerIds)->get();
            }

            $generated = [];

            foreach ($referrals as $referral) {
                // Get all child referrals on left and right
                $leftChildren = $this->getChildReferrals($referral->id, 'left');
                $rightChildren = $this->getChildReferrals($referral->id, 'right');

                $leftCount = count($leftChildren);
                $rightCount = count($rightChildren);

                if ($leftCount > 0 || $rightCount > 0) {
                    // Create supporting bonuses for each child
                    foreach ($leftChildren as $childReferral) {
                        $bonus = CustomerSupportingBonus::create([
                            'customer_id' => $childReferral->customer_id,
                            'referral_id' => $referral->id,
                            'amount' => $amount,
                            'status' => CustomerSupportingBonus::STATUS['PENDING'],
                        ]);

                        // Update parent's left points
                        $referral->left_points += $amount;
                    }

                    foreach ($rightChildren as $childReferral) {
                        $bonus = CustomerSupportingBonus::create([
                            'customer_id' => $childReferral->customer_id,
                            'referral_id' => $referral->id,
                            'amount' => $amount,
                            'status' => CustomerSupportingBonus::STATUS['PENDING'],
                        ]);

                        // Update parent's right points
                        $referral->right_points += $amount;
                    }

                    $referral->save();

                    $generated[] = [
                        'referral_id' => $referral->id,
                        'customer_id' => $referral->customer_id,
                        'left_children' => $leftCount,
                        'right_children' => $rightCount,
                        'left_points' => $referral->left_points,
                        'right_points' => $referral->right_points,
                        'bonuses_created' => $leftCount + $rightCount,
                    ];
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Supporting bonuses generated successfully',
                'generated' => $generated,
                'total_bonuses' => array_sum(array_column($generated, 'bonuses_created')),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate supporting bonuses: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all child referrals on a specific side
     *
     * @param int $referralId
     * @param string $side
     * @return array
     */
    private function getChildReferrals($referralId, $side)
    {
        $children = [];
        $referral = Referral::find($referralId);

        if (!$referral) {
            return $children;
        }

        $childId = ($side == 'left') ? $referral->left_child_id : $referral->right_child_id;

        if (!$childId) {
            return $children;
        }

        $queue = [$childId];

        while (!empty($queue)) {
            $currentId = array_shift($queue);
            $currentReferral = Referral::find($currentId);

            if ($currentReferral) {
                $children[] = $currentReferral;

                if ($currentReferral->left_child_id) {
                    $queue[] = $currentReferral->left_child_id;
                }

                if ($currentReferral->right_child_id) {
                    $queue[] = $currentReferral->right_child_id;
                }
            }
        }

        return $children;
    }

    /**
     * Check if supporting bonuses are generated correctly for a specific customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkSupportingBonuses(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
        ]);

        try {
            $customerId = $request->input('customer_id');

            $customer = Customer::find($customerId);
            $referral = Referral::where('customer_id', $customerId)->first();

            if (!$referral) {
                return response()->json([
                    'success' => false,
                    'message' => 'Referral not found for this customer',
                ], 404);
            }

            // Get supporting bonuses where this customer is the recipient
            $bonusesReceived = CustomerSupportingBonus::where('customer_id', $customerId)
                ->with('customer')
                ->get();

            // Get supporting bonuses generated under this customer's referral
            $bonusesGenerated = CustomerSupportingBonus::where('referral_id', $referral->id)
                ->with('customer')
                ->get();

            // Get child referrals
            $leftChildren = $this->getChildReferrals($referral->id, 'left');
            $rightChildren = $this->getChildReferrals($referral->id, 'right');

            // Calculate totals
            $leftBonusTotal = CustomerSupportingBonus::where('referral_id', $referral->id)
                ->whereIn('customer_id', array_column($leftChildren, 'customer_id'))
                ->sum('amount');

            $rightBonusTotal = CustomerSupportingBonus::where('referral_id', $referral->id)
                ->whereIn('customer_id', array_column($rightChildren, 'customer_id'))
                ->sum('amount');

            // Get today's bonuses
            $todayBonuses = CustomerSupportingBonus::where('referral_id', $referral->id)
                ->whereDate('created_at', Carbon::today())
                ->get();

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'email' => $customer->email,
                    'referral_code' => $customer->referral_code,
                ],
                'referral' => [
                    'id' => $referral->id,
                    'level' => $referral->level,
                    'level_index' => $referral->level_index,
                    'left_points' => $referral->left_points,
                    'right_points' => $referral->right_points,
                ],
                'tree_structure' => [
                    'left_children_count' => count($leftChildren),
                    'right_children_count' => count($rightChildren),
                    'total_children' => count($leftChildren) + count($rightChildren),
                ],
                'bonus_summary' => [
                    'bonuses_received_count' => $bonusesReceived->count(),
                    'bonuses_received_total' => $bonusesReceived->sum('amount'),
                    'bonuses_generated_count' => $bonusesGenerated->count(),
                    'bonuses_generated_total' => $bonusesGenerated->sum('amount'),
                    'left_side_bonus_total' => $leftBonusTotal,
                    'right_side_bonus_total' => $rightBonusTotal,
                    'today_bonuses_count' => $todayBonuses->count(),
                    'today_bonuses_total' => $todayBonuses->sum('amount'),
                ],
                'validation' => [
                    'left_points_match' => ($referral->left_points == $leftBonusTotal),
                    'right_points_match' => ($referral->right_points == $rightBonusTotal),
                    'points_difference' => [
                        'left' => $referral->left_points - $leftBonusTotal,
                        'right' => $referral->right_points - $rightBonusTotal,
                    ],
                ],
                'bonuses_received' => $bonusesReceived->map(function ($bonus) {
                    return [
                        'id' => $bonus->id,
                        'amount' => $bonus->amount,
                        'referral_id' => $bonus->referral_id,
                        'status' => $bonus->status,
                        'created_at' => $bonus->created_at->toDateTimeString(),
                    ];
                }),
                'bonuses_generated' => $bonusesGenerated->map(function ($bonus) {
                    return [
                        'id' => $bonus->id,
                        'customer_id' => $bonus->customer_id,
                        'customer_email' => $bonus->customer->email ?? 'N/A',
                        'amount' => $bonus->amount,
                        'status' => $bonus->status,
                        'created_at' => $bonus->created_at->toDateTimeString(),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check supporting bonuses: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Get tree statistics
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTreeStats()
    {
        try {
            $totalCustomers = Customer::count();
            $totalReferrals = Referral::count();
            $totalBonuses = CustomerSupportingBonus::count();

            $maxLevel = Referral::max('level');
            $customersByLevel = Referral::select('level', DB::raw('count(*) as count'))
                ->groupBy('level')
                ->orderBy('level')
                ->get();

            $totalLeftPoints = Referral::sum('left_points');
            $totalRightPoints = Referral::sum('right_points');

            $todayBonuses = CustomerSupportingBonus::whereDate('created_at', Carbon::today())->count();
            $todayBonusTotal = CustomerSupportingBonus::whereDate('created_at', Carbon::today())->sum('amount');

            return response()->json([
                'success' => true,
                'statistics' => [
                    'total_customers' => $totalCustomers,
                    'total_referrals' => $totalReferrals,
                    'total_bonuses' => $totalBonuses,
                    'max_level' => $maxLevel,
                    'total_left_points' => $totalLeftPoints,
                    'total_right_points' => $totalRightPoints,
                    'total_points' => $totalLeftPoints + $totalRightPoints,
                    'today_bonuses_count' => $todayBonuses,
                    'today_bonuses_total' => $todayBonusTotal,
                ],
                'customers_by_level' => $customersByLevel,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get tree stats: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear all test data (use with caution!)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearTestData(Request $request)
    {
        $request->validate([
            'confirm' => 'required|in:YES_DELETE_ALL',
        ]);

        DB::beginTransaction();

        try {
            // Get count before deletion
            $customerCount = Customer::count();
            $referralCount = Referral::count();
            $bonusCount = CustomerSupportingBonus::count();
            $walletCount = Wallet::count();

            // Delete in correct order to avoid foreign key constraints
            CustomerSupportingBonus::truncate();
            Referral::truncate();
            Wallet::truncate();
            Customer::truncate();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'All test data cleared successfully',
                'deleted' => [
                    'customers' => $customerCount,
                    'referrals' => $referralCount,
                    'bonuses' => $bonusCount,
                    'wallets' => $walletCount,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to clear test data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get customer details with tree information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerDetails(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
        ]);

        try {
            $customerId = $request->input('customer_id');

            $customer = Customer::with('wallet')->find($customerId);
            $referral = Referral::where('customer_id', $customerId)->first();

            if (!$referral) {
                return response()->json([
                    'success' => false,
                    'message' => 'Referral not found for this customer',
                ], 404);
            }

            // Get parent
            $parent = null;
            if ($referral->parent_referral_id) {
                $parentReferral = Referral::find($referral->parent_referral_id);
                if ($parentReferral) {
                    $parentCustomer = Customer::find($parentReferral->customer_id);
                    $parent = [
                        'customer_id' => $parentCustomer->id,
                        'email' => $parentCustomer->email,
                        'referral_code' => $parentCustomer->referral_code,
                    ];
                }
            }

            // Get children
            $leftChild = null;
            if ($referral->left_child_id) {
                $leftReferral = Referral::find($referral->left_child_id);
                if ($leftReferral) {
                    $leftCustomer = Customer::find($leftReferral->customer_id);
                    $leftChild = [
                        'customer_id' => $leftCustomer->id,
                        'email' => $leftCustomer->email,
                        'referral_code' => $leftCustomer->referral_code,
                    ];
                }
            }

            $rightChild = null;
            if ($referral->right_child_id) {
                $rightReferral = Referral::find($referral->right_child_id);
                if ($rightReferral) {
                    $rightCustomer = Customer::find($rightReferral->customer_id);
                    $rightChild = [
                        'customer_id' => $rightCustomer->id,
                        'email' => $rightCustomer->email,
                        'referral_code' => $rightCustomer->referral_code,
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                    'referral_code' => $customer->referral_code,
                ],
                'wallet' => [
                    'eth_wallet_address' => $customer->wallet->eth_wallet_address ?? 'N/A',
                    'token_amount' => $customer->wallet->token_amount ?? 0,
                    'usdt_amount' => $customer->wallet->usdt_amount ?? 0,
                ],
                'referral' => [
                    'id' => $referral->id,
                    'level' => $referral->level,
                    'level_index' => $referral->level_index,
                    'left_points' => $referral->left_points,
                    'right_points' => $referral->right_points,
                ],
                'tree_relationships' => [
                    'parent' => $parent,
                    'left_child' => $leftChild,
                    'right_child' => $rightChild,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get customer details: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a single customer and automatically add to tree
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createCustomer(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:customers,email',
            'telephone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
        ]);

        DB::beginTransaction();

        try {
            $password = $request->input('password', 'password123');

            // Create customer
            $customer = Customer::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'telephone' => $request->input('telephone', '0000000000'),
                'mobile' => $request->input('mobile', '0000000000'),
                'password' => Hash::make($password),
                'type' => Customer::TYPE['INDIVIDUAL'],
                'status' => Customer::STATUS['ACTIVE'],
                'active_status' => Customer::ACTIVE_STATUS['ACTIVE'],
                'purchased_status' => Customer::PURCHASED_STATUS['INACTIVE'],
            ]);

            // Generate fake wallet address for testing
            $walletAddress = $this->generateFakeWalletAddress($customer->id);
            $privateKey = $this->generateFakePrivateKey($customer->id);

            // Create wallet
            Wallet::create([
                'customer_id' => $customer->id,
                'token_amount' => 0,
                'usdt_amount' => 0,
                'holding_tokens' => 0,
                'holding_usdt' => 0,
                'eth_wallet_address' => $walletAddress,
                'eth_wallet_private_key' => $privateKey,
                'status' => 1,
                'daily_share_cap' => 1000,
                'max_income_quota' => 10000,
                'used_income_quota' => 0,
            ]);

            // Find available parent in tree
            $parentReferral = $this->findAvailableParent();

            if (!$parentReferral) {
                // No tree exists, create as root
                $referral = Referral::create([
                    'customer_id' => $customer->id,
                    'parent_referral_id' => null,
                    'direct_referral_id' => null,
                    'left_child_id' => null,
                    'right_child_id' => null,
                    'level' => 1,
                    'level_index' => 1,
                    'left_points' => 0,
                    'right_points' => 0,
                    'status' => 1,
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Customer created successfully as root of tree',
                    'customer' => [
                        'id' => $customer->id,
                        'first_name' => $customer->first_name,
                        'last_name' => $customer->last_name,
                        'email' => $customer->email,
                        'referral_code' => $customer->referral_code,
                        'wallet_address' => $walletAddress,
                    ],
                    'referral' => [
                        'id' => $referral->id,
                        'level' => $referral->level,
                        'level_index' => $referral->level_index,
                        'parent_referral_id' => null,
                    ],
                ]);
            }

            // Add customer to tree
            $level = $parentReferral->level + 1;

            // Determine if this will be left or right child
            if (!$parentReferral->left_child_id) {
                $levelIndex = $this->calculateLevelIndex($parentReferral->level_index, 1);
                $position = 'left';

                // Create referral as left child
                $referral = Referral::create([
                    'customer_id' => $customer->id,
                    'parent_referral_id' => $parentReferral->id,
                    'direct_referral_id' => $parentReferral->id,
                    'left_child_id' => null,
                    'right_child_id' => null,
                    'level' => $level,
                    'level_index' => $levelIndex,
                    'left_points' => 0,
                    'right_points' => 0,
                    'status' => 1,
                ]);

                // Update parent's left child
                $parentReferral->update(['left_child_id' => $referral->id]);
            } else {
                $levelIndex = $this->calculateLevelIndex($parentReferral->level_index, 2);
                $position = 'right';

                // Create referral as right child
                $referral = Referral::create([
                    'customer_id' => $customer->id,
                    'parent_referral_id' => $parentReferral->id,
                    'direct_referral_id' => $parentReferral->id,
                    'left_child_id' => null,
                    'right_child_id' => null,
                    'level' => $level,
                    'level_index' => $levelIndex,
                    'left_points' => 0,
                    'right_points' => 0,
                    'status' => 1,
                ]);

                // Update parent's right child
                $parentReferral->update(['right_child_id' => $referral->id]);
            }

            DB::commit();

            $parentCustomer = Customer::find($parentReferral->customer_id);

            return response()->json([
                'success' => true,
                'message' => 'Customer created and added to tree successfully',
                'customer' => [
                    'id' => $customer->id,
                    'first_name' => $customer->first_name,
                    'last_name' => $customer->last_name,
                    'email' => $customer->email,
                    'referral_code' => $customer->referral_code,
                    'wallet_address' => $walletAddress,
                ],
                'referral' => [
                    'id' => $referral->id,
                    'level' => $level,
                    'level_index' => $levelIndex,
                    'parent_referral_id' => $parentReferral->id,
                    'position' => $position,
                ],
                'parent' => [
                    'customer_id' => $parentCustomer->id,
                    'email' => $parentCustomer->email,
                    'referral_code' => $parentCustomer->referral_code,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create customer: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Create a product purchase for a customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProductPurchase(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'product_id' => 'required|integer|exists:products,id',
        ]);

        DB::beginTransaction();

        try {
            $customerId = $request->input('customer_id');
            $productId = $request->input('product_id');

            $customer = Customer::find($customerId);
            $product = \App\Models\Product::find($productId);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }

            // Calculate income quota (typically 3x the product price)
            $maxIncomeQuota = $product->price * 3;

            // Create product purchase
            $productPurchase = \App\Models\ProductPurchase::create([
                'customer_id' => $customerId,
                'product_id' => $productId,
                'max_income_quota' => $maxIncomeQuota,
                'remaining_income_quota' => $maxIncomeQuota,
                'income_quota_status' => \App\Models\ProductPurchase::INCOME_QUOTA_STATUS['AVAILABLE'],
                'status' => \App\Models\ProductPurchase::STATUS['AVAILABLE'],
            ]);

            // Update customer's purchased status
            $customer->update([
                'purchased_status' => Customer::PURCHASED_STATUS['ACTIVE'],
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Product purchase created successfully',
                'purchase' => [
                    'id' => $productPurchase->id,
                    'customer_id' => $customerId,
                    'customer_email' => $customer->email,
                    'customer_referral_code' => $customer->referral_code,
                    'product_id' => $productId,
                    'product_name' => $product->name,
                    'product_price' => $product->price,
                    'product_points' => $product->points,
                    'max_income_quota' => $maxIncomeQuota,
                    'remaining_income_quota' => $maxIncomeQuota,
                    'created_at' => $productPurchase->created_at->toDateTimeString(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create product purchase: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }

    /**
     * Create a project investment for a customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProjectInvestment(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|integer|exists:customers,id',
            'project_id' => 'required|integer|exists:projects,id',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $customerId = $request->input('customer_id');
            $projectId = $request->input('project_id');
            $amount = $request->input('amount');

            $customer = Customer::find($customerId);
            $project = \App\Models\Project::find($projectId);

            if (!$project) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found',
                ], 404);
            }

            // Check minimum investment
            if ($amount < $project->minimum_investment) {
                return response()->json([
                    'success' => false,
                    'message' => "Investment amount must be at least {$project->minimum_investment}",
                ], 422);
            }

            // Calculate points based on project points ratio
            $points = $project->points ? ($amount * $project->points / 100) : 0;

            // Create project investment
            $projectInvestment = \App\Models\ProjectInvestment::create([
                'customer_id' => $customerId,
                'project_id' => $projectId,
                'amount' => $amount,
                'points' => $points,
                'status' => \App\Models\ProjectInvestment::STATUS['COMPLETED'],
            ]);

            // Update project invested amount
            $project->update([
                'invested_amount' => $project->invested_amount + $amount,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project investment created successfully',
                'investment' => [
                    'id' => $projectInvestment->id,
                    'customer_id' => $customerId,
                    'customer_email' => $customer->email,
                    'customer_referral_code' => $customer->referral_code,
                    'project_id' => $projectId,
                    'project_name' => $project->name,
                    'amount' => $amount,
                    'points' => $points,
                    'status' => $projectInvestment->status,
                    'created_at' => $projectInvestment->created_at->toDateTimeString(),
                ],
                'project' => [
                    'id' => $project->id,
                    'name' => $project->name,
                    'total_value' => $project->total_value,
                    'invested_amount' => $project->invested_amount,
                    'remaining' => $project->total_value - $project->invested_amount,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create project investment: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}

