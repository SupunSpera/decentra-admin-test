# 🚀 API Testing Endpoints - Complete Guide

## What's New?

I've created **3 new API endpoints** for testing your TE-NET system:

### 1️⃣ Create Customer (with Auto Tree Placement) ✨
```
POST /api/testing/customer/create
```
- Creates a new customer
- **Automatically adds to binary tree** (no manual tree management!)
- Generates wallet and referral code
- Returns tree position info

### 2️⃣ Create Product Purchase 🛍️
```
POST /api/testing/product-purchase/create
```
- Creates a product purchase for a customer
- Calculates income quotas
- Updates customer status

### 3️⃣ Create Project Investment 💰
```
POST /api/testing/project-investment/create
```
- Creates a project investment
- Calculates points
- Updates project totals

---

## 🎯 Quick Test (30 seconds)

### Using Browser (Easiest!)

1. **Start your server:**
   ```bash
   php artisan serve
   ```

2. **Open in browser:**
   ```
   http://localhost:8000/api-tester.html
   ```

3. **Click buttons to test!** 🎉

That's it! The interface will guide you through everything.

---

## 📚 Documentation Files

I've created several documentation files for you:

| File | When to Use |
|------|-------------|
| **🚀 QUICK_START_GUIDE.md** | When you want to start testing immediately (5 min read) |
| **📖 TESTING_API_ENDPOINTS.md** | When you need complete API documentation (15 min read) |
| **📋 TESTING_ENDPOINTS_SUMMARY.md** | When you need an overview of everything created |
| **🌐 api-tester.html** | When you want to test in your browser (instant) |
| **📮 Testing_API_Endpoints.postman_collection.json** | When you're using Postman |

### Which File Should I Read?

**Just want to test quickly?**
→ Open `api-tester.html` in your browser

**Need to understand the API?**
→ Read `QUICK_START_GUIDE.md` (5 minutes)

**Need complete documentation?**
→ Read `TESTING_API_ENDPOINTS.md` (15 minutes)

**Using Postman?**
→ Import `Testing_API_Endpoints.postman_collection.json`

---

## 💻 Testing Methods

### Method 1: Browser Interface (Recommended for Quick Testing)

**Pros:** Visual, easy, instant feedback
**Setup time:** 0 minutes

```bash
# 1. Start server
php artisan serve

# 2. Open browser to:
http://localhost:8000/api-tester.html
```

### Method 2: Postman (Recommended for Development)

**Pros:** Professional, saveable, sharable
**Setup time:** 2 minutes

1. Open Postman
2. Import → Select File → `Testing_API_Endpoints.postman_collection.json`
3. Set `base_url` variable
4. Start testing!

### Method 3: cURL (For Automation/Scripts)

**Pros:** Scriptable, automatable
**Setup time:** 0 minutes

```bash
# Create customer
curl -X POST http://localhost:8000/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@test.com"
  }'
```

---

## 🎬 Complete Example Workflow

### Step-by-Step Testing

```bash
# 1. Create first customer (becomes root of tree)
curl -X POST http://localhost:8000/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{
    "first_name": "Root",
    "last_name": "User",
    "email": "root@test.com"
  }'

# Response includes customer_id, use it below
# Example: "id": 1

# 2. Create product purchase
curl -X POST http://localhost:8000/api/testing/product-purchase/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "product_id": 1
  }'

# 3. Create project investment
curl -X POST http://localhost:8000/api/testing/project-investment/create \
  -H "Content-Type: application/json" \
  -d '{
    "customer_id": 1,
    "project_id": 1,
    "amount": 10000
  }'

# 4. Verify everything
curl -X GET "http://localhost:8000/api/testing/customer/details?customer_id=1"

# 5. Check tree stats
curl -X GET "http://localhost:8000/api/testing/tree/stats"
```

---

## 🌳 How the Binary Tree Works

### Simple Explanation

When you create customers, they're automatically placed in a binary tree:

```
         Customer 1 (Root)
         /              \
  Customer 2         Customer 3
   /     \            /      \
  C4     C5          C6      C7
```

**You don't need to:**
- Specify parent
- Choose left/right position
- Calculate tree levels
- Manage tree balance

**The system automatically:**
- Finds the next available slot
- Places customer in correct position
- Links to parent
- Maintains tree structure

---

## 📊 What Gets Created

### Creating a Customer Creates:
1. **Customer record** with status, type, etc.
2. **Wallet** with ETH address (fake for testing)
3. **Referral node** in binary tree
4. **Referral code** (format: DX010001)

### Creating a Product Purchase Creates:
1. **Purchase record** with income quotas
2. Updates customer's **purchased_status**

### Creating a Project Investment Creates:
1. **Investment record** with amount and points
2. Updates project's **invested_amount**

---

## ⚙️ Prerequisites

Before testing, make sure you have:

### 1. Database Setup ✅
```bash
php artisan migrate
```

### 2. At Least One Product ✅
```sql
-- Check if you have products
SELECT id, name, price FROM products WHERE status = 1;

-- If empty, create one via admin panel or:
INSERT INTO products (name, price, points, status) 
VALUES ('Test Product', 1000, 500, 1);
```

