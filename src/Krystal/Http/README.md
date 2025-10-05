HTTP Component
==============

This component provides an abstraction over HTTP. It has several services that simplify sending and receiving data via HTTP.

# Request

    \Krystal\Http\Request

This service is available in controllers as `request` property. 

## Available methods

## isIntended()

    \Krystal\Http\Request::isIntended()

Tells whether request has been made either via POST or GET request. Returns boolean value. It's called `isIntended()` because most web application only do use POST and GET requests.

## getMetaCsrfToken()

    \Krystal\Http\Request::getMetaCsrfToken()

Returns CSRF token if present in headers. If not, then `null` is returned.

## hasMetaCsrfToken()

    \Krystal\Http\Request::hasMetaCsrfToken()

Determines whether CSRF token is present in request headers. Returns boolean.

## getCookieBag()

    \Krystal\Http\Request::getCookieBag()

Returns an instance of `CookieBag`. It has its own dedicated documentation below.

## getAll()

    \Krystal\Http\Request::getAll($separate = true)

Returns all request data, including files if they present. If its `$separate` argument is true, then it will create two standalone keys; one for data, another for files. Otherwise it will merge request and files.

## buildQuery()

    \Krystal\Http\Request::buildQuery(array $params, $includeCurrent = true)

Builds a raw query string. The first `$params` argument is a pair itself. And the second `$includeCurrent` defines whether to merge current query data with provided params or not.

## getFiles()

    \Krystal\Http\Request::getFiles($name = null)

Returns an array of `FileEnity` objects. Each file entity object has the following methods, that provide basic data about the uploaded file itself:

`getUniqueName()` - return unique file name.
`getType()` - returns guessed Mime-Type.
`getName()` - returns a base name.
`getTmpName()` - returns a path to a temporary uploaded file.
`getError()` - returns error code
`getSize()` - returns file size in bytes

If the first argument is `null`, then it returns all files that have been sent by a user. If you want to filter by specific field name, you can provide that name as the argument.

## hasFiles()

    \Krystal\Http\Request::hasFiles($name = null)

Determines whether at least one file has been sent by a user. If `$name` isn't `null` then it will reduce checking to particular input file by its name.

## serialize()

    \Krystal\Http\Request::serialize($data)

Builds raw query string based on pairs

## unserialize()

    \Krystal\Http\Request::unserialize($string)

Turns back serialized string into an array.

## getCurrentURL()

    \Krystal\Http\Request::getCurrentURL()

Returns current URL.

## getClientIP()

    \Krystal\Http\Request::getClientIP($proxy = false)

Returns IP of the current user. The first `$proxy` argument tells whether to rely on proxy or not.

## getRootDir()

    \Krystal\Http\Request::getRootDir()

Returns root directory path.

## getHost()

    \Krystal\Http\Request::getHost()

Returns current host name.

## getServerIP()

    \Krystal\Http\Request::getServerIP()

Returns server's IP current script runs on.

## getMethod()

    \Krystal\Http\Request::getMethod()

Returns method type the request has been made with.

## getSubdomains()

    \Krystal\Http\Request::getSubdomains()

Returns current sub-domains, if present

## getDomain()

    \Krystal\Http\Request::getDomain()

Returns current domain

## getLanguages()

    \Krystal\Http\Request::getLanguages()

Returns all languages supported by a browser.

## getLanguage()

    \Krystal\Http\Request::getLanguage()

Returns default browser's language.

## getTimestamp()

    \Krystal\Http\Request::getTimestamp()

Returns UNIX timestamp when the request has been made.

## getServerPort()

    \Krystal\Http\Request::getServerPort()

Returns server post, the script runs on.

## getBaseUrl()

    \Krystal\Http\Request::getBaseUrl()

Returns base URL.

## getRemotePort()

    \Krystal\Http\Request::getRemotePort()

Returns server port.

## isRedirected()

    \Krystal\Http\Request::isRedirected()

Determines whether request has been redirected internally. You can use this method to determine whether is has been redirected by `mod_rewrite` or not.

## getScriptLocation()

    \Krystal\Http\Request::getScriptLocation()

Returns the path to the current script.

## isSecure()

    \Krystal\Http\Request::isSecure()

Returns boolean value, that indicates whether request has been done via secure protocol (i.e via HTTPS).

## getScheme()

    \Krystal\Http\Request::getScheme()

Returns current protocol scheme. Can be either HTTP or HTTPS.

## getURI()

    \Krystal\Http\Request::getURI()

Returns URI from the query.

## isXHR()

    \Krystal\Http\Request::isXHR()

Determines whether request has been done via AJAX. Returns boolean.

## isFlash()

    \Krystal\Http\Request::isFlash()

Determines whether request has been done from flash script. Returns boolean.

## isCLI()

    \Krystal\Http\Request::isCLI()

Determines whether request has been done from a command line.

## hasQueryVals()

    \Krystal\Http\Request::hasQueryVals($filter = null)

Determines whether query string contains at least one non-empty value. Optionally can be filtered by a group, in case query string has nested arrays.

## hasQueryNs()

    \Krystal\Http\Request::hasQueryNs($ns, $key)

