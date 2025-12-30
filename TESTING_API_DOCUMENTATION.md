# Testing API Documentation

This document provides comprehensive documentation for all testing endpoints in the TestingController. These endpoints are designed to help you test the binary tree referral system, customer creation, points management, and supporting bonus generation.

## Base URL
All testing endpoints are prefixed with `/api/testing`

## Authentication
These testing routes are API routes without authentication middleware, making them easy to use with Postman or cURL. **In production, ensure these routes are properly secured or removed.**

---

## Endpoints

### 1. Add Customers to Tree

**Endpoint:** `POST /api/testing/customers/add-to-tree`

**Description:** Creates multiple customers and adds them to the binary tree structure with hardcoded wallet addresses. Creates a balanced binary tree automatically.

**Parameters:**
- `count` (optional, default: 1000) - Number of customers to create
- `start_index` (optional, default: 1) - Starting index for wallet address generation

**Request Example:**
```bash
curl -X POST http://localhost/api/testing/customers/add-to-tree \
  -H "Content-Type: application/json" \
  -d '{
    "count": 100,
    "start_index": 1
  }'
```

**Response Example:**
```json
{
  "success": true,
  "message": "Successfully created 100 customers in the tree",
  "customers": [
    {
      "customer_id": 1,
      "email": "test_user_1_1234567890@test.com",
      "referral_code": "DX010000",
      "referral_id": 1,
      "level": 1,
      "level_index": 1,
      "parent_referral_id": null,
      "wallet_address": "0x0000000000000000000000000000000000000001"
    }
  ],
  "total_customers": 100,
  "total_referrals": 100
}
```

---

### 2. Add Points to Single User

**Endpoint:** `POST /api/testing/points/add-to-user`

**Description:** Adds points to a specific customer on a specified side (left or right) and propagates the points up the tree to all parent referrals. Also creates supporting bonus records.

**Parameters:**
- `customer_id` (required) - The customer ID to add points to
- `points` (required) - Amount of points to add (numeric, min: 0)
- `side` (required) - Side to add points ("left" or "right")

**Request Example:**
```bash
curl -X POST http://localhost/api/testing/points/add-to-user \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 5,
    "points": 100,
    "side": "left"
  }'
```

**Response Example:**
```json
{
  "success": true,
  "message": "Successfully added 100 points to left side of customer 5",
  "referral": {
    "id": 5,
    "customer_id": 5,
    "level": 3,
    "left_points": 100,
    "right_points": 0
  }
}
```

---

### 3. Add Points to Multiple Users

**Endpoint:** `POST /api/testing/points/add-to-multiple-users`

**Description:** Adds points to multiple customers in a single request. Useful for bulk testing.

**Parameters:**
- `users` (required, array) - Array of user objects with the following structure:
  - `customer_id` (required) - Customer ID
  - `points` (required) - Points to add
  - `side` (optional, default: "left") - Side to add points ("left" or "right")

**Request Example:**
```bash
curl -X POST http://localhost/api/testing/points/add-to-multiple-users \
  -H "Content-Type: application/json" \
  -d '{
    "users": [
      {
        "customer_id": 5,
        "points": 100,
        "side": "left"
      },
      {
        "customer_id": 10,
        "points": 200,
        "side": "right"
      },
      {
        "customer_id": 15,
        "points": 150,
        "side": "left"
      }
    ]
  }'
```

**Response Example:**
```json
{
  "success": true,
  "message": "Points added to multiple users",
  "results": [
    {
      "customer_id": 5,
      "success": true,
      "points_added": 100,
      "side": "left",
      "left_points": 100,
      "right_points": 0
    },
    {
      "customer_id": 10,
      "success": true,
      "points_added": 200,
      "side": "right",
      "left_points": 0,
      "right_points": 200
    }
  ]
}
```

---

### 4. Generate Supporting Bonuses

**Endpoint:** `POST /api/testing/bonuses/generate`

**Description:** Generates supporting bonuses for customers based on their tree structure. Creates bonus records for all children under specified referrals.

**Parameters:**
- `amount` (optional, default: 100) - Amount of bonus to generate per child
- `customer_ids` (optional, array) - Array of customer IDs to generate bonuses for. If empty, generates for all customers with children.