### 3. At Least One Project ✅
```sql
-- Check if you have projects
SELECT id, name, minimum_investment FROM projects WHERE status = 1;

-- If empty, create one via admin panel or:
INSERT INTO projects (name, minimum_investment, total_value, status) 
VALUES ('Test Project', 5000, 1000000, 1);
```

---

## 🔍 Testing Scenarios

### Scenario 1: Single Customer Flow
1. Create customer
2. Make product purchase
3. Make project investment
4. Verify customer details

### Scenario 2: Multiple Customers
1. Create 5-10 customers
2. Check tree structure
3. Verify tree statistics

### Scenario 3: Complete System Test
1. Create root customer
2. Create 2 child customers
3. Make purchases for all
4. Make investments for all
5. Check tree stats
6. Verify all relationships

---

## 📱 Sample API Responses

### Create Customer Response
```json
{
  "success": true,
  "message": "Customer created and added to tree successfully",
  "customer": {
    "id": 1,
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@test.com",
    "referral_code": "DX010001",
    "wallet_address": "0x00000000000000000000000000000000000000001"
  },
  "referral": {
    "id": 1,
    "level": 1,
    "level_index": 1,
    "parent_referral_id": null,
    "position": "root"
  }
}
```

### Product Purchase Response
```json
{
  "success": true,
  "message": "Product purchase created successfully",
  "purchase": {
    "id": 1,
    "customer_id": 1,
    "product_name": "Premium Package",
    "product_price": 1000,
    "max_income_quota": 3000,
    "remaining_income_quota": 3000
  }
}
```

---

## 🛠️ Troubleshooting

### Common Issues

**❌ "Product not found"**
```bash
# Solution: Check products exist
php artisan tinker
>>> \App\Models\Product::where('status', 1)->get();
```

**❌ "Email already taken"**
```bash
# Solution: Use unique email or clear data
curl -X POST http://localhost:8000/api/testing/clear-test-data \
  -H "Content-Type: application/json" \
  -d '{"confirm": "YES_DELETE_ALL"}'
```

**❌ "Server error 500"**
```bash
# Solution: Check Laravel logs
tail -f storage/logs/laravel.log
```

---

## 🎯 Best Practices

### DO ✅
- Use unique emails for each test
- Check tree stats regularly
- Verify customer details after creation
- Clear test data between major test runs
- Use timestamps in test emails

### DON'T ❌
- Don't use same email twice
- Don't forget to check prerequisites
- Don't test in production (these are test endpoints!)
- Don't skip verification steps

---

## 🔐 Security Note

⚠️ **IMPORTANT:** These endpoints are for **TESTING ONLY**

- No authentication required (by design for testing)
- Should be removed or protected in production
- Wallet addresses are fake (for testing)
- Located under `/api/testing/` for easy identification

**Before production:**
1. Add authentication middleware
2. Add authorization checks
3. Remove or protect endpoints
4. Use real wallet generation

---

## 📞 Need Help?

### Step 1: Check Documentation
- Quick issue? → `QUICK_START_GUIDE.md`
- API question? → `TESTING_API_ENDPOINTS.md`
- Overview needed? → `TESTING_ENDPOINTS_SUMMARY.md`

### Step 2: Check Logs
```bash
tail -f storage/logs/laravel.log
```

### Step 3: Verify Database
```sql
-- Check customers
SELECT COUNT(*) FROM customers;

-- Check tree
SELECT COUNT(*) FROM referrals;

-- Check purchases
SELECT COUNT(*) FROM product_purchases;
```

---

## 🎉 You're All Set!

### Quick Start Checklist

- [ ] Server running (`php artisan serve`)
- [ ] Database migrated
- [ ] At least 1 product exists
- [ ] At least 1 project exists
- [ ] Opened `api-tester.html` OR imported Postman collection
- [ ] Created first customer successfully

**All checked?** You're ready to test! 🚀

---

## 📖 File Structure

```
├── app/Http/Controllers/
│   └── TestingController.php        (3 new methods added)
├── routes/
│   └── api.php                       (3 new routes added)
├── public/
│   └── api-tester.html              (NEW: Browser testing interface)
├── QUICK_START_GUIDE.md              (NEW: Quick reference)
├── TESTING_API_ENDPOINTS.md          (NEW: Complete docs)
├── TESTING_ENDPOINTS_SUMMARY.md      (NEW: Summary)
├── API_TESTING_README.md             (NEW: This file)
└── Testing_API_Endpoints.postman_collection.json (NEW: Postman)
```

---

## 🚀 Start Testing Now!

Choose your method:

**🌐 Browser (Easiest)**
```
http://localhost:8000/api-tester.html
```

**📮 Postman (Most Professional)**
```
Import: Testing_API_Endpoints.postman_collection.json
```

**💻 cURL (Most Flexible)**
```bash
curl -X POST http://localhost:8000/api/testing/customer/create \
  -H "Content-Type: application/json" \
  -d '{"first_name":"Test","last_name":"User","email":"test@test.com"}'
```

**Happy Testing! 🎉**

