/**
 * Advanced Wallet Transaction Listener Service for Laravel
 * 
 * This service uses a custom or advanced web3-payment-notifier library (with ListenerEngine)
 * to monitor multiple networks, specific tokens (ERC20), and wallet subscriptions configured
 * dynamically from the Laravel database.
 * 
 * Key Improvements based on the reference code:
 * - Central configuration of networks, tokens, and wallet subscriptions fetched from DB.
 * - Support for native token transfers AND ERC20 token transfers (e.g., USDC, custom tokens).
 * - Emits structured 'deposit' and 'confirmation' events.
 * - Handles confirmations properly.
 * - Notifies Laravel API only on confirmed deposits.
 * - Graceful shutdown and better logging.
 * 
 * Assumptions:
 * - You are using an advanced version of 'web3-payment-notifier' that exports { ListenerEngine } 
 *   and supports the configuration format shown in the reference (networks with walletSubscriptions, tokens, etc.).
 * - Your Laravel app has tables/models for:
 *   - CryptoNetwork (id, name, chain_id, rpc_http, rpc_ws optional, is_active)
 *   - WalletAddress or User wallets (user_id, network_id or chain_id, wallet_address, monitored_tokens array or relation)
 *   - Tokens per network (symbol => contract address)
 * - Adjust the API endpoints and payload as needed.
 * 
 * Installation:
 * npm install web3-payment-notifier axios dotenv
 * node listeners/advanced-wallet-transaction-listener.js
 */

const { ListenerEngine, logger: libLogger, LogLevel } = require('web3-payment-notifier'); // Adjust import if needed
const axios = require('axios');
const dotenv = require('dotenv');

// Load .env from Laravel root
dotenv.config({ path: __dirname + '/../.env' });

// Enable info logging from the library
libLogger.setLevel(LogLevel.INFO);

// Configuration
const LARAVEL_API_URL = process.env.APP_URL || 'http://te-net-admin.sp/';
const LARAVEL_API_TOKEN = process.env.API_TOKEN || '';
const DEFAULT_CONFIRMATIONS = parseInt(process.env.CONFIRMATIONS || '5', 10); // Higher for safety

// Simple console logger
const appLogger = {
    info: (msg, data) => console.log(`[INFO] ${msg}`, data || ''),
    error: (msg, data) => console.error(`[ERROR] ${msg}`, data || ''),
    warn: (msg, data) => console.warn(`[WARN] ${msg}`, data || '')
};

/**
 * Fetch active crypto networks from Laravel
 */
async function getCryptoNetworks() {
    try {
        const response = await axios.get(`${LARAVEL_API_URL}/api/crypto-networks`, {
            headers: { 'Authorization': LARAVEL_API_TOKEN ? `Bearer ${LARAVEL_API_TOKEN}` : undefined }
        });
        return response.data.networks.filter(n => n.is_active) || [];
    } catch (error) {
        appLogger.error('Failed to fetch crypto networks', error.message);
        return [];
    }
}

/**
 * Fetch wallet subscriptions (users' wallets + tokens to monitor per network)
 * Adjust endpoint/response structure based on your Laravel API
 */
async function getWalletSubscriptions() {
    try {
        const response = await axios.get(`${LARAVEL_API_URL}/api/wallet-addresses`, {
            headers: { 'Authorization': LARAVEL_API_TOKEN ? `Bearer ${LARAVEL_API_TOKEN}` : undefined }
        });
        // Expected: array of { userId, network (or chain_id), wallet, tokens: ['NATIVE', 'USDC', 'TOKEN'] }
        return response.data.addresses || [];
    } catch (error) {
        appLogger.error('Failed to fetch wallet subscriptions', error.message);
        return [];
    }
}

/**
 * Fetch token addresses per network (symbol => contract)
 * Adjust if you have a separate endpoint or include in networks
 */
async function getTokensForNetworks() {
    try {
        const response = await axios.get(`${LARAVEL_API_URL}/api/network-tokens`, {
            headers: { 'Authorization': LARAVEL_API_TOKEN ? `Bearer ${LARAVEL_API_TOKEN}` : undefined }
        });
        // Expected: { chain_id: { USDC: '0x...', TOKEN: '0x...' } }
        return response.data.tokens || {};
    } catch (error) {
        appLogger.error('Failed to fetch network tokens', error.message);
        return {};
    }
}

/**
 * Notify Laravel about a confirmed deposit
 */
