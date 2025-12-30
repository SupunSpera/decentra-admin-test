# Customer Create API - Updated Documentation

## Overview
The `/api/testing/customer/create` endpoint has been updated to match the full functionality of `CustomerCreateForm.php` (Livewire component).

## Changes Made

### 1. **Added ReferralHelper Trait**
   - Imported `App\Traits\Referral\ReferralHelper` trait to TestingController
   - This provides helper methods like:
     - `calculateLevelIndex()`
     - `getOuterChildren()`
     - `getOuterChildWithSide()`

### 2. **Added Required Dependencies**
   - `InstituteMember` model
   - `InstituteMemberFacade`
   - `ProductPurchaseFacade`

### 3. **Updated Request Parameters**

#### Required Parameters:
- `first_name` (string, max:50)
- `last_name` (string, max:50)
- `email` (string, email, unique)
- `referral_id` (integer, exists in referrals table) - **Direct Referral**

#### Optional Parameters:
- `parent_referral_id` (integer, exists in referrals table) - For manual placement
- `placement` (string: 'L', 'R', 'left', or 'right') - For manual placement
- `telephone` (string, max:20)
- `mobile` (string, max:20)
- `password` (string, min:8) - Auto-generated if not provided

### 4. **Two Placement Modes**

#### A. **Manual Placement** (When both `parent_referral_id` and `placement` are provided)
- Allows precise control over where the customer is placed in the tree
- Validates that the selected position (left or right) is available
- Throws error if position is already occupied

#### B. **Auto Placement** (When `parent_referral_id` or `placement` is missing)
- Uses intelligent algorithm to find optimal placement
- Considers:
  - Empty left/right positions under direct referral
  - Points distribution (left_points vs right_points)
  - Active customers count on each side
  - Product purchase history (last 2 months)
  - Balancing percentage (25% threshold)

### 5. **Key Features**

✅ **Institute Member Integration**: Automatically activates pending institute members
✅ **Fake Wallet Generation**: Creates test ETH wallet addresses
✅ **Complex Balancing Logic**: Matches the Livewire form's auto-placement algorithm
✅ **Direct Referral Activation**: Updates direct referral's active status
✅ **Transaction Safety**: Uses database transactions with rollback on error

## API Usage Examples

### Example 1: Auto Placement (Simple)
```json
POST /api/testing/customer/create
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com",
  "referral_id": 1
}
```

### Example 2: Manual Placement (Left)
```json
POST /api/testing/customer/create
{
  "first_name": "Jane",
  "last_name": "Smith",
  "email": "jane.smith@example.com",
  "referral_id": 1,
  "parent_referral_id": 5,
  "placement": "L"
}
```

### Example 3: Manual Placement (Right) with Password
```json
POST /api/testing/customer/create
{
  "first_name": "Bob",
  "last_name": "Johnson",
  "email": "bob.johnson@example.com",
  "referral_id": 1,
  "parent_referral_id": 5,
  "placement": "R",
  "password": "SecurePass123"
}
```

### Example 4: With Contact Information
```json
POST /api/testing/customer/create
{
  "first_name": "Alice",
  "last_name": "Williams",
  "email": "alice.williams@example.com",
  "referral_id": 2,
  "telephone": "1234567890",
  "mobile": "0987654321"
}
```

## Response Format

### Success Response:
```json
{
  "success": true,
  "message": "Customer created successfully",
  "customer": {
    "id": 123,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "referral_code": "REF123ABC",
    "wallet_address": "0x000000000000000000000000000000000000007b",
    "password": "aB3dEf8h"
  },
  "referral": {
    "id": 456,
    "level": 2,
    "level_index": 3,
    "position": "left",
    "placement_type": "auto",
    "parent_referral_id": 1,
    "direct_referral_id": 1
  }
}
```

### Error Response:
```json
{
  "success": false,
  "message": "Failed to create customer: Left position already occupied",
  "trace": "..."
}
```

## Placement Types in Response

- `manual` - Customer manually placed by specifying parent and position
- `auto` - Customer placed in first available position (left or right)
- `auto_complex` - Customer placed using complex balancing algorithm

## Auto Placement Algorithm

The auto-placement algorithm follows this decision tree:

1. **Check Direct Referral's Children**
   - If left child empty → Place left
   - If right child empty → Place right

2. **Both Children Occupied**
   - Count direct referrals under parent
   - If only 1 direct referral: Place on opposite side
   - If multiple direct referrals:
     - Calculate points on each side
     - Count active customers (with purchases in last 2 months)
     - Balance based on:
       - Minimum points side
       - Minimum customers side
       - 25% threshold for customer distribution
     - Find outer child position recursively

## Validation Rules

- Email must be unique
- Referral ID must exist in referrals table
- Parent Referral ID must exist (if provided)
- Placement must be 'L', 'R', 'left', or 'right' (case-insensitive)
- If manual placement, position must be available

## Notes

- Password is auto-generated (8 random characters) if not provided
- Wallet addresses are fake (for testing) using pattern: `0x` + padded hex ID
- Institute members with matching email are automatically activated
- Direct referral customer's `active_status` is set to ACTIVE
- Uses database transactions for data integrity

## Comparison with CustomerCreateForm.php

| Feature | CustomerCreateForm | TestingController API |
|---------|-------------------|----------------------|
| Manual Placement | ✅ | ✅ |
| Auto Placement | ✅ | ✅ |
| Complex Balancing | ✅ | ✅ |
| Institute Member Sync | ✅ | ✅ |
| Email Notification | ✅ | ❌ (API only) |
| Wallet Creation | Real ETH API | Fake (for testing) |
| UI/Form Validation | ✅ | N/A |
| API Response | N/A | ✅ |

## Testing Recommendations

1. Test auto-placement with empty tree
2. Test manual placement with available positions
3. Test error handling for occupied positions
4. Test complex auto-placement with multiple levels
5. Test with institute members
6. Verify direct referral activation
