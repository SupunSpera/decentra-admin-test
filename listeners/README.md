# Wallet Transaction Listener Setup Guide

This guide explains how to set up and run the Node.js wallet transaction listener service that monitors blockchain transactions and automatically confirms deposits in your Laravel application.

## Prerequisites

- Node.js (v14 or higher)
- npm (v6 or higher)
- Access to the npm package for wallet transaction listening
- Laravel application running and accessible

## Installation Steps

### 1. Install the npm Package

Install the `web3-payment-notifier` package:

```bash
cd /opt/homebrew/var/www/projects/TE-NET-ADMIN
npm install web3-payment-notifier
```

**Package Documentation:** https://www.npmjs.com/package/web3-payment-notifier

### 2. Install Additional Dependencies

Install the required dependencies for the listener service:

```bash
npm install axios dotenv
```

### 3. Configure the Listener Script

The listener script is already configured to use `web3-payment-notifier`. No changes needed unless you want to customize the behavior.

### 4. Configure Environment Variables

Add the following variables to your Laravel root `.env` file (the same `.env` file used by Laravel):

```env
# Wallet Transaction Listener Configuration
LARAVEL_API_URL=http://localhost:8000
LARAVEL_API_TOKEN=your-api-token-if-needed
PROVIDER_URL=https://mainnet.infura.io/v3/YOUR_PROJECT_ID
CONFIRMATIONS=1
```

**Important:** 
- The listener reads from the root `.env` file (same as Laravel)
- Set `LARAVEL_API_URL` to your Laravel application's base URL
- If your Laravel API requires authentication, set `LARAVEL_API_TOKEN`
- **Required:** Set `PROVIDER_URL` with your Ethereum node provider URL (Infura, Alchemy, QuickNode, etc.)
- Set `CONFIRMATIONS` to the number of block confirmations to wait (default: 1)
- You can also use `BLOCKCHAIN_RPC_URL` instead of `PROVIDER_URL` if preferred

### 5. Update Laravel Configuration (Optional)

If you want to add API authentication for the listener endpoints, you can:

1. Add a middleware to protect the `/api/wallet-addresses` endpoint
2. Generate an API token and add it to your `.env` file
3. Update the listener script to use the token

## Running the Listener

### Development Mode

Run the listener directly with Node.js:

```bash
node listeners/wallet-transaction-listener.js
```

### Production Mode (Using PM2)

For production, it's recommended to use PM2 to keep the listener running:

1. Install PM2 globally:
   ```bash
   npm install -g pm2
   ```

2. Create a PM2 ecosystem file (`ecosystem.config.js`):
   ```javascript
   module.exports = {
     apps: [{
       name: 'wallet-transaction-listener',
       script: 'listeners/wallet-transaction-listener.js',
       interpreter: 'node',
       instances: 1,
       autorestart: true,
       watch: false,
       max_memory_restart: '1G',
       env: {
         NODE_ENV: 'production'
       }
     }]
   };
   ```

3. Start the listener with PM2:
   ```bash
   pm2 start ecosystem.config.js
   ```

4. Check status:
   ```bash
   pm2 status
   ```

5. View logs:
   ```bash
   pm2 logs wallet-transaction-listener
   ```

6. Stop the listener:
   ```bash
   pm2 stop wallet-transaction-listener
   ```

### Running as a System Service

You can also set up the listener as a systemd service on Linux. Create a service file at `/etc/systemd/system/wallet-listener.service`:

```ini
[Unit]
Description=Wallet Transaction Listener
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/opt/homebrew/var/www/projects/TE-NET-ADMIN
ExecStart=/usr/bin/node listeners/wallet-transaction-listener.js
Restart=always
RestartSec=10
Environment=NODE_ENV=production

[Install]
WantedBy=multi-user.target
```

Then enable and start the service:

```bash
sudo systemctl enable wallet-listener
sudo systemctl start wallet-listener
sudo systemctl status wallet-listener
```

## How It Works

1. **Listener Startup**: The listener service starts and fetches all wallet addresses from your Laravel application via the `/api/wallet-addresses` endpoint.

2. **Monitoring**: The `web3-payment-notifier` package monitors the blockchain for transactions using your configured RPC provider.

3. **Transaction Detection**: When a transaction is detected to any of the monitored wallet addresses, the listener receives a `data` event.

4. **Deposit Confirmation**: The listener converts the transaction value from Wei to USDT and calls the Laravel `/api/confirm-deposit` endpoint with the transaction details.

5. **Database Update**: The Laravel `confirmDeposit` function processes the deposit, updates the wallet balance, creates a transaction record, and deletes any pending deposit requests.

## API Endpoints

### GET `/api/wallet-addresses`

Returns all wallet addresses that need to be monitored.

**Response:**
```json
{
  "success": true,
  "addresses": ["0x123...", "0x456..."],
  "count": 2
}
```

### POST `/api/confirm-deposit`

Called by the listener when a deposit is detected.

**Request Body:**
```json
{
  "status": "success",
  "address": "0x123...",
  "token_balance": 100.50,
  "transaction_hash": "0xabc...",
  "block_number": 12345678,
  "timestamp": "2024-01-01T00:00:00Z"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Deposit confirmed successfully",
  "wallet_id": 1,
  "transaction_id": 123,
  "deposited_usdt": 100.50,
  "deposited_tex": 50.25
}
```

## Troubleshooting

### Listener Not Starting

- Check that `web3-payment-notifier` is installed: `npm list web3-payment-notifier`
- Verify `PROVIDER_URL` is set correctly in the root `.env` file
- Check Node.js version: `node --version` (should be v14+)
- Ensure your RPC provider URL is accessible and valid
- Review the error logs

### No Transactions Detected

- Verify wallet addresses are being fetched correctly (check `/api/wallet-addresses` endpoint)
- Check `PROVIDER_URL` is correct and accessible
- Ensure your RPC provider has access to the network you're monitoring
- Verify wallet addresses in the database have `eth_wallet_address` set
- Check listener logs for any connection errors
- Test by sending a small transaction to one of the monitored addresses

### Laravel API Errors

- Verify `LARAVEL_API_URL` is correct
- Check Laravel application is running
- Verify API token if authentication is required
- Check Laravel logs: `storage/logs/laravel.log`

### Duplicate Transactions

Currently, the system doesn't prevent duplicate processing based on transaction hash. To add this feature:

1. Create a migration to add `transaction_hash` and `block_number` columns to `wallet_transactions` table
2. Add these fields to the `WalletTransaction` model's `$fillable` array
3. Add a method `getByTransactionHash()` to `WalletTransactionService`
4. Uncomment the duplicate check in `WalletDepositController::confirmDeposit()`

## Testing

To test the listener:

1. Start the listener service
2. Make a test transaction to one of the monitored wallet addresses
3. Check the Laravel logs to see if the deposit was processed
4. Verify the wallet balance was updated in the database

## Support

For issues related to:
- **web3-payment-notifier package**: Refer to https://www.npmjs.com/package/web3-payment-notifier
- **Laravel integration**: Check the Laravel logs and API responses
- **Blockchain connectivity**: Verify RPC endpoint (PROVIDER_URL) and network settings
- **RPC Provider**: Ensure your Infura/Alchemy/QuickNode account is active and has sufficient quota

## Notes

- The listener runs continuously and should be kept running at all times
- Monitor the listener logs regularly for errors
- Consider setting up log rotation for production environments
- The listener automatically handles reconnection if the connection is lost

