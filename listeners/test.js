import { ListenerEngine, EventHandler, TransactionConfirmationEvent, DepositEvent, logger, LogLevel, WalletSubscription } from '../src';


logger.setLevel(LogLevel.INFO);


/**
 * This handler will catch the confirmation event
 * when waitForTransactionConfirmation is called
 */
class ConfirmationListener implements EventHandler {
    private engine: ListenerEngine | null = null;
    private chainIdMap: Record<string, number> = {
        'abstract-testnet': 11124,
        'polygon-amoy': 80002
    };


    setEngine(engine: ListenerEngine): void {
        this.engine = engine;
    }


    /**
     * When a deposit is detected, automatically confirm it
     */
    async handleDeposit(event: DepositEvent): Promise<void> {
        logger.info(`\n========================================`);
        logger.info(`🔔 DEPOSIT DETECTED!`);
        logger.info(`========================================`);
        logger.info(`User ID: ${event.userId}`);
        logger.info(`Token: ${event.token}`);
        logger.info(`Amount: ${event.value}`);
        logger.info(`Wallet: ${event.wallet}`);
        logger.info(`Transaction Hash: ${event.transactionHash}`);
        logger.info(`Network: ${event.network}`);
        logger.info(`Block: ${event.blockNumber}`);
        logger.info(`========================================\n`);


        // Automatically confirm the transaction
        if (this.engine) {
            const chainId = this.chainIdMap[event.network];
            logger.info(`⏳ Auto-confirming transaction: ${event.transactionHash}\n`);


            try {
                await this.engine.waitForTransactionConfirmation(
                    chainId,
                    event.transactionHash,
                    5
                );
            } catch (error: any) {
                logger.error(`❌ Confirmation error: ${error.message}\n`);
            }
        }
    }


    handleTransfer(event: any): void {
        // Not needed for this example
    }


    /**
     * THIS WILL BE CALLED AUTOMATICALLY
     * when waitForTransactionConfirmation completes successfully
     */
    handleTransactionConfirmation(event: TransactionConfirmationEvent): void {
        logger.info(`\n========================================`);
        logger.info(`🎉 CONFIRMATION EVENT RECEIVED!`);
        logger.info(`========================================`);
        logger.info(`Chain ID: ${event.chainId}`);
        logger.info(`Transaction Hash: ${event.transactionHash}`);
        logger.info(`Block Number: ${event.blockNumber}`);
        logger.info(`Confirmations: ${event.confirmations}`);
        logger.info(`Timestamp: ${new Date(event.timestamp).toISOString()}`);
        logger.info(`========================================\n`);
       
        // HERE YOU CAN:
        // - Credit user's account
        // - Send notification
        // - Update database
        // - Trigger webhooks
        // - etc.
       
        logger.info(`💰 User can now be credited for transaction: ${event.transactionHash}\n`);
    }
}


// Define wallet subscriptions
const walletSubscriptions: WalletSubscription[] = [
    {
        userId: "user-1",
        network: "abstract-testnet",
        wallet: "0x0db9363cE61D834d00Ba1Ac6B6d1FF166C9Df507", // Replace with actual wallet
        tokens: ["USDC", "TOKEN", "CTT"]
    }
];


/**
 * Main function demonstrating event emission
 */
async function main() {
    logger.info('🚀 Starting confirmation event listener...\n');


    // Create handler and engine
    const listener = new ConfirmationListener();
    const engine = new ListenerEngine(listener, 5);
    listener.setEngine(engine);


    // Start the engine with network configuration
    await engine.start([
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
            walletSubscriptions
        }
    ]);


    logger.info('✅ Engine started and listening for deposits!\n');
    logger.info('📋 When a deposit is detected:');
    logger.info('   1. Deposit event will be logged');
    logger.info('   2. Transaction will be automatically confirmed');
    logger.info('   3. Confirmation event will be emitted\n');
    logger.info('💡 Send tokens to 0x0db9363cE61D834d00Ba1Ac6B6d1FF166C9Df507 to test!\n');
    logger.info('Press Ctrl+C to stop.\n');
}


main().catch(console.error);






Response

export interface TransferEvent {
    network: string;
    token: string;
    from: string;
    to: string;
    value: string;
    blockNumber: number;
    transactionHash: string;
    timestamp?: number;
}


export interface DepositEvent extends TransferEvent {
    userId: string;
    wallet: string;
}


export interface TransactionConfirmationEvent {
    chainId: number;
    transactionHash: string;
    blockNumber: number;
    confirmations: number;
    timestamp: number;
}




[abstract-testnet] ✅ MATCHED - Subscribed User Deposit
[INFO] 
========================================
[INFO] 🔔 DEPOSIT DETECTED!
[INFO] ========================================
[INFO] User ID: user-1
[INFO] Token: CTT
[INFO] Amount: 100000000000000000000
[INFO] Wallet: 0x0db9363cE61D834d00Ba1Ac6B6d1FF166C9Df507
[INFO] Transaction Hash: 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2dbeba8b41ed984a8abd
[INFO] Network: abstract-testnet
[INFO] Block: 16080467
[INFO] ========================================

[INFO] ⏳ Auto-confirming transaction: 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2
dbeba8b41ed984a8abd

[INFO] Waiting for 5 confirmations for tx 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2dbeba8b41ed984a8abd on chain 11124
[INFO] 
[abstract-testnet] New Block #16080467
[INFO]     📦 Transactions: 1
[INFO] Transaction 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2dbeba8b41ed984a8abd mined at block 16080467
[INFO] 
[abstract-testnet] New Block #16080468
[INFO]     📦 Transactions: 1
[INFO] 
[abstract-testnet] New Block #16080469
[INFO]     📦 Transactions: 2
[INFO] 
[abstract-testnet] New Block #16080470
[INFO]     📦 Transactions: 1
[INFO] 
[abstract-testnet] New Block #16080471
[INFO]     📦 Transactions: 1
[INFO] Transaction 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2dbeba8b41ed984a8abd confirmed with 5 confirmations
[INFO] 
========================================
[INFO] 🎉 CONFIRMATION EVENT RECEIVED!
[INFO] ========================================
[INFO] Chain ID: 11124
[INFO] Transaction Hash: 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2dbeba8b41ed984a8abd
[INFO] Block Number: 16080467
[INFO] Confirmations: 5
[INFO] Timestamp: 2025-12-18T05:24:03.869Z
[INFO] ========================================

[INFO] 💰 User can now be credited for transaction: 0x0f5cebbb116fb813dafe42cafb9ebc959822c6c2ecde2dbeba8b41ed984a8abd


