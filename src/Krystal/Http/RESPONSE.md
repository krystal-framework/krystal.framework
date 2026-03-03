HTTP response
===========

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