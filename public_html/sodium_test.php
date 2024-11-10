<?php
if (function_exists('sodium_crypto_sign_verify_detached')) {
    echo "Sodium library is available";
} else {
    echo "Sodium library is not available";
}
?>
