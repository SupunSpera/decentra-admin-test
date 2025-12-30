# Testing Setup - Quick Start Guide

## 📋 What Has Been Added

A comprehensive testing suite for the binary tree referral system has been added to the project. This includes:

### Files Created/Modified:

1. **TestingController.php** (`app/Http/Controllers/TestingController.php`)
   - Complete controller with 8 testing endpoints
   - Handles customer creation, points management, and bonus generation

2. **Routes** (`routes/web.php`)
   - Added `/testing` prefix group with all testing routes
   - Currently without auth middleware (configure as needed)

3. **Documentation** 
   - `TESTING_API_DOCUMENTATION.md` - Complete API documentation
   - `TESTING_SETUP_README.md` - This file

4. **Testing Tools**
   - `test_endpoints.php` - PHP script to test all endpoints
   - `TE_NET_ADMIN_Testing.postman_collection.json` - Postman collection

---

## 🚀 Quick Start

### Option 1: Using PHP Script (Recommended for Initial Testing)

```bash
# Make sure your Laravel app is running
php artisan serve

# In another terminal, run the test script
php test_endpoints.php
```

This will:
- ✅ Create 50 test customers in a binary tree
- ✅ Add points to customers
- ✅ Generate supporting bonuses
- ✅ Display comprehensive test results

### Option 2: Using Postman

1. Open Postman
2. Import the collection: `File` → `Import` → Select `TE_NET_ADMIN_Testing.postman_collection.json`
3. Set the base URL variable: `http://localhost` (or your app URL)
4. Run the "Complete Test Workflow" folder to execute all tests in sequence

### Option 3: Using cURL

```bash
# Create 100 customers
curl -X POST http://localhost/api/testing/customers/add-to-tree \
  -H "Content-Type: application/json" \
  -d '{"count": 100, "start_index": 1}'

# Get tree statistics
curl http://localhost/api/testing/tree/stats

# Add points to a user
curl -X POST http://localhost/api/testing/points/add-to-user \
  -H "Content-Type: application/json" \
  -d '{"customer_id": 5, "points": 100, "side": "left"}'

# Check bonuses
curl http://localhost/api/testing/bonuses/check?customer_id=1
```

---

## 📚 Available Endpoints

All endpoints are now API routes with `/api/` prefix for easy Postman testing without CSRF tokens!

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/testing/customers/add-to-tree` | Create multiple customers in binary tree |
| POST | `/api/testing/points/add-to-user` | Add points to single user |
| POST | `/api/testing/points/add-to-multiple-users` | Add points to multiple users |
| POST | `/api/testing/bonuses/generate` | Generate supporting bonuses |
| GET | `/api/testing/bonuses/check` | Check bonuses for customer |
| GET | `/api/testing/tree/stats` | Get tree statistics |
| GET | `/api/testing/customer/details` | Get customer details |
| POST | `/api/testing/clear-test-data` | Clear all test data ⚠️ |

---

## 🔑 Key Features

### 1. Customer Tree Creation
- Automatically creates balanced binary tree
- Generates hardcoded wallet addresses (`0x` + padded hex)
- Creates customers with all required relationships

### 2. Points Management
- Add points to individual or multiple customers
- Automatic propagation up the tree to parents
- Creates supporting bonus records automatically

### 3. Bonus Generation
- Generate bonuses for all customers or specific ones
- Configurable bonus amounts
- Tracks left/right side totals

### 4. Comprehensive Validation
- Check if bonuses are generated correctly
- Validate points match on left/right sides
- View complete tree structure for any customer

---

## 🎯 Common Testing Scenarios

### Scenario 1: Test Basic Tree Structure

```bash
# 1. Create customers
curl -X POST http://localhost/api/testing/customers/add-to-tree \
  -H "Content-Type: application/json" \
  -d '{"count": 50}'

# 2. Check tree structure
curl http://localhost/api/testing/tree/stats

# 3. View specific customer
curl http://localhost/api/testing/customer/details?customer_id=1
```

### Scenario 2: Test Points Propagation

```bash
# 1. Add points to a leaf node
curl -X POST http://localhost/api/testing/points/add-to-user \
  -H "Content-Type: application/json" \
  -d '{"customer_id": 10, "points": 500, "side": "left"}'

# 2. Check parent's bonuses (customer 5 is likely parent of 10)
curl http://localhost/api/testing/bonuses/check?customer_id=5

# 3. Check root's bonuses
curl http://localhost/api/testing/bonuses/check?customer_id=1
```

### Scenario 3: Test Bonus Generation

```bash
# 1. Generate bonuses
curl -X POST http://localhost/api/testing/bonuses/generate \
  -H "Content-Type: application/json" \
  -d '{"amount": 100}'

