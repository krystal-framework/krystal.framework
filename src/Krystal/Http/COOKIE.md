Cookies
=======

The **CookieBag** service provides a clean, secure interface for reading from `$_COOKIE`, writing new cookies, removing them, and managing encrypted values.

It is typically available in controllers as `$this->request->getCookieBag()`

    public function someAction()
    {
       $cookieBag = $this->request->getCookieBag();
       
       if ($cookieBag->exist('foo')) {
           $foo = $cookieBag->get('foo');
           // ....
       }
    }


## Check if any cookies exist

    isEmpty(): bool

Determine whether the current request contains any cookies.

**Example**

    if ($cookieBag->isEmpty()) {
        // No cookies received in this request
    }

## Get all cookies

    getAll(): array

Retrieve the full array of cookies from the current request.

**Example**

    $cookies = $cookieBag->getAll();
    print_r($cookies);

## Check if a cookie exists

    has(string $key): bool

Verify whether a specific cookie key is present in the current request.

**Example**

    if ($cookieBag->has('theme')) {
        // Theme preference is set
    }

## Check multiple cookies exist

    hasMany(array $keys): bool

Verify that all specified cookie keys are present.

**Example**

    if ($cookieBag->hasMany(['user_id', 'session_token'])) {
        // Proceed with authenticated logic
    }

## Set a cookie

    set(string $key, string $value, int $ttl = TimeHelper::YEAR, string $path = '/', bool $secure = false, bool $httpOnly = false, bool $raw = false): bool

Set or update a cookie. The value becomes immediately available in the current request.

    $this->cookieBag->set(
        'theme',
        'dark',
        86000,
        '/',
        true,  // secure (HTTPS only)
        true,  // httpOnly
        false  // not raw
    );

Important notes

- `$key` cannot contain dots (throws `UnexpectedValueException`)
- `$value` and `$key` must be scalar types
- Domain is automatically parsed from current host (supports subdomain sharing)
- Returns true if `setcookie()` ran successfully (does not guarantee client acceptance)


## Set an encrypted cookie

    setEncrypted(string $key, string $value, int $ttl = TimeHelper::YEAR, string $path = '/', bool $secure = false, bool $httpOnly = false, bool $raw = false): bool

Set a cookie with its value encrypted using the crypter.

**Example**

    $cookieBag->setEncrypted('token', $token, 8600);

## Get a cookie value

    get(string $key): string

Retrieve the value of a cookie by its key. Throws `RuntimeException` if the key does not exist.

**Example**

    $theme = $cookieBag->get('theme'); // e.g. 'dark'

Note

Use `has()` first to avoid exceptions if the key may be missing.

## Get encrypted cookie value

    getEncrypted(string $key): string

Retrieve and decrypt the value of an encrypted cookie.

**Example**

    $token = $cookieBag->getEncrypted('token');


## Get or set cookie once (lazy initialization)

    getOnce(string $key, Closure $callback): mixed

Retrieve a cookie value, or compute and set it once if missing (useful for visitor IDs, preferences).

**Example**

    $visitorId = $cookieBag->getOnce('visitor_id', function () {
        return uniqid('vis_', true);
    });

## Remove a single cookie

    remove(string $key): bool

Delete a cookie by expiring it in the past and removing it from the current request.

**Example**

    $cookieBag->remove('session_token');

Note

Returns true if the cookie existed and was removed.

## Remove all cookies

    removeAll(): bool

Expire and remove all cookies from the client.

**Example**

    $cookieBag->removeAll(); // Clear all cookies on logout