const { ListenerEngine, logger, LogLevel } = require('web3-payment-notifier');
const mysql = require('mysql2/promise');

// Enable info logging
logger.setLevel(LogLevel.INFO);

// Create networks configuration - everything in one config!
const networks = [
    {
        id: "abstract-testnet",
        chainId: 11124,
        rpc: {
            http: "https://api.testnet.abs.xyz",
            ws: "wss://api.testnet.abs.xyz/ws"
        },
        tokens: {
            TOKEN: "0xEb653D74E30fA07E85a19F4BbA8aBc41bA4c4622",
            USDC: "0xe4C7fBB0a626ed208021ccabA6Be1566905E2dFc",
            CTT: "0xF1f196AAfB0f3be2e8aA6a6365e7FE5A72e6F2f7"
        },
        // Wallet subscriptions embedded directly in network config
        walletSubscriptions: [
            {
                userId: "1",
                network: "abstract-testnet",
                wallet: "0x0db9363cE61D834d00Ba1Ac6B6d1FF166C9Df507",
                tokens: ["USDC", "TOKEN", "CTT"]
            }
        ]
    }
];

// Deposit status configuration
const DEPOSIT_STATUS = {
    PENDING: 0,
    SUCCESS: 1,
    FAILED: 2
};

// Network to chainId mapping
const NETWORK_CHAIN_IDS = {
    'abstract-testnet': 11124,
    '11124': 11124
};

// Simple queue to process pending deposits
const processingQueue = [];
let isProcessing = false;

