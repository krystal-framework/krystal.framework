HTTP Client
===========

The Krystal HTTP Client is a robust, PHP 5.6+ compatible HTTP client built on top of cURL. It provides a clean, object-oriented interface for making HTTP requests with comprehensive error handling and configuration options.

## Features

- Full HTTP Method Support: GET, POST, PUT, PATCH, DELETE, HEAD
- PHP 5.6 to 8+ Compatibility: Works across all supported PHP versions
- Thread-Safe: Multiple instances can be used concurrently
- Exception-Based Error Handling: Clear error messages for debugging
- Configurable Defaults: Set once, use everywhere

## Method signatures

    method(string $url, array $data = [], array $extra = [])

- `$url` (string): The target URL
- `$data` (array): Data to send (query params for GET/HEAD, body for others)
- `$extra` (array): Additional cURL options (`CURLOPT_*` constants as keys)


## Usage

    <?php
    
    use Krystal\Http\Client\HttpClient;
    use RuntimeException;
    
    $client = new HttpClient();
    
    try {
        // Simple GET request
        $response = $client->get('https://api.example.com/users');
        
        // GET with query parameters
        $response = $client->get('https://api.example.com/users', [
            'page' => 1,
            'limit' => 20
        ]);
        
        // POST request with form data
        $response = $client->post('https://api.example.com/login', [
            'username' => 'john',
            'password' => 'secret'
        ]);
        
        // PUT request
        $response = $client->put('https://api.example.com/users/1', [
            'name' => 'John Updated',
            'email' => 'john@example.com'
        ]);
        
        // PATCH request
        $response = $client->patch('https://api.example.com/users/1', [
            'name' => 'John Modified'
        ]);
        
        // DELETE request
        $response = $client->delete('https://api.example.com/users/1');
        
        // HEAD request (returns headers only)
        $headers = $client->head('https://api.example.com/users');
    
        // Generic request method
        $response = $client->request('POST', 'https://api.example.com/users', $data);
        
    } catch (RuntimeException $e) {
        echo 'Request failed: ' . $e->getMessage();
    }

## Custom Headers

Add custom HTTP headers to your requests:

    $response = $client->get('https://api.example.com/protected', [], [
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer token123',
            'X-Custom-Header: value'
        ]
    ]);

## JSON Requests

Send and receive JSON data:

    $response = $client->post('https://api.example.com/users', [], [
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'name' => 'Jane',
            'email' => 'jane@example.com'
        ])
    ]);

## File Uploads

Upload files using multipart/form-data:

    $response = $client->post('https://api.example.com/upload', [], [
        CURLOPT_POSTFIELDS => [
            'file' => new CURLFile('/path/to/file.jpg', 'image/jpeg', 'photo.jpg'),
            'description' => 'My photo'
        ]
    ]);

## Concurrent HTTP Requests

`CurlMulti` is a wrapper around PHP's `curl_multi_*` functions that provides a clean, object-oriented interface for concurrent HTTP requests. It manages multiple `Curl` instances and executes them in parallel.

**Important**: `CurlMulti` works with `Curl` instances directly, not `HttpClient` instances. You'll need to use the low-level `Curl` class. 

Basic example:    
       
    <?php
    
    use Krystal\Http\Client\Curl;
    use Krystal\Http\Client\CurlMulti;
    use RuntimeException;
    
    // Create individual Curl instances
    $curl1 = new Curl();
    $curl2 = new Curl();
    $curl3 = new Curl();
    
    // Configure each Curl instance
    $curl1->setOption(CURLOPT_URL, 'https://api.example.com/users/1');
    $curl1->setOption(CURLOPT_RETURNTRANSFER, true);
    
    $curl2->setOption(CURLOPT_URL, 'https://api.example.com/users/2');
    $curl2->setOption(CURLOPT_RETURNTRANSFER, true);
    
    $curl3->setOption(CURLOPT_URL, 'https://api.example.com/users/3');
    $curl3->setOption(CURLOPT_RETURNTRANSFER, true);
    
    // Create multi-handle
    $multi = new CurlMulti();
    
    try {
        // Add all Curl instances to multi-handle
        $multi->add($curl1);
        $multi->add($curl2);
        $multi->add($curl3);
        
        // Execute all requests concurrently
        $results = $multi->exec();
        
        // Process results
        foreach ($results as $id => $result) {
            echo "Result $id:\n";
            echo "Content: " . $result['result'] . "\n";
            echo "HTTP Code: " . ($result['info']['http_code'] ?? 'N/A') . "\n";
            if ($result['errno']) {
                echo "Error: " . $result['error'] . "\n";
            }
        }
        
    } catch (RuntimeException $e) {
        echo 'Multi-request failed: ' . $e->getMessage();
    } finally {
        // Clean up
        $curl1->close();
        $curl2->close();
        $curl3->close();
    }
