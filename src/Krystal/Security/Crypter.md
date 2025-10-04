Crypter
=====

**Crypter** is a lightweight, secure, and easy-to-use tool for encrypting and decrypting strings. Using AES-256-CBC encryption, it allows you to safely protect sensitive data with any secret key or salt, automatically handling key derivation and initialization vectors behind the scenes. 

With simple `encrypt()` and `decrypt()` methods, it's ideal for safeguarding tokens, API keys, or any reversible confidential information - providing strong security without complicating your code.

Usage

    <?php
    
    use Krystal\Security\Crypter;
    
    $crypter = new Crypter('my-secret-salt');
    
    $encrypted = $crypter->encrypt('Hello, world!');
    echo "Encrypted: $encrypted"; // Encrypted: oqZhfrbcsIGcOOF0FGwHiV+nQGPnK9TWcjMjHaJh1tQ=
    
    $decrypted = $crypter->decrypt($encrypted);
    echo "Decrypted: $decrypted"; // Decrypted: Hello, world!