Determines whether query's nested array, has a particular key. The first argument is a name of an array, and the second `$key` is a name of the target key. Returns boolean.

## getQueryNs()

    \Krystal\Http\Request::getQueryNs($ns, $key, $default)

Returns a value from a nested query's array. The first argument is a name of nested array, the second is a target key, and the third a default value to be returned in case a target key doesn't exist.

## getWithNsQuery()

    \Krystal\Http\Request::getWithNsQuery($ns, $data, $mark = true)

Returns merged data with current query data. The first `$ns` argument is a name of nested query array, the second `$data` is a custom array, and the third optional `$mark` defines whether to append questing mark to the target result. Returns a query string.

## getWithQuery()

    \Krystal\Http\Request::getWithQuery($data, $mark = true)

Just like as previous `getWithNsQuery()` does the same, but without assumption about nested array.

## hasQuery()

    \Krystal\Http\Request::hasQuery()

Determines whether query parameter exists, This method is variadic. That means you can supply as many argument as you need, like `hasQuery('foo', 'bar', 'bar')`. If no arguments supplied, then it would check the whole query data.

## hasPost()

    \Krystal\Http\Request::hasPost()

Determines whether POST parameter exists. This method is variadic as well. That means you can supply as many argument as you need, like `hasPost('foo', 'bar', 'bar')`. If no arguments supplied, then it would check the whole POST data.

## getPost()

    \Krystal\Http\Request::getPost($key, $default = null)

Returns a value from POST request associated with a target key. If no arguments provided, then the whole POST array is returned.

## getQuery()

    \Krystal\Http\Request::getQuery($key, $default = null)

Returns a value from GET request associated a target key. If no arguments provided, then the whole GET array is returned.

## isAjaxPost()

    \Krystal\Http\Request::isAjaxPost()

Determines whether request has been made via POST and has AJAX headers. Return boolean.

## isAjaxGet()

    \Krystal\Http\Request::isAjaxGet()

Determines whether request has been made via GET and has AJAX headers. Return boolean.

## isAjax()

    \Krystal\Http\Request::isAjax()

That's just an alias to `isXhr()`.

## Request method detectors

Here's a list of methods, that detect the type of the request:

    isPost()
    isGet()
    isOptions()
    isPropFind()
    isHead()
    isPut()
    isDelete()
    isTrace()
    isConnect()
    isPatch()
    
As you might already guessed, they all return boolean value.


# Working with cookies

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

# HTTP response

HTTP response is represented by `response` service, which is available as a property in controllers. You can use this service, if you want to controll the way of response generation.

It's used like this:

    public function someAction()
    {
        // .... do something here and finally
        $this->response->redirect('http://example.com');
    }

Now, let's take a look at available methods:


## download()

    \Krystal\Http\Response\HttpResponse::download($file, $alias = null)

Send a file back to user for downloading. The first `$file` argument is a path to be file to be sent, and the second `$alias` is an optional alias name, which overrides a base name of selected file.

## setStatusCode()

    \Krystal\Http\Response\HttpResponse::setStatusCode($code)

Sets HTTP status code.

## redirect()

    \Krystal\Http\Response\HttpResponse::redirect($url)

Redirects to another URL.

## enableCache()

     \Krystal\Http\Response\HttpResponse::enableCache($timestamp, $ttl)

Enables HTTP cache. The first `$filestamp` must be a timestamp of latest modification, and the second `$ttl` defines a lifetime in seconds.

## disableCache()

Completely disables HTTP cache.

## setContentType()

    \Krystal\Http\Response\HttpResponse::setContentType($type, $charset)

Defines content type with its preferred charset.

## setSignature()

    \Krystal\Http\Response\HttpResponse::setSignature($signature)

Sets `HTTP X-Powered-By` signature.



# HTTP Clients

HTTP clients would help you to make request to third-party URLs. Typically, you'd need that when working with third-party APIs in *REST* style.

## cURL

    \Krystal\Http\Client\Curl

That's an adapter for cURL extension. Its usage almost the same as if you were using procedural `curl_*` functions, but instead you'd use a service object. 

## Available methods

### init()

    \Krystal\Http\Client\Curl::init(array $options = array())

Initializes the cURL. Optionally you can pass an array of options. You can learn more about options on [official page](http://php.net/manual/en/function.curl-setopt.php)

### setOptions()

    \Krystal\Http\Client\Curl::setOptions(array $options = array())

Sets cURL options. That can be useful if you want to set options after class instantiation.

### close()

    \Krystal\Http\Client\Curl::close()

Closes the connection.

### clone()

    \Krystal\Http\Client\Curl::clone()

Returns a cloned instance of current cURL instance.

### getErrors()

    \Krystal\Http\Client\Curl::getErrors()

Returns an array of errors, if any.

### exec()

    \Krystal\Http\Client\Curl::exec()

Performs a session request.

### getVersion()

    \Krystal\Http\Client\Curl::getVersion()

Returns current cURL extension version.


### Example

Usage is pretty simple:

    <?php
    
    use Krystal\Http\Client\Curl;
    
    $curl = new Curl(array(.....));
    $curl->init();
    
    echo $curl->exec();
