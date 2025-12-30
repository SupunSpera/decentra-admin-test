/**
* Advanced Wallet Transaction Listener Service for Laravel
*
* This service uses a custom or advanced web3-payment-notifier library (with ListenerEngine)
* to monitor multiple networks, specific tokens (ERC20), and wallet subscriptions configured
* dynamically from the Laravel database.
*
* Key Features:
* - Direct database connection (no API calls)
* - Central configuration of networks, tokens, and wallet subscriptions fetched from DB
* - Support for native token transfers AND ERC20 token transfers (e.g., USDC, custom tokens)
* - Emits structured 'deposit' and 'confirmation' events
* - Handles confirmations properly
* - Direct database updates for confirmed deposits
* - Automatic decryption of wallet private keys (AES-256-CBC)
* - Graceful shutdown and better logging
*
* Environment Variables Required:
* - DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD, DB_DATABASE (database connection)
* - ENCRYPTION_KEY (optional, defaults to config value if not set)
* - ADMIN_TOKEN_ADDRESS (token address for fund collection)
* - ADMIN_WALLET_ADDRESS (destination wallet for collected funds)
* - GAS_WALLET_PRIVATE_KEY (private key for gas payments)
* - CONFIRMATIONS (optional, defaults to 5)
*
* Installation:
* npm install web3-payment-notifier mysql2 dotenv web3-funds-collector
* node listeners/new-listener.js
*/




const { ListenerEngine, logger: libLogger, LogLevel } = require('web3-payment-notifier');
// const { basicCollector } = require('./collector');
const { FundCentralizing } = require('web3-funds-collector');
const mysql = require('mysql2/promise');
const dotenv = require('dotenv');
const crypto = require('crypto');


let fundManager = null;


// Load .env from Laravel root
dotenv.config({ path: __dirname + '/../.env' });




// Enable info logging from the library
libLogger.setLevel(LogLevel.INFO);




// Database configuration
const DB_CONFIG = {
   host: process.env.DB_HOST || '127.0.0.1',
   port: parseInt(process.env.DB_PORT || '3306', 10),
   user: process.env.DB_USERNAME || 'supun',
   password: process.env.DB_PASSWORD || '100100100',
   database: process.env.DB_DATABASE || 'decentra_test',
   waitForConnections: true,
   connectionLimit: 10,
   queueLimit: 0
};




const DEFAULT_CONFIRMATIONS = parseInt(process.env.CONFIRMATIONS || '5', 10);




// Deposit status configuration
const DEPOSIT_STATUS = {
   PENDING: 0,
   SUCCESS: 1,
   FAILED: 2
};


const COLLECTED = {
    TRUE: 1,
    FALSE: 0
}




// Simple console logger
const appLogger = {
   info: (msg, data) => console.log(`[INFO] ${msg}`, data || ''),
   error: (msg, data) => console.error(`[ERROR] ${msg}`, data || ''),
   warn: (msg, data) => console.warn(`[WARN] ${msg}`, data || '')
};

/**
 * Decrypt private key using AES-256-CBC (matching Laravel encryption)
 * @param {string} encryptedData - Base64 encoded encrypted private key
 * @param {string} encryptionKey - Encryption key from config (default from .env or config)
 * @returns {string} - Decrypted private key
 */
function decryptPrivateKey(encryptedData) {
   try {
       // Get encryption key from env or use default from config
       const encryptionKey = process.env.ENCRYPTION_KEY || 'Z4q9tzG8YXJ3LvDb6NwXrCvQh2KM5pTa';
       
       // Create key hash (SHA256, truncated to 32 bytes) - matching PHP logic
       const key = crypto.createHash('sha256').update(encryptionKey).digest().slice(0, 32);
       
       // Decode base64
       const data = Buffer.from(encryptedData, 'base64');
       
       // Extract IV (first 16 bytes) and encrypted data (rest)
       const iv = data.slice(0, 16);
       const encrypted = data.slice(16);
       
       // Decrypt using AES-256-CBC
       const decipher = crypto.createDecipheriv('aes-256-cbc', key, iv);
       let decrypted = decipher.update(encrypted, null, 'utf8');
       decrypted += decipher.final('utf8');
       
       return decrypted;
   } catch (error) {
       appLogger.error('Failed to decrypt private key', error.message);
       throw new Error(`Decryption failed: ${error.message}`);
   }
}