// Start monitoring
async function main() {
    console.log("Starting simple event listener...\n");
    
    // Create MySQL pool (XAMPP default: root user, no password)
    const dbPool = mysql.createPool({
        host: '127.0.0.1',
        port: 3306,
        user: 'root',
        password: '',
        database: 'te-net',
        waitForConnections: true,
        connectionLimit: 10,
        queueLimit: 0
    });


    async function ensureSchema() {
        await dbPool.execute(`CREATE TABLE IF NOT EXISTS wallet_transactions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            wallet_id INT,
            token_amount DECIMAL(20,8) DEFAULT 0,
            usdt_amount DECIMAL(20,8) DEFAULT 0,
            type VARCHAR(50),
            \`from\` VARCHAR(50),
            status TINYINT DEFAULT 0,
            txHash VARCHAR(255) UNIQUE,
            network VARCHAR(255),
            confirmed TINYINT DEFAULT 0,
            isLocked TINYINT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )`);
    }

    async function saveDeposit(ev, walletId) {
        try {
            await ensureSchema();
            const network = ev.network || ev.chainId || 'unknown';
            const usdtAmount = parseFloat(ev.value) || 0;
            await dbPool.execute(
                'INSERT INTO wallet_transactions (wallet_id, token_amount, usdt_amount, type, \`from\`, status, txHash, network) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE usdt_amount=VALUES(usdt_amount)',
                [walletId, 0, usdtAmount, 'DEPOSIT', 'CUSTOMER', DEPOSIT_STATUS.PENDING, ev.transactionHash, network]
            );
            console.log(`   📝 Wallet transaction saved with status: pending (${DEPOSIT_STATUS.PENDING})`);
        } catch (err) {
            console.error('DB save error:', err.message || err);
        }
    }

    async function markConfirmed(txHash) {
        try {

            
            // Get transaction details
            const [transactions] = await dbPool.execute(
                'SELECT wallet_id, usdt_amount FROM wallet_transactions WHERE txHash = ? LIMIT 1',
                [txHash]
            );

            if (transactions.length > 0) {
                const transaction = transactions[0];
                const walletId = transaction.wallet_id;
                const depositedUSDTAmount = parseFloat(transaction.usdt_amount);

                // Get wallet by address
                const [wallets] = await dbPool.execute(
                    'SELECT id, deposited_token_amount, usdt_amount FROM wallets WHERE wallet_id = ? LIMIT 1',
                    [transactions[0].wallet_id]
                );

                if (wallets.length > 0) {
                    const wallet = wallets[0];

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
                        'UPDATE wallets SET deposited_token_amount = ?, usdt_amount = ?, updated_at = NOW() WHERE id = ?',
                        [
                            parseFloat(wallet.deposited_token_amount) + depositedTEXAmount,
                            parseFloat(wallet.usdt_amount) + depositedUSDTAmount,
                            wallet.id
                        ]
                    );

                    console.log(`   💰 Wallet updated: +${depositedTEXAmount.toFixed(6)} TEX, +${depositedUSDTAmount} USDT`);
                }
            }

            // Update transaction status to SUCCESS
            await dbPool.execute('UPDATE wallet_transactions SET confirmed=1, status=? WHERE txHash = ?', [DEPOSIT_STATUS.SUCCESS, txHash]);
            console.log(`   ✅ Transaction status updated to: success (${DEPOSIT_STATUS.SUCCESS})`);
        } catch (err) {
            console.error('DB update error:', err.message || err);
        }
    }

    // Create engine without custom handler - events will be emitted
    const engine = new ListenerEngine();
    
    // Listen for deposit events - NO CUSTOM CLASS NEEDED!
    engine.on('deposit', async (event) => {
        console.log('\n🔔 DEPOSIT EVENT!');
        console.log(`   User ID: ${event.userId}`);
        console.log(`   Token: ${event.token}`);
        console.log(`   Amount: ${event.value}`);
        console.log(`   Wallet: ${event.wallet}`);
        console.log(`   TxHash: ${event.transactionHash}`);
        
        // Query wallets table to get wallet_id
        let walletId = null;
        try {
            const [rows] = await dbPool.execute(
                'SELECT id, customer_id FROM wallets WHERE eth_wallet_address = ? LIMIT 1',
                [event.wallet.toLowerCase()]
            );
            
            if (rows.length > 0) {
                walletId = rows[0].id;
                const customerId = rows[0].customer_id;
                console.log(`   🔍 Found Wallet ID: ${walletId}`);
                console.log(`   👤 Customer ID: ${customerId}`);
                
                // Create wallet transaction record (similar to PHP WalletTransactionFacade::create)
                try {
                    // Convert token amount from wei to USDT (assuming 6 decimals for USDT)
                    const depositedUSDTAmount = event.token === 'USDC' || event.token === 'USDT' 
                        ? parseFloat(event.value) / 1e6 
                        : parseFloat(event.value) / 1e18;
                    
                    event.value = depositedUSDTAmount;
                    await saveDeposit(event, walletId);
                    console.log(`   💾 Wallet transaction saved: ${depositedUSDTAmount} USDT`);
                } catch (txError) {
                    console.error(`   ❌ Error creating wallet transaction: ${txError.message}`);
                }
            } else {
                console.log(`   ⚠️  Wallet not found in database: ${event.wallet}`);
            }
        } catch (error) {
            console.error(`   ❌ Error querying wallet: ${error.message}`);
        }
        
        // Save deposit to database with pending status
        
    });
    
    // Listen for confirmation events
    engine.on('confirmation', (event) => {
        console.log('\n✅ TRANSACTION CONFIRMED!');
        console.log(`   TxHash: ${event.transactionHash}`);
        console.log(`   Confirmations: ${event.confirmations}`);
        console.log(`   Block: ${event.blockNumber}`);
        
        // 💰 THIS IS WHERE YOU CREDIT THE USER!
        // - Update database: user.balance += amount
        // - Mark transaction as confirmed
        // - Send email notification
        // Mark transaction as confirmed in DB
        markConfirmed(event.transactionHash).catch(console.error);
    });
    
    // Listen for all transfer events (optional)
    engine.on('transfer', (event) => {
        console.log('\n📤 Transfer event:', event.transactionHash);
    });
    
    await engine.start(networks);

    console.log("\n✅ Monitoring started!");
    console.log("Listening for events...");
    console.log("Press Ctrl+C to stop.\n");

    // ========== CRON JOB: Check pending deposits every 5 seconds ==========
    
    // Function to fetch and lock pending deposits
    async function fetchAndLockPendingDeposits() {
        try {
            // Fetch pending, unlocked transactions
            const [rows] = await dbPool.execute(
                'SELECT id, network, txHash FROM wallet_transactions WHERE status = ? AND isLocked = 0 LIMIT 10',
                [DEPOSIT_STATUS.PENDING]
            );

            if (rows.length > 0) {
                console.log(`\n📋 Found ${rows.length} pending transactions to check`);

                // Lock these transactions
                const ids = rows.map(r => r.id);
                const placeholders = ids.map(() => '?').join(',');
                await dbPool.execute(
                    `UPDATE wallet_transactions SET isLocked = 1 WHERE id IN (${placeholders})`,
                    ids
                );

                // Add to queue
                rows.forEach(transaction => {
                    processingQueue.push(transaction);
                });

                console.log(`   🔒 Locked ${rows.length} transactions for processing`);
            }
        } catch (error) {
            console.error(`❌ Error fetching pending transactions: ${error.message}`);
        }
    }

    // Function to process queue
    async function processQueue() {
        if (isProcessing || processingQueue.length === 0) {
            return;
        }

        isProcessing = true;

        while (processingQueue.length > 0) {
            const transaction = processingQueue.shift();
            
            try {
                console.log(`\n⏳ Checking confirmation for TxHash: ${transaction.txHash}`);
                
                // Get chainId from network
                const chainId = NETWORK_CHAIN_IDS[transaction.network] || 11124;
                console.log(`   🔗 Using chainId: ${chainId}`);

                // Wait for transaction confirmation (reusing same engine)
                await engine.waitForTransactionConfirmation(
                    chainId,
                    transaction.txHash,
                    5
                );

                // If successful, update status to SUCCESS
                await dbPool.execute(
                    'UPDATE wallet_transactions SET status = ?, confirmed = 1, isLocked = 0 WHERE id = ?',
                    [DEPOSIT_STATUS.SUCCESS, transaction.id]
                );

                console.log(`   ✅ Transaction confirmed! Updated to SUCCESS`);

            } catch (error) {
                console.error(`   ❌ Error checking confirmation: ${error.message}`);
                
                // Unlock the transaction so it can be retried later
                await dbPool.execute(
                    'UPDATE wallet_transactions SET isLocked = 0 WHERE id = ?',
                    [transaction.id]
                );
            }
        }

        isProcessing = false;
    }

    // Run cron job every 5 seconds
    setInterval(async () => {
        await fetchAndLockPendingDeposits();
        await processQueue();
    }, 5000);

    console.log("✅ Cron job started! Checking pending transactions every 5 seconds...\n");
}

main().catch(console.error);