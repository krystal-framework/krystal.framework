Cookies

=======

There's a built-in service called `CookieBag`, you can use to work with cookies. It's a part of request service, so you can access it by calling `getCookieBag()` on it. As a best practice you should only work with cookies in controllers only, since cookies are part of the HTTP request. As an example, it might look like this:

    public function someAction()
    {
       $cookieBag = $this->request->getCookieBag();
       
       if ($cookieBag->exist('foo')) {
           $foo = $cookieBag->get('foo');
           // ....
       }
    }

## Available methods

### isEmpty()

    \Krystal\Http\CookieBag::isEmpty()

Checks whether there's at least one cookie has been set on client's machine. Return boolean.

### removeAll()

    \Krystal\Http\CookieBag::removeAll()

Removes all available cookies.

### getAll()

    \Krystal\Http\CookieBag::getAll()

Returns all available cookies.

### set()

    \Krystal\Http\CookieBag::set($key, $value, $ttl = 0, $path = '/', $secure = false, $httpOnly = false, $raw = false)

Sets a new cookie. Its arguments are self-explanatory. The only one note, if `$ttl` is 0, that means that the cookie will be removed automatically, when user close a browser.

### get()

    \Krystal\Http\CookieBag::get($key)

Returns cookie value by its associated key. If the target key doesn't exist, then `RuntimeException` will be thrown.

### remove()

    \Krystal\Http\CookieBag::remove($key)

Removes a cookie by its associated key. Returns `true` if removed successfully, `false` if tried to remove non-existing cookie.

### has()

    \Krystal\Http\CookieBag::has($key)

Checks whether cookie exist.