/**
* Fetch active crypto networks from database
*/
async function getCryptoNetworks(dbPool) {
   try {
       const [rows] = await dbPool.execute(
           'SELECT id, name, chain_id, rpc_http, rpc_ws, tokens, is_active FROM crypto_networks WHERE is_active = 1 ORDER BY id DESC'
       );
     
       return rows.map(row => ({
           id: row.id,
           name: row.name,
           chain_id: row.chain_id,
           rpc_http: row.rpc_http,
           rpc_ws: row.rpc_ws,
           tokens: row.tokens,
           is_active: row.is_active === 1
       }));
   } catch (error) {
       appLogger.error('Failed to fetch crypto networks from database', error.message);
       return [];
   }
}




/**
* Fetch wallet addresses from database
*/
async function getWalletAddresses(dbPool) {
   try {
       const [rows] = await dbPool.execute(
           'SELECT DISTINCT eth_wallet_address FROM wallets WHERE eth_wallet_address IS NOT NULL AND eth_wallet_address != ""'
       );
     
       return rows.map(row => row.eth_wallet_address);
   } catch (error) {
       appLogger.error('Failed to fetch wallet addresses from database', error.message);
       return [];
   }
}




/**
* Get wallet ID by address
*/
async function getWalletIdByAddress(dbPool, address) {
   console.log('addresses',address);
   try {
       const [rows] = await dbPool.execute(
           'SELECT id, customer_id FROM wallets WHERE eth_wallet_address = ? LIMIT 1',
           [address.toLowerCase()]
       );
     
       return rows.length > 0 ? { id: rows[0].id, customer_id: rows[0].customer_id } : null;
   } catch (error) {
       appLogger.error('Failed to get wallet ID by address', error.message);
       return null;
   }
}




/**
* Save deposit to database (pending status)
*/
async function saveDeposit(dbPool, event, walletId) {
   try {
       // Convert token amount based on token type
       let usdtAmount = parseFloat(event.value) || 0;
       if (event.token && event.token !== 'NATIVE') {
           // For ERC20 tokens, value is usually in wei (18 decimals) or token decimals
           // Adjust based on your token's decimal places
           usdtAmount = usdtAmount / 1e18; // Assuming 18 decimals, adjust if needed
       }
     
       // Check if transaction already exists to avoid duplicates
       const [existing] = await dbPool.execute(
           'SELECT id FROM wallet_transactions WHERE wallet_id = ? AND type = ? AND usdt_amount = ? AND tx_hash = ? ORDER BY id DESC LIMIT 1',
           [walletId, 1, usdtAmount, event.transactionHash] // type = 1 is DEPOSIT
       );
       console.log('existingssss',event);
     
       if (existing.length === 0) {
           await dbPool.execute(
               'INSERT INTO wallet_transactions (wallet_id,network, token_amount, usdt_amount, type, `from`, status, tx_hash, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())',
               [walletId, event.network, 0, usdtAmount, 1, 0, DEPOSIT_STATUS.PENDING, event.transactionHash] // type = 1 (DEPOSIT), from = 0 (CUSTOMER)
           );
         
           appLogger.info(`📝 Deposit saved: wallet_id=${walletId}, amount=${usdtAmount}, tx_hash=${event.transactionHash}, status=PENDING`);
       } else {
           appLogger.info(`📝 Deposit already exists, skipping duplicate`);
       }
   } catch (error) {
       appLogger.error('Failed to save deposit to database', error.message);
   }
}




