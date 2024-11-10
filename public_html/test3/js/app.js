// Check for Phantom Wallet Extension
const isPhantomInstalled = window.solana && window.solana.isPhantom;

const connectButton = document.getElementById('connect-button');

// Update the button based on connection status
const updateButton = async () => {
    if (window.solana && window.solana.isConnected) {
        const publicKey = window.solana.publicKey.toString();
        connectButton.textContent = `Connected: ${publicKey.substring(0, 4)}...${publicKey.substring(publicKey.length - 4)}`;
        connectButton.disabled = true;
    } else {
        connectButton.textContent = 'Connect Wallet';
        connectButton.disabled = false;
    }
};

// Fetch and Display Balance
const displayBalance = async () => {
    const connection = new solanaWeb3.Connection(solanaWeb3.clusterApiUrl('mainnet-beta'));
    const publicKey = window.solana.publicKey;
    const balance = await connection.getBalance(publicKey);
    const balanceSOL = balance / solanaWeb3.LAMPORTS_PER_SOL;
    // Display the balance on the page or via alert
    alert(`Your balance is ${balanceSOL} SOL`);
};

// Connect to Phantom Wallet
const connectWallet = async () => {
    try {
        const resp = await window.solana.connect();
        console.log('Connected with Public Key:', resp.publicKey.toString());
        updateButton();
        // Display the user's balance
        displayBalance();
    } catch (err) {
        console.error('Connection failed!', err);
    }
};

// Handle Button Click
connectButton.addEventListener('click', () => {
    if (isPhantomInstalled) {
        connectWallet();
    } else {
        alert('Phantom Wallet is not installed. Please install it from https://phantom.app/');
    }
});

// Auto-connect if already connected
window.addEventListener('load', async () => {
    if (isPhantomInstalled) {
        try {
            await window.solana.connect({ onlyIfTrusted: true });
            updateButton();
            // Optionally display balance if already connected
            displayBalance();
        } catch (err) {
            console.error(err);
        }
    }
});