# 2. Verify generation
curl http://localhost/api/testing/tree/stats

# 3. Check specific customer
curl http://localhost/api/testing/bonuses/check?customer_id=1
```

---

## ⚙️ Configuration

### Update Base URL

**PHP Script:**
```php
// In test_endpoints.php, line 11
$baseUrl = 'http://your-domain.com/api/testing';
```

**Postman:**
- Collection Variables → `base_url` → `http://your-domain.com`

### Add Authentication (If Needed)

In `routes/api.php`, add middleware:

```php
Route::prefix('testing')->middleware(['auth'])->group(function () {
    // ... routes
});
```

Or add custom auth:

```php
Route::prefix('testing')->middleware(['auth', 'admin'])->group(function () {
    // ... routes
});
```

---

## 🔒 Security Considerations

### ⚠️ IMPORTANT FOR PRODUCTION:

1. **Remove or Secure Routes:**
   ```php
   // Option 1: Remove in production
   if (config('app.env') !== 'production') {
       Route::prefix('testing')->group(function () {
           // ... testing routes
       });
   }
   
   // Option 2: Add strong authentication
   Route::prefix('testing')
       ->middleware(['auth', 'admin', 'can:access-testing'])
       ->group(function () {
           // ... testing routes
       });
   ```

2. **Environment-Based Access:**
   ```php
   Route::prefix('testing')
       ->middleware(function ($request, $next) {
           if (config('app.env') === 'production') {
               abort(404);
           }
           return $next($request);
       })
       ->group(function () {
           // ... testing routes
       });
   ```

3. **IP Whitelist:**
   ```php
   Route::prefix('testing')
       ->middleware('ip.whitelist:127.0.0.1,192.168.1.100')
       ->group(function () {
           // ... testing routes
       });
   ```

---

## 🧹 Cleanup

### Clear All Test Data

**⚠️ WARNING: This will delete ALL customers, referrals, wallets, and bonuses!**

```bash
curl -X POST http://localhost/api/testing/clear-test-data \
  -H "Content-Type: application/json" \
  -d '{"confirm": "YES_DELETE_ALL"}'
```

---

## 📊 Understanding the Binary Tree

### Tree Structure:
```
Level 1:                    [1]
                           /   \
Level 2:              [2]       [3]
                      /  \      /  \
Level 3:          [4]  [5]  [6]  [7]
                 /  \  /  \  /  \  /  \
Level 4:      [8][9][10][11][12][13][14][15]
```

### Key Concepts:

- **Parent Referral**: The referral above a node
- **Direct Referral**: The referral that invited this customer
- **Left/Right Child**: The two possible children of a referral
- **Left/Right Points**: Accumulated points from left/right subtrees
- **Level**: Depth in the tree (root is level 1)
- **Level Index**: Position within the level

---

## 🐛 Troubleshooting

### Issue: "Customer not found"
- **Cause**: Customer ID doesn't exist
- **Solution**: Check tree stats to see available customer IDs

### Issue: "Referral not found"
- **Cause**: Customer exists but not in referral tree
- **Solution**: Ensure customer was created via tree endpoints

### Issue: "Points don't match"
- **Cause**: Points added directly vs through bonus generation
- **Solution**: Use consistent method (either direct points or bonus generation)

### Issue: Routes not found (404)
- **Cause**: Routes not loaded or middleware blocking
- **Solution**: Run `php artisan route:list | grep testing` to verify routes

---

## 📖 Additional Resources

- **Full API Documentation**: See `TESTING_API_DOCUMENTATION.md`
- **Controller Source**: `app/Http/Controllers/TestingController.php`
- **Routes**: `routes/api.php` (search for `/api/testing` prefix)

---

## 💡 Tips

1. **Start Small**: Test with 10-20 customers first, then scale up
2. **Check Stats**: Always check tree stats before and after operations
3. **Validate**: Use the check bonuses endpoint to verify correctness
4. **Sequential Testing**: Run tests in order (create → add points → generate bonuses → check)
5. **Use Postman Workflow**: The "Complete Test Workflow" folder runs all tests in correct order

---

## 🤝 Support

For issues or questions:
1. Check the full documentation in `TESTING_API_DOCUMENTATION.md`
2. Review the controller code in `TestingController.php`
3. Run the PHP test script for comprehensive testing

---

## ✅ Quick Validation Checklist

After setup, verify everything works:

- [ ] Can create customers in tree
- [ ] Can view tree statistics
- [ ] Can add points to users
- [ ] Can generate supporting bonuses
- [ ] Can check bonuses for validation
- [ ] Can view customer details
- [ ] All points propagate correctly up the tree
- [ ] Bonus totals match expected values

---

**Happy Testing! 🎉**