/**
* Process confirmed deposit - update wallet and transaction status
*/
async function processConfirmedDeposit(dbPool, event) {
   console.log('eventsss',event);
   try {
       // Get transaction details from wallet_transactions table using transaction hash
       const [transactions] = await dbPool.execute(
           'SELECT wallet_id, usdt_amount FROM wallet_transactions WHERE tx_hash = ? LIMIT 1',
           [event.transactionHash]
       );




       if (transactions.length === 0) {
           appLogger.warn(`No transaction found for tx_hash: ${event.transactionHash}`);
           return;
       }




       const walletId = transactions[0].wallet_id;
       const depositedUSDTAmount = parseFloat(transactions[0].usdt_amount);




       // Get wallet details using wallet_id from transaction
       const [wallets] = await dbPool.execute(
           'SELECT id, customer_id, deposited_token_amount, usdt_amount FROM wallets WHERE id = ? LIMIT 1',
           [walletId]
       );




       if (wallets.length === 0) {
           appLogger.warn(`Wallet not found with id: ${walletId}`);
           return;
       }




       const wallet = wallets[0];
       const customerId = wallet.customer_id;




       // Get currency pool to calculate TEX value
       const [pools] = await dbPool.execute('SELECT usdt_amount, tex_amount FROM currency_pools LIMIT 1');
     
       let depositedTEXAmount = 0;
       if (pools.length > 0) {
           const pool = pools[0];
           const usdtAmount = parseFloat(pool.usdt_amount);
           const texAmount = parseFloat(pool.tex_amount);




           // Calculate TEX value (same as PHP logic)
           const texValue = texAmount > 0 ? usdtAmount / texAmount : 0;




           // Calculate deposited TEX amount
           depositedTEXAmount = texValue > 0 ? depositedUSDTAmount / texValue : 0;




           // Save current TEX value
           await dbPool.execute(
               'INSERT INTO token_values (token_value, created_at, updated_at) VALUES (?, NOW(), NOW())',
               [texValue]
           );
       }




       // Update wallet (same as PHP WalletFacade::update)
       await dbPool.execute(
           'UPDATE wallets SET deposited_token_amount = deposited_token_amount + ?, usdt_amount = usdt_amount + ?, updated_at = NOW() WHERE id = ?',
           [depositedTEXAmount, depositedUSDTAmount, wallet.id]
       );




       // Update transaction status to SUCCESS using transaction hash
       await dbPool.execute(
           'UPDATE wallet_transactions SET collected = ?, status = ?, updated_at = NOW() WHERE tx_hash = ?',
           [COLLECTED.FALSE, DEPOSIT_STATUS.SUCCESS, event.transactionHash]
       );




       const [uncollectedTransactions] = await dbPool.execute(
            `SELECT
                wt.id,
                wt.wallet_id,
                wt.tx_hash,
                wt.network,
                wt.confirmed,
                wt.isLocked,
                wt.token_amount,
                wt.usdt_amount,
                wt.type,
                wt.from,
                wt.status,
                w.customer_id,
                w.eth_wallet_address,
                w.eth_wallet_private_key
            FROM wallet_transactions wt
            INNER JOIN wallets w ON wt.wallet_id = w.id
            WHERE wt.collected = ? AND wt.status = ?`,
            [COLLECTED.FALSE, DEPOSIT_STATUS.SUCCESS]
       );


       
         if (uncollectedTransactions.length > 0) {
            await Promise.all(uncollectedTransactions.map(async (transaction) => {
                try {
                    const [chainIdResult] = await dbPool.execute(
                        'SELECT chain_id FROM crypto_networks WHERE name = ? LIMIT 1', [transaction.network]
                    )
                    
                    if (chainIdResult.length === 0) {
                        appLogger.error(`Chain ID not found for network: ${transaction.network}`);
                        return; // Skip this transaction
                    }
                    
                    console.log('fundManager',fundManager);
                    console.log('chainIdResult',chainIdResult);
                    console.log('transaction',transaction);
                    console.log('process.env.ADMIN_TOKEN_ADDRESS',process.env.ADMIN_TOKEN_ADDRESS);
                    console.log('process.env.ADMIN_WALLET_ADDRESS',process.env.ADMIN_WALLET_ADDRESS);
                    console.log('transaction.usdt_amount',transaction.usdt_amount);
                    console.log('transaction.tx_hash',transaction.tx_hash);
                    console.log('transaction.id',transaction.id);
                    console.log('transaction.network',transaction.network);
                    console.log('transaction.confirmed',transaction.confirmed);
                    
                    // Decrypt the private key (it's stored encrypted in the database)
                    let decryptedPrivateKey;
                    try {
                        decryptedPrivateKey = decryptPrivateKey(transaction.eth_wallet_private_key);
                        // Ensure it starts with 0x
                        if (!decryptedPrivateKey.startsWith('0x')) {
                            decryptedPrivateKey = `0x${decryptedPrivateKey}`;
                        }
                    } catch (decryptError) {
                        appLogger.error(`Failed to decrypt private key for transaction ${transaction.tx_hash}:`, decryptError.message);
                        return; // Skip this transaction
                    }
                    
                    const result = await fundManager.transferToken({
                        chainId: chainIdResult[0].chain_id, // Specify which network to use
                        sourcePrivateKey: decryptedPrivateKey,
                        tokenAddress: process.env.ADMIN_TOKEN_ADDRESS, // USDC on Polygon
                        destinationAddress: process.env.ADMIN_WALLET_ADDRESS,
                        amount: transaction.usdt_amount.toString()
                    });
                   
                    appLogger.info(`✅ Transaction collected: tx_hash=${transaction.tx_hash}, amount=${transaction.usdt_amount}`);
                   
                    // Update transaction as collected
                    await dbPool.execute(
                        'UPDATE wallet_transactions SET collected = ?, updated_at = NOW() WHERE id = ?',
                        [COLLECTED.TRUE, transaction.id]
                    );
                } catch (error) {
                    appLogger.error(`Failed to collect transaction ${transaction.tx_hash}:`, error.message);
                }
            }));
         }
       // Delete wallet deposit request if exists
    //    await dbPool.execute(
    //        'DELETE FROM wallet_deposits WHERE customer_id = ?',
    //        [customerId]
    //    );




       appLogger.info(`💰 Deposit confirmed: wallet_id=${walletId}, +${depositedTEXAmount.toFixed(6)} TEX, +${depositedUSDTAmount} USDT`);
   } catch (error) {
       appLogger.error('Failed to process confirmed deposit', error.message);
   }
}




