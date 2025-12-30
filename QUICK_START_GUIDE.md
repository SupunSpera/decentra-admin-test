# Quick Start Guide - Testing API Endpoints

## 🚀 Quick Setup

### 1. Prerequisites
Make sure you have at least one product and one project in your database:

```sql
-- Check if you have products
SELECT id, name, price FROM products WHERE status = 1 LIMIT 5;

-- Check if you have projects  
SELECT id, name, minimum_investment FROM projects WHERE status = 1 LIMIT 5;
```

If you don't have any, create them through your admin panel or insert test data.

---

## 📋 Three Main Endpoints

### 1️⃣ Create Customer (Auto Tree Placement)
**Endpoint:** `POST /api/testing/customer/create`

**Minimal Request:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com"
}
```

**What it does:**
- Creates a new customer
- Automatically adds them to the binary referral tree
- Creates a wallet for the customer
- Returns customer details with tree position

---

### 2️⃣ Create Product Purchase
**Endpoint:** `POST /api/testing/product-purchase/create`

**Request:**
```json
{
  "customer_id": 1,
  "product_id": 1
}
```

**What it does:**
- Creates a product purchase for the customer
- Calculates income quota (3x product price)
- Updates customer's purchased status to ACTIVE

---

### 3️⃣ Create Project Investment
**Endpoint:** `POST /api/testing/project-investment/create`

**Request:**
```json
{
  "customer_id": 1,
  "project_id": 1,
  "amount": 5000
}
```

**What it does:**
- Creates a project investment
- Calculates investment points
- Updates project's invested amount

---

## 🧪 Testing with cURL

### Complete Test Workflow

**Step 1: Create First Customer (Root)**
```bash
curl -X POST http://localhost:8000/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Root",
    "last_name": "User",
    "email": "root@test.com"
  }'
```

**Step 2: Note the customer_id from the response**
```json
{
  "success": true,
  "customer": {
    "id": 1,  // ← Use this ID
    "referral_code": "DX010000"
  }
}
```

**Step 3: Create Product Purchase**
```bash
curl -X POST http://localhost:8000/api/testing/product-purchase/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "product_id": 1
  }'
```

**Step 4: Create Project Investment**
```bash
curl -X POST http://localhost:8000/api/testing/project-investment/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "project_id": 1,
    "amount": 10000
  }'
```

**Step 5: Verify Everything Worked**
```bash
curl -X GET "http://localhost:8000/api/testing/customer/details?customer_id=1"
```

---

## 🔥 Quick Bulk Testing

**Create 10 customers at once:**
```bash
curl -X POST http://localhost:8000/api/testing/customers/add-to-tree \
  -H "Content-Type: application/json" \
  -d '{
    "count": 10
  }'
```

**Get tree statistics:**
```bash
curl -X GET http://localhost:8000/api/testing/tree/stats
```

---

## 📱 Testing with Postman

1. **Import the collection:**
   - Open Postman
   - Click "Import"
   - Select `Testing_API_Endpoints.postman_collection.json`

2. **Set base URL:**
   - In collection variables, set `base_url` to your server URL
   - Example: `http://localhost:8000`

3. **Update product_id and project_id:**
   - Check your database for valid IDs
   - Update collection variables accordingly

4. **Run the complete workflow:**
   - Go to "7. Complete Testing Workflow"
   - Click "Run" to execute all steps in sequence

---

## 🎯 Common Use Cases

### Testing Customer Registration Flow
```bash
# Create 5 customers to test tree structure
for i in {1..5}; do
  curl -X POST http://localhost:8000/api/testing/customer/create \
    -H "Content-Type: application/json" \
    -d "{
      \"first_name\": \"User\",
      \"last_name\": \"$i\",
      \"email\": \"user$i@test.com\"
    }"
done
```

### Testing Product Purchases
```bash
# Purchase product for customer 1
curl -X POST http://localhost:8000/api/testing/product-purchase/create \
  -H "Content-Type: application/json" \
  -d '{"customer_id": 1, "product_id": 1}'
```

### Testing Investments
```bash
# Create investment for customer 1
curl -X POST http://localhost:8000/api/testing/project-investment/create \
  -H "Content-Type: application/json" \
  -d '{"customer_id": 1, "project_id": 1, "amount": 15000}'
```

---

## 🛠️ Helpful Utility Endpoints

### Get Customer Details
```bash
curl -X GET "http://localhost:8000/api/testing/customer/details?customer_id=1"
```

### Check Tree Statistics
```bash
curl -X GET "http://localhost:8000/api/testing/tree/stats"
```

### Add Points to Customer
```bash
curl -X POST http://localhost:8000/api/testing/points/add-to-user \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "points": 100,
    "side": "left"
  }'
```

---

## ⚠️ Reset Test Data

**DANGER: This will delete ALL customers, referrals, wallets, and bonuses!**

```bash
curl -X POST http://localhost:8000/api/testing/clear-test-data \
  -H "Content-Type: application/json" \
  -d '{"confirm": "YES_DELETE_ALL"}'
```

---

## 📊 Understanding the Response

### Customer Creation Response
```json
{
  "success": true,
  "message": "Customer created and added to tree successfully",
  "customer": {
    "id": 1,                          // Use this for other endpoints
    "referral_code": "DX010000",     // Unique referral code
    "wallet_address": "0x..."        // ETH wallet address
  },
  "referral": {
    "level": 1,                       // Tree level (1 = root)
    "level_index": 1,                 // Position in level
    "position": "left"                // left or right child
  },
  "parent": {
    "customer_id": 0,                 // Parent's customer ID
    "referral_code": "DX..."         // Parent's referral code
  }
}
```

### Product Purchase Response
```json
{
  "success": true,
  "purchase": {
    "id": 1,
    "product_name": "Premium Package",
    "product_price": 1000,
    "max_income_quota": 3000,        // 3x the price
    "remaining_income_quota": 3000
  }
}
```

### Project Investment Response
```json
{
  "success": true,
  "investment": {
    "id": 1,
    "amount": 5000,
    "points": 250,                    // Calculated from project
    "status": 1
  },
  "project": {
    "invested_amount": 105000,       // Updated total
    "remaining": 895000              // Remaining capacity
  }
}
```

---

## 🔍 Troubleshooting

### Error: "Product not found"
**Solution:** Check if product exists and is published
```sql
SELECT * FROM products WHERE id = 1 AND status = 1;
```

### Error: "Project not found"
**Solution:** Check if project exists and is published
```sql
SELECT * FROM projects WHERE id = 1 AND status = 1;
```

### Error: "Investment amount must be at least X"
**Solution:** Check project's minimum investment
```sql
SELECT minimum_investment FROM projects WHERE id = 1;
```

### Error: "Email has already been taken"
**Solution:** Use a unique email or clear test data

---

## 📚 More Information

- **Full API Documentation:** See `TESTING_API_ENDPOINTS.md`
- **Postman Collection:** Import `Testing_API_Endpoints.postman_collection.json`
- **Existing Documentation:** See `TESTING_API_DOCUMENTATION.md`

---

## 💡 Tips

1. **Use timestamps in emails** for testing: `user{{timestamp}}@test.com`
2. **Check tree stats** regularly to verify structure
3. **Keep track of customer IDs** returned from creation
4. **Test the complete workflow** before individual endpoints
5. **Clear test data** between major test runs

---

## 🎉 You're Ready!

Start by creating a root customer, then add purchases and investments. The tree structure is handled automatically!

