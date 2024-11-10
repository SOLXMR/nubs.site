function copyToClipboard() {
    const contractAddress = document.getElementById("contractAddress");
    contractAddress.select();
    contractAddress.setSelectionRange(0, 99999);
    document.execCommand("copy");
    alert("Contract address copied!");
}

window.addEventListener("load", function() {
    const backgroundMusic = document.getElementById("backgroundMusic");
    backgroundMusic.play();
});