/**
* Build the networks configuration array for ListenerEngine
*/
async function buildNetworksConfig(dbPool) {
   const networks = await getCryptoNetworks(dbPool);
   const walletAddresses = await getWalletAddresses(dbPool);
 
   appLogger.info(`Fetched ${networks.length} network(s) and ${walletAddresses.length} wallet address(es)`);




   if (networks.length === 0) {
       appLogger.warn('No active crypto networks found in database');
       return [];
   }
 
   if (walletAddresses.length === 0) {
       appLogger.warn('No wallet addresses found to monitor');
       return [];
   }
 
   const config = networks.map(network => {
       const rpc = {
           http: network.rpc_http,
           ws: network.rpc_ws || undefined
       };




       // Parse tokens from JSON string if it's a string
       let networkTokens = {};
       if (network.tokens) {
           if (typeof network.tokens === 'string') {
               try {
                   networkTokens = JSON.parse(network.tokens);
               } catch (e) {
                   appLogger.warn(`Failed to parse tokens for network ${network.name}:`, e.message);
                   networkTokens = {};
               }
           } else if (typeof network.tokens === 'object') {
               networkTokens = network.tokens;
           }
       }




       // Extract token symbols (keys from tokens object) and add NATIVE
       const tokenSymbols = ['NATIVE', ...Object.keys(networkTokens)];




       // Map wallet addresses to wallet subscriptions for this network
       const walletSubscriptions = walletAddresses.map(address => ({
           userId: "user-1", // Hardcoded for now as requested
           network: network.name.toLowerCase(), // Use actual network name
           wallet: address.toLowerCase(),
           tokens: tokenSymbols // Use tokens from network configuration
       }));




       return {
           id: network.name.toLowerCase().replace(/\s+/g, '-'),
           chainId: network.chain_id,
           rpc: rpc,
           tokens: networkTokens, // e.g., { SPT2: '0x...', USDC: '0x...' }
           walletSubscriptions: walletSubscriptions
       };
   });




   // Filter out networks with no wallet subscriptions
   const filteredConfig = config.filter(net => net.walletSubscriptions.length > 0);
 
   appLogger.info(`Built configuration for ${filteredConfig.length} network(s) with wallet subscriptions`);
 
   return filteredConfig;
}


