Overview
=====

A **JWT (JSON Web Token)** is a compact, URL-safe way of securely transmitting information between a client and a server as a JSON object.  

It is commonly used for **authentication** and **authorization** in modern web applications and APIs.

## Advantages

-   **Stateless** – no session storage required on the server.
-   **Compact** – easy to pass in HTTP headers, cookies, or query strings.

# Usage

This implementation provides two main methods:

-   **`encode(array $payload, int $exp)`** → Encode and generate a token.
    
-   **`decode(string $jwt)`** / **`decodeFromBearer()`** → Decode and verify a token.
    

----------

## Step 1: Create a token (Server-side)

Once the user is authenticated, you can generate a token that stores your data in the `$payload` array.  

This token can then be sent to your frontend (e.g., JavaScript) for later use.

    <?php
    
    namespace Krystal\Authentication\JWT;
    
    $secret = 'my-secret'; // Your unique secret key (keep it in configuration)
    $ttl = 3600;           // Time-to-live in seconds
    
    $payload = [
        'sub' => '1',       // User ID
        // Any other custom data (e.g. role, email, permissions)
    ];
    
    $jwt = new JWT($secret);
    
    // Generate a token and send it to JavaScript
    echo $jwt->create($payload, $ttl);

----------

## Step 2: Decode and verify a Token (Server-side)

On subsequent requests, you can verify the token either from a query parameter or from the **Authorization Bearer header**.

    <?php
    
    namespace Krystal\Authentication\JWT;
    
    $secret = 'my-secret';
    $jwt = new JWT($secret);
    
    // Option 1: Token from query string (?token=...)
    $payload = $jwt->decode($_GET['token']);
    
    // Option 2: Token from Authorization header (Bearer <token>)
    $payload = $jwt->decodeFromBearer();
    
    if ($payload) {
        // Token is valid and not expired - proceed with request
    } else {
        // Invalid or expired token
    }

----------

## Step 3: JavaScript Client Example

On the frontend, you authenticate by sending login credentials to the server.  

If successful, the server returns a **JWT** which you can store and send with each request.

    // Login and receive JWT
    async function login() {
      const response = await fetch("/auth/login", {
        method: "POST",
        headers: { 
         "Content-Type": "application/x-www-form-urlencoded" 
        },
        body: new URLSearchParams({ username: "alice", password: "password123" })
      });
    
      const data = await response.json();
    
      if (data.success) {
        console.log("Token received:", data.token);
    
        // Save token (for demo purposes, use HttpOnly cookie in production)
        localStorage.setItem("jwt", data.token);
    
        // Call a protected route
        getProfile();
      } else {
        console.error("Login failed:", data.message);
      }
    }
    
    // Example protected request
    async function getProfile() {
      const token = localStorage.getItem("jwt");
    
      const response = await fetch("/auth/profile", {
        method: "GET",
        headers: {
          "Authorization": "Bearer " + token
        }
      });
    
      const data = await response.json();
      console.log("Profile:", data);
    }
    
    // Start login flow
    login();

----------

## Flow Summary

1.  **Client sends login credentials** → `/auth/login`.
2.  **Server verifies credentials** → issues a JWT.
3.  **Client stores the token** (localStorage, sessionStorage, or HttpOnly cookie).
4.  **Client sends the token** with every request in `Authorization: Bearer <token>`.    
5.  **Server decodes and validates** the token before serving protected resources.
