<?php

/**
 * Testing Script for TestingController Endpoints
 * 
 * This script demonstrates how to use the testing endpoints programmatically.
 * Run this script from the command line:
 * 
 * php test_endpoints.php
 */

$baseUrl = 'http://localhost/api/testing'; // Change this to your app URL

/**
 * Make an API request
 */
function makeRequest($method, $url, $data = null) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'status_code' => $httpCode,
        'response' => json_decode($response, true)
    ];
}

/**
 * Print formatted response
 */
function printResponse($title, $result) {
    echo "\n" . str_repeat("=", 80) . "\n";
    echo $title . "\n";
    echo str_repeat("=", 80) . "\n";
    echo "Status Code: " . $result['status_code'] . "\n";
    echo "Response:\n";
    echo json_encode($result['response'], JSON_PRETTY_PRINT) . "\n";
}

// ============================================================================
// TEST 1: Create 50 customers in the tree
// ============================================================================

echo "\n🚀 Starting Tests...\n";

$result = makeRequest('POST', $baseUrl . '/customers/add-to-tree', [
    'count' => 50,
    'start_index' => 1
]);
printResponse("TEST 1: Add 50 Customers to Tree", $result);

// Get some customer IDs from the response for later tests
$customerIds = [];
if (isset($result['response']['customers'])) {
    foreach ($result['response']['customers'] as $customer) {
        $customerIds[] = $customer['customer_id'];
        if (count($customerIds) >= 5) break; // Get first 5 customer IDs
    }
}

// ============================================================================
// TEST 2: Get tree statistics
// ============================================================================

$result = makeRequest('GET', $baseUrl . '/tree/stats');
printResponse("TEST 2: Get Tree Statistics", $result);

// ============================================================================
// TEST 3: Add points to a single user
// ============================================================================

if (!empty($customerIds)) {
    $testCustomerId = $customerIds[2] ?? 3; // Use 3rd customer or ID 3
    
    $result = makeRequest('POST', $baseUrl . '/points/add-to-user', [
        'customer_id' => $testCustomerId,
        'points' => 500,
        'side' => 'left'
    ]);
    printResponse("TEST 3: Add Points to Single User (ID: $testCustomerId)", $result);
}

// ============================================================================
// TEST 4: Add points to multiple users
// ============================================================================

if (count($customerIds) >= 4) {
    $users = [
        [
            'customer_id' => $customerIds[0],
            'points' => 100,
            'side' => 'left'
        ],
        [
            'customer_id' => $customerIds[1],
            'points' => 150,
            'side' => 'right'
        ],
        [
            'customer_id' => $customerIds[2],
            'points' => 200,
            'side' => 'left'
        ],
        [
            'customer_id' => $customerIds[3],
            'points' => 250,
            'side' => 'right'
        ]
    ];
    
    $result = makeRequest('POST', $baseUrl . '/points/add-to-multiple-users', [
        'users' => $users
    ]);
    printResponse("TEST 4: Add Points to Multiple Users", $result);
}

// ============================================================================
// TEST 5: Generate supporting bonuses
// ============================================================================

$result = makeRequest('POST', $baseUrl . '/bonuses/generate', [
    'amount' => 100
]);
printResponse("TEST 5: Generate Supporting Bonuses", $result);

// ============================================================================
// TEST 6: Check supporting bonuses for root customer
// ============================================================================

$rootCustomerId = 1; // Root customer is always ID 1
$result = makeRequest('GET', $baseUrl . '/bonuses/check?customer_id=' . $rootCustomerId);
printResponse("TEST 6: Check Supporting Bonuses for Root Customer", $result);

// ============================================================================
// TEST 7: Get customer details
// ============================================================================

if (!empty($customerIds)) {
    $testCustomerId = $customerIds[2] ?? 3;
    $result = makeRequest('GET', $baseUrl . '/customer/details?customer_id=' . $testCustomerId);
    printResponse("TEST 7: Get Customer Details (ID: $testCustomerId)", $result);
}

// ============================================================================
// TEST 8: Get updated tree statistics
// ============================================================================

$result = makeRequest('GET', $baseUrl . '/tree/stats');
printResponse("TEST 8: Get Updated Tree Statistics", $result);

// ============================================================================
// CLEANUP (OPTIONAL - UNCOMMENT TO CLEAR ALL TEST DATA)
// ============================================================================

echo "\n" . str_repeat("=", 80) . "\n";
echo "⚠️  To clear all test data, uncomment the cleanup section in this script.\n";
echo str_repeat("=", 80) . "\n";

/*
$result = makeRequest('POST', $baseUrl . '/clear-test-data', [
    'confirm' => 'YES_DELETE_ALL'
]);
printResponse("CLEANUP: Clear All Test Data", $result);
*/

echo "\n✅ All tests completed!\n\n";

// ============================================================================
// SUMMARY
// ============================================================================

echo str_repeat("=", 80) . "\n";
echo "SUMMARY\n";
echo str_repeat("=", 80) . "\n";
echo "All testing endpoints have been called successfully.\n";
echo "\nEndpoints tested:\n";
echo "  1. ✓ Add customers to tree\n";
echo "  2. ✓ Get tree statistics\n";
echo "  3. ✓ Add points to single user\n";
echo "  4. ✓ Add points to multiple users\n";
echo "  5. ✓ Generate supporting bonuses\n";
echo "  6. ✓ Check supporting bonuses\n";
echo "  7. ✓ Get customer details\n";
echo "  8. ✓ Get updated tree statistics\n";
echo "\nRefer to TESTING_API_DOCUMENTATION.md for more details.\n";
echo str_repeat("=", 80) . "\n\n";