**Request Example:**
```bash
# Generate bonuses for all customers with children
curl -X POST http://localhost/api/testing/bonuses/generate \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 100
  }'

# Generate bonuses for specific customers
curl -X POST http://localhost/api/testing/bonuses/generate \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 150,
    "customer_ids": [1, 2, 3]
  }'
```

**Response Example:**
```json
{
  "success": true,
  "message": "Supporting bonuses generated successfully",
  "generated": [
    {
      "referral_id": 1,
      "customer_id": 1,
      "left_children": 5,
      "right_children": 4,
      "left_points": 500,
      "right_points": 400,
      "bonuses_created": 9
    }
  ],
  "total_bonuses": 50
}
```

---

### 5. Check Supporting Bonuses for Customer

**Endpoint:** `GET /api/testing/bonuses/check`

**Description:** Comprehensive check of supporting bonuses for a specific customer. Returns detailed information about bonuses received, bonuses generated under the customer's referral, tree structure, and validation of points.

**Parameters:**
- `customer_id` (required) - Customer ID to check

**Request Example:**
```bash
curl -X GET "http://localhost/api/testing/bonuses/check?customer_id=1"
```

**Response Example:**
```json
{
  "success": true,
  "customer": {
    "id": 1,
    "email": "test_user_1@test.com",
    "referral_code": "DX010000"
  },
  "referral": {
    "id": 1,
    "level": 1,
    "level_index": 1,
    "left_points": 500,
    "right_points": 400
  },
  "tree_structure": {
    "left_children_count": 5,
    "right_children_count": 4,
    "total_children": 9
  },
  "bonus_summary": {
    "bonuses_received_count": 0,
    "bonuses_received_total": 0,
    "bonuses_generated_count": 9,
    "bonuses_generated_total": 900,
    "left_side_bonus_total": 500,
    "right_side_bonus_total": 400,
    "today_bonuses_count": 9,
    "today_bonuses_total": 900
  },
  "validation": {
    "left_points_match": true,
    "right_points_match": true,
    "points_difference": {
      "left": 0,
      "right": 0
    }
  },
  "bonuses_received": [],
  "bonuses_generated": [
    {
      "id": 1,
      "customer_id": 2,
      "customer_email": "test_user_2@test.com",
      "amount": 100,
      "status": 0,
      "created_at": "2025-12-29 10:00:00"
    }
  ]
}
```

---

### 6. Get Tree Statistics

**Endpoint:** `GET /api/testing/tree/stats`

**Description:** Returns comprehensive statistics about the entire tree structure, including customer counts, bonus totals, and distribution across levels.

**Request Example:**
```bash
curl -X GET http://localhost/api/testing/tree/stats
```

**Response Example:**
```json
{
  "success": true,
  "statistics": {
    "total_customers": 1000,
    "total_referrals": 1000,
    "total_bonuses": 5000,
    "max_level": 10,
    "total_left_points": 25000,
    "total_right_points": 24500,
    "total_points": 49500,
    "today_bonuses_count": 150,
    "today_bonuses_total": 15000
  },
  "customers_by_level": [
    {
      "level": 1,
      "count": 1
    },
    {
      "level": 2,
      "count": 2
    },
    {
      "level": 3,
      "count": 4
    }
  ]
}
```

---

### 7. Get Customer Details

**Endpoint:** `GET /api/testing/customer/details`

**Description:** Get detailed information about a specific customer including their tree relationships (parent and children).

**Parameters:**
- `customer_id` (required) - Customer ID to get details for

**Request Example:**
```bash
curl -X GET "http://localhost/api/testing/customer/details?customer_id=5"
```

**Response Example:**
```json
{
  "success": true,
  "customer": {
    "id": 5,
    "first_name": "Test",
    "last_name": "User5",
    "email": "test_user_5@test.com",
    "referral_code": "DX010005"
  },
  "wallet": {
    "eth_wallet_address": "0x0000000000000000000000000000000000000005",
    "token_amount": 0,
    "usdt_amount": 0
  },
  "referral": {
    "id": 5,
    "level": 3,
    "level_index": 2,
    "left_points": 100,
    "right_points": 50
  },
  "tree_relationships": {
    "parent": {
      "customer_id": 2,
      "email": "test_user_2@test.com",
      "referral_code": "DX010002"
    },
    "left_child": {
      "customer_id": 10,
      "email": "test_user_10@test.com",
      "referral_code": "DX010010"
    },
    "right_child": {
      "customer_id": 11,
      "email": "test_user_11@test.com",
      "referral_code": "DX010011"
    }
  }
}
```

