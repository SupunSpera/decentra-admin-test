# Testing API Endpoints Documentation

This document provides detailed information about the testing API endpoints for customer creation, product purchases, and project investments.

## Base URL
```
http://your-domain.com/api/testing
```

---

## 1. Create Customer (with Automatic Tree Placement)

### Endpoint
```
POST /api/testing/customer/create
```

### Description
Creates a new customer and automatically adds them to the binary referral tree. The customer will be placed in the first available position in the tree (either left or right child of an existing referral).

### Request Body
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com",
  "telephone": "1234567890",
  "mobile": "0987654321",
  "password": "password123"
}
```

### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| first_name | string | Yes | Customer's first name (max 50 chars) |
| last_name | string | Yes | Customer's last name (max 50 chars) |
| email | string | Yes | Unique email address |
| telephone | string | No | Phone number (max 20 chars) |
| mobile | string | No | Mobile number (max 20 chars) |
| password | string | No | Password (min 8 chars, defaults to "password123") |

### Success Response (201)
```json
{
  "success": true,
  "message": "Customer created and added to tree successfully",
  "customer": {
    "id": 123,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "referral_code": "DX010001",
    "wallet_address": "0x0000000000000000000000000000000000000007b"
  },
  "referral": {
    "id": 456,
    "level": 2,
    "level_index": 2,
    "parent_referral_id": 1,
    "position": "left"
  },
  "parent": {
    "customer_id": 1,
    "email": "parent@example.com",
    "referral_code": "DX010000"
  }
}
```

### Error Response (422)
```json
{
  "success": false,
  "message": "The email has already been taken.",
  "errors": {
    "email": ["The email has already been taken."]
  }
}
```

### cURL Example
```bash
curl -X POST http://your-domain.com/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "password": "password123"
  }'
```

---

## 2. Create Product Purchase

### Endpoint
```
POST /api/testing/product-purchase/create
```

### Description
Creates a product purchase for a specific customer. This will also update the customer's purchased status to active and calculate income quotas.

### Request Body
```json
{
  "customer_id": 123,
  "product_id": 5
}
```

### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| customer_id | integer | Yes | ID of the customer making the purchase |
| product_id | integer | Yes | ID of the product being purchased |

### Success Response (200)
```json
{
  "success": true,
  "message": "Product purchase created successfully",
  "purchase": {
    "id": 789,
    "customer_id": 123,
    "customer_email": "john.doe@example.com",
    "customer_referral_code": "DX010001",
    "product_id": 5,
    "product_name": "Premium Package",
    "product_price": 1000,
    "product_points": 500,
    "max_income_quota": 3000,
    "remaining_income_quota": 3000,
    "created_at": "2025-12-30 10:30:00"
  }
}
```

### Error Response (404)
```json
{
  "success": false,
  "message": "Product not found"
}
```

### cURL Example
```bash
curl -X POST http://your-domain.com/api/testing/product-purchase/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 123,
    "product_id": 5
  }'
```

### Notes
- The max income quota is automatically calculated as 3x the product price
- The customer's `purchased_status` is updated to ACTIVE
- The income quota status is set to AVAILABLE

---

## 3. Create Project Investment

### Endpoint
```
POST /api/testing/project-investment/create
```

### Description
Creates a project investment for a specific customer. The investment amount must meet the project's minimum investment requirement.

### Request Body
```json
{
  "customer_id": 123,
  "project_id": 10,
  "amount": 5000
}
```

### Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| customer_id | integer | Yes | ID of the customer making the investment |
| project_id | integer | Yes | ID of the project to invest in |
| amount | numeric | Yes | Investment amount (must be >= project minimum) |

### Success Response (200)
```json
{
  "success": true,
  "message": "Project investment created successfully",
  "investment": {
    "id": 456,
    "customer_id": 123,
    "customer_email": "john.doe@example.com",
    "customer_referral_code": "DX010001",
    "project_id": 10,
    "project_name": "Real Estate Development",
    "amount": 5000,
    "points": 250,
    "status": 1,
    "created_at": "2025-12-30 10:30:00"
  },
  "project": {
    "id": 10,
    "name": "Real Estate Development",
    "total_value": 1000000,
    "invested_amount": 105000,
    "remaining": 895000
  }
}
```

### Error Response (422)
```json
{
  "success": false,
  "message": "Investment amount must be at least 1000"
}
```

### cURL Example
```bash
curl -X POST http://your-domain.com/api/testing/project-investment/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 123,
    "project_id": 10,
    "amount": 5000
  }'