async function notifyLaravelDeposit(depositEvent) {
    try {
        const payload = {
            user_id: depositEvent.userId,
            network: depositEvent.network || depositEvent.chainId,
            wallet: depositEvent.wallet,
            token: depositEvent.token,
            amount: depositEvent.value, // Already human-readable
            transaction_hash: depositEvent.transactionHash,
            block_number: depositEvent.blockNumber,
            confirmations: depositEvent.confirmations,
            timestamp: new Date().toISOString()
        };

        appLogger.info('Notifying Laravel of confirmed deposit', payload);

        const response = await axios.post(
            `${LARAVEL_API_URL}/api/confirm-deposit`,
            payload,
            {
                headers: {
                    'Authorization': LARAVEL_API_TOKEN ? `Bearer ${LARAVEL_API_TOKEN}` : undefined
                }
            }
        );

        appLogger.info('Laravel notified successfully', response.data);
    } catch (error) {
        appLogger.error('Failed to notify Laravel', error.message);
        if (error.response) appLogger.error('Response', error.response.data);
    }
}

/**
 * Build the networks configuration array for ListenerEngine
 */
async function buildNetworksConfig() {
    const networks = await getCryptoNetworks();
    const tokensMap = await getTokensForNetworks();
    const walletAddresses = await getWalletSubscriptions();
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
                    networkTokens = network.tokens;
                    console.log('network.tokens',networkTokens);
                } catch (e) {
                    appLogger.warn(`Failed to parse tokens for network ${network.name}:`, e.message);
                    networkTokens = {};
                }
            } else if (typeof network.tokens === 'object') {
                networkTokens = network.tokens;
            }
        }

        // Use tokens from network or fallback to tokensMap
        const tokens = Object.keys(networkTokens).length > 0 
            ? networkTokens 
            : (tokensMap[network.chain_id] || {});

        // Extract token symbols (keys from tokens object) and add NATIVE
        const tokenSymbols = ['NATIVE', ...Object.keys(tokens)];

        // Map wallet addresses to wallet subscriptions for this network
        const walletSubscriptions = walletAddresses.map(address => ({
            userId: "user-1", // Hardcoded for now as requested
            network: network.name.toLowerCase(), // Use actual network name
            wallet: address.toLowerCase(),
            tokens: ["SPT2"] // Use tokens from network configuration
        }));
        console.log('walletSubscriptions', walletSubscriptions);

        return {
            id: network.name.toLowerCase().replace(/\s+/g, '-'),
            chainId: network.chain_id,
            rpc: rpc,
            tokens: {
                SPT2:"0x40d5c38e9d94a5283ee35ecc896dd650b8c52b13"
            }, // e.g., { SPT2: '0x...', USDC: '0x...' }
            walletSubscriptions: walletSubscriptions
        };
    });
    console.log('final config',config);

    // Filter out networks with no wallet subscriptions
    const filteredConfig = config.filter(net => net.walletSubscriptions.length > 0);
    console.log('filteredConfig',filteredConfig[0]?.walletSubscriptions);
    
    appLogger.info(`Built configuration for ${filteredConfig.length} network(s) with wallet subscriptions`);
    
    return filteredConfig;
}

/**
 * Start the listener
 */
async function startListener() {
    appLogger.info('Starting advanced wallet transaction listener...');

    const networksConfig = await buildNetworksConfig();

    if (networksConfig.length === 0) {
        appLogger.error('No networks with wallet subscriptions to monitor. Exiting.');
        process.exit(1);
    }

    appLogger.info(`Monitoring ${networksConfig.length} network(s) with subscriptions`);

    const engine = new ListenerEngine();
    console.log('networkConfigs',engine);
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
        
        await engine.waitForTransactionConfirmation(
            event.chainId,
            event.transactionHash,
            5
        );
    });
   
        
   


    // Listen for confirmation - this is where we credit/notify
    engine.on('confirmation',  async (event) => {
        appLogger.info('\n✅ DEPOSIT CONFIRMED!', {
            txHash: event.transactionHash,
            confirmations: event.confirmations,
            block: event.blockNumber,
            token: event.token,
            amount: event.value,
            userId: event.userId
        });

        // Notify Laravel (credit user balance, etc.)
        await notifyLaravelDeposit(event);
    });

    // Optional: raw transfer events
    engine.on('transfer', (event) => {
        appLogger.info('📤 Transfer event (ignored if not subscribed)', event.transactionHash);
    });

    engine.on('error', (error) => {
        appLogger.error('ListenerEngine error:', error.message || error);
    });

    // Start monitoring all configured networks
    // console.log('networksConfigsssssss',networksConfig);
    await engine.start(networksConfig);

    appLogger.info('\n✅ Advanced monitoring started successfully!');
    appLogger.info('Listening for deposits and confirmations across configured networks...');

    // Graceful shutdown
    const shutdown = async (signal) => {
        appLogger.info(`Received ${signal}. Shutting down...`);
        await engine.stop?.(); // If stop method exists
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