---

### 8. Clear Test Data

**Endpoint:** `POST /api/testing/clear-test-data`

**Description:** **⚠️ DANGEROUS OPERATION** - Deletes all customers, referrals, wallets, and supporting bonuses from the database. Use with extreme caution!

**Parameters:**
- `confirm` (required) - Must be exactly "YES_DELETE_ALL" to proceed

**Request Example:**
```bash
curl -X POST http://localhost/api/testing/clear-test-data \
  -H "Content-Type: application/json" \
  -d '{
    "confirm": "YES_DELETE_ALL"
  }'
```

**Response Example:**
```json
{
  "success": true,
  "message": "All test data cleared successfully",
  "deleted": {
    "customers": 1000,
    "referrals": 1000,
    "bonuses": 5000,
    "wallets": 1000
  }
}
```

---

## Testing Workflow Examples

### Example 1: Create a test tree and add points

```bash
# Step 1: Create 100 test customers
curl -X POST http://localhost/api/testing/customers/add-to-tree \
  -H "Content-Type: application/json" \
  -d '{"count": 100, "start_index": 1}'

# Step 2: Get tree statistics
curl -X GET http://localhost/api/testing/tree/stats

# Step 3: Add points to a specific customer
curl -X POST http://localhost/api/testing/points/add-to-user \
  -H "Content-Type: application/json" \
  -d '{"customer_id": 10, "points": 500, "side": "left"}'

# Step 4: Check bonuses for that customer's parents
curl -X GET "http://localhost/api/testing/bonuses/check?customer_id=5"
```

### Example 2: Generate and verify supporting bonuses

```bash
# Step 1: Generate supporting bonuses for all customers
curl -X POST http://localhost/api/testing/bonuses/generate \
  -H "Content-Type: application/json" \
  -d '{"amount": 100}'

# Step 2: Check bonuses for specific customer
curl -X GET "http://localhost/api/testing/bonuses/check?customer_id=1"

# Step 3: Verify tree statistics
curl -X GET http://localhost/api/testing/tree/stats
```

### Example 3: Bulk points addition

```bash
# Add points to multiple customers at once
curl -X POST http://localhost/api/testing/points/add-to-multiple-users \
  -H "Content-Type: application/json" \
  -d '{
    "users": [
      {"customer_id": 5, "points": 100, "side": "left"},
      {"customer_id": 6, "points": 150, "side": "right"},
      {"customer_id": 7, "points": 200, "side": "left"},
      {"customer_id": 8, "points": 250, "side": "right"}
    ]
  }'
```

---

## Important Notes

1. **Wallet Addresses**: Wallet addresses are generated as hardcoded values based on the customer index: `0x` followed by the index padded to 40 hex characters.

2. **Binary Tree Structure**: The system automatically creates a balanced binary tree. Each referral can have:
   - A left child (`left_child_id`)
   - A right child (`right_child_id`)
   - A parent (`parent_referral_id`)
   - A direct referral (`direct_referral_id`)

3. **Points Propagation**: When points are added to a customer, they automatically propagate up the tree to all parent referrals, updating the appropriate side (left or right).

4. **Supporting Bonuses**: Supporting bonuses are created as records in the `customer_supporting_bonuses` table with:
   - `status = 0` (PENDING)
   - Associated customer and referral IDs
   - Amount

5. **Testing in Production**: **Never expose these endpoints in production** or ensure they are properly secured with strong authentication and authorization.

6. **Database Cleanup**: Always use the clear test data endpoint responsibly. It truncates entire tables!

---

## Error Handling

All endpoints return appropriate HTTP status codes:
- `200` - Success
- `404` - Resource not found
- `422` - Validation error
- `500` - Server error

Error response format:
```json
{
  "success": false,
  "message": "Error description",
  "trace": "Stack trace (only in debug mode)"
}
```

---

## Testing with Postman

Import this collection structure into Postman:

1. Create a new collection called "TE-NET-ADMIN Testing"
2. Add the base URL as a collection variable: `{{base_url}}` = `http://localhost`
3. Import all endpoints from this documentation
4. Use the workflow examples as test scenarios

---

## Support

For issues or questions about these testing endpoints, please contact the development team.