```

### Notes
- Points are calculated based on the project's points ratio: `(amount * project.points / 100)`
- The project's `invested_amount` is automatically updated
- The investment status is set to COMPLETED by default

---

## Complete Testing Workflow

### Step 1: Create First Customer (Root)
```bash
curl -X POST http://your-domain.com/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Root",
    "last_name": "User",
    "email": "root@example.com"
  }'
```
**Response:** Customer created as root (level 1, position 1)

---

### Step 2: Create Additional Customers
```bash
# Second customer (will be left child of root)
curl -X POST http://your-domain.com/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Second",
    "last_name": "User",
    "email": "second@example.com"
  }'

# Third customer (will be right child of root)
curl -X POST http://your-domain.com/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Third",
    "last_name": "User",
    "email": "third@example.com"
  }'
```

---

### Step 3: Get Customer Details
First, get the tree stats to see customer IDs:
```bash
curl -X GET http://your-domain.com/api/testing/tree/stats
```

Then get specific customer details:
```bash
curl -X GET "http://your-domain.com/api/testing/customer/details?customer_id=1"
```

---

### Step 4: Create Product Purchase
First, check available products in your database, then:
```bash
curl -X POST http://your-domain.com/api/testing/product-purchase/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "product_id": 1
  }'
```

---

### Step 5: Create Project Investment
First, check available projects in your database, then:
```bash
curl -X POST http://your-domain.com/api/testing/project-investment/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "project_id": 1,
    "amount": 10000
  }'
```

---

## Postman Collection

You can import these endpoints into Postman by creating a new collection with the following structure:

### Collection Variables
- `base_url`: `http://your-domain.com`
- `customer_id`: (set after creating customer)
- `product_id`: (your product ID)
- `project_id`: (your project ID)

### Example Postman Request Structure
```json
{
  "name": "Create Customer",
  "request": {
    "method": "POST",
    "header": [
      {
        "key": "Content-Type",
        "value": "application/json"
      }
    ],
    "body": {
      "mode": "raw",
      "raw": "{\n  \"first_name\": \"Test\",\n  \"last_name\": \"User\",\n  \"email\": \"test@example.com\"\n}"
    },
    "url": {
      "raw": "{{base_url}}/api/testing/customer/create",
      "host": ["{{base_url}}"],
      "path": ["api", "testing", "customer", "create"]
    }
  }
}
```

---

## Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 201 | Created successfully |
| 404 | Resource not found |
| 422 | Validation error |
| 500 | Server error |

---

## Additional Testing Endpoints

These endpoints were already available and can be used in conjunction with the new endpoints:

### Get Tree Statistics
```bash
GET /api/testing/tree/stats
```

### Get Customer Details
```bash
GET /api/testing/customer/details?customer_id=123
```

### Add Multiple Customers to Tree
```bash
POST /api/testing/customers/add-to-tree
Body: { "count": 100, "start_index": 1 }
```

### Clear Test Data (⚠️ DANGEROUS)
```bash
POST /api/testing/clear-test-data
Body: { "confirm": "YES_DELETE_ALL" }
```

---

## Database Requirements

Before testing, ensure you have:
1. At least one product in the `products` table with `status = 1` (PUBLISHED)
2. At least one project in the `projects` table with `status = 1` (PUBLISHED)
3. Proper database migrations run

To check available products and projects:
```sql
-- Check products
SELECT id, name, price, points, status FROM products WHERE status = 1;

-- Check projects
SELECT id, name, minimum_investment, points, status FROM projects WHERE status = 1;
```

---

## Notes

1. **Tree Structure**: Customers are automatically placed in a binary tree structure. Each customer can have a left and right child.

2. **Wallet Creation**: A fake ETH wallet address and private key are generated for testing purposes. In production, you would integrate with a real wallet service.

3. **Referral Code**: Automatically generated in format "DX" + 6-digit number (e.g., DX010001).

4. **Income Quota**: For product purchases, the max income quota is calculated as 3x the product price.

5. **Investment Points**: Project investment points are calculated based on the project's points configuration.

6. **Testing Environment**: These endpoints are meant for testing only. Add proper authentication and authorization before using in production.