async function basicCollector(dbPool) {
  try {
     const networks = await getCryptoNetworks(dbPool);
   
    // Map database networks to FundCentralizing format
    const networkConfigs = networks.map(network => ({
      chainId: network.chain_id,
      name: network.name,
      rpcUrl: network.rpc_http,
      nativeCurrency: {
        name: 'USDT',
        symbol: 'USDT',
        decimals: 18
      }
    }));
   
    // Initialize the library with network configurations
    fundManager = new FundCentralizing({
      networks: networkConfigs,
      gasWalletPrivateKey: process.env.GAS_WALLET_PRIVATE_KEY
    });


    console.log('Transfer object successfully created');
  } catch (error) {
    console.error('Transfer failed:', error.message);
  }
}


/**
* Start the listener
*/
async function startListener() {
   appLogger.info('Starting advanced wallet transaction listener...');




   // Create MySQL connection pool
   const dbPool = mysql.createPool(DB_CONFIG);
   appLogger.info('Database connection pool created');
   
   // Initialize fund collector
   await basicCollector(dbPool);




   const networksConfig = await buildNetworksConfig(dbPool);




   if (networksConfig.length === 0) {
       appLogger.error('No networks with wallet subscriptions to monitor. Exiting.');
       await dbPool.end();
       process.exit(1);
   }




   appLogger.info(`Monitoring ${networksConfig.length} network(s) with subscriptions`);




   const engine = new ListenerEngine();
 
   // Listen for initial deposit (unconfirmed)
   engine.on('deposit', async (event) => {
       appLogger.info('\n🔔 DEPOSIT DETECTED!', {
           userId: event.userId,
           token: event.token,
           amount: event.value,
           wallet: event.wallet,
           txHash: event.transactionHash,
           chainId: event.chainId
       });
     
       // Get wallet ID by address
       const walletInfo = await getWalletIdByAddress(dbPool, event.wallet);
       console.log('walletInfossss',walletInfo);
       if (walletInfo) {
           // Save deposit to database with pending status
           await saveDeposit(dbPool, event, walletInfo.id);
       } else {
           appLogger.warn(`Wallet not found for address: ${event.wallet}`);
       }
     
       // Wait for transaction confirmation
       await engine.waitForTransactionConfirmation(
           event.chainId,
           event.transactionHash,
           DEFAULT_CONFIRMATIONS
       );
   });




   // Listen for confirmation - this is where we credit/notify
   engine.on('confirmation', async (event) => {
       appLogger.info('\n✅ DEPOSIT CONFIRMED!', {
           txHash: event.transactionHash,
           confirmations: event.confirmations,
           block: event.blockNumber,
           token: event.token,
           amount: event.value,
           userId: event.userId
       });
       console.log('event-new-listener',event);




       // Process confirmed deposit - update wallet and transaction status
       await processConfirmedDeposit(dbPool, event);
   });




   // Optional: raw transfer events
   engine.on('transfer', (event) => {
       appLogger.info('📤 Transfer event (ignored if not subscribed)', event.transactionHash);
   });




   engine.on('error', (error) => {
       appLogger.error('ListenerEngine error:', error.message || error);
   });




   // Start monitoring all configured networks
   await engine.start(networksConfig);




   appLogger.info('\n✅ Advanced monitoring started successfully!');
   appLogger.info('Listening for deposits and confirmations across configured networks...');




   // Graceful shutdown
   const shutdown = async (signal) => {
       appLogger.info(`Received ${signal}. Shutting down...`);
       await engine.stop?.(); // If stop method exists
       await dbPool.end();
       process.exit(0);
   };




   process.on('SIGINT', () => shutdown('SIGINT'));
   process.on('SIGTERM', () => shutdown('SIGTERM'));
}




// Run
startListener().catch(err => {
   appLogger.error('Fatal error starting listener', err);
   process.exit(1);
});



