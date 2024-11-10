document.getElementById("connectWallet").addEventListener("click", async () => {
    try {
        if (window.solana && window.solana.isPhantom) {
            const response = await window.solana.connect();
            const publicKey = response.publicKey.toString();
            document.getElementById("walletAddress").innerText = "Connected: " + publicKey;

            await fetch("/save_wallet.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ publicKey: publicKey })
            });
        } else {
            alert("Phantom Wallet not found. Please install it.");
        }
    } catch (err) {
        console.error("Connection error:", err);
        document.getElementById("statusMessage").innerText = "Connection failed. Please try again.";
    }
});

document.getElementById("donateButton").addEventListener("click", async () => {
    const amount = parseFloat(document.getElementById("amount").value);
    const recipientAddress = "5MpGTPugZBjxFY4FUZ5Uu5Nvr1i8CjRCq25aXH3sqXDe";
    const rpcUrl = 'https://api.mainnet-beta.solana.com';

    try {
        if (!window.solana || !window.solana.isConnected) {
            document.getElementById("statusMessage").innerText = "Please connect your wallet first.";
            return;
        }

        if (amount <= 0) {
            document.getElementById("statusMessage").innerText = "Enter a valid donation amount.";
            return;
        }

        if (!window.solanaWeb3 || !window.solanaWeb3.Connection) {
            document.getElementById("statusMessage").innerText = "Unable to load Solana Web3 components. Please try refreshing the page.";
            console.error("Solana Web3 library is not loaded correctly.");
            return;
        }

        const connection = new window.solanaWeb3.Connection(rpcUrl);

        const transaction = new window.solanaWeb3.Transaction().add(
            window.solanaWeb3.SystemProgram.transfer({
                fromPubkey: window.solana.publicKey,
                toPubkey: new window.solanaWeb3.PublicKey(recipientAddress),
                lamports: amount * window.solanaWeb3.LAMPORTS_PER_SOL,
            })
        );

        const { signature } = await window.solana.signAndSendTransaction(transaction);
        await connection.confirmTransaction(signature);
        document.getElementById("statusMessage").innerText = "Donation successful! Thank you!";
    } catch (err) {
        console.error("Transaction error:", err);
        document.getElementById("statusMessage").innerText = "Transaction failed. Please try again.";
    }
});
