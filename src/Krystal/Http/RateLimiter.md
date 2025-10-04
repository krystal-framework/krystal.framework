Rate limiter
=====

**Rate Limiter** is a mechanism that limits how many requests a user/client can make to a server within a specific time window.

Why it matters:

-   **Prevents abuse** (e.g. brute-force login attempts, scraping, DDoS)
    
-   **Protects APIs** from being overwhelmed
    
-   **Improves fairness** by preventing single users from hogging resources

## Usage example

The rate limiter should only be used within controllers, as they are responsible for handling HTTP requests.

Let's define a dedicated method `requestAllowed()` to determine whether the request limit has been exceeded.

Example:


    <?php
    
    namespace Site\Controller;
    
    use Krystal\Http\RateLimiter
    
    final class Site extends AbstractSiteController
    {
        /**
         * Method to determine whether request allowed
         * 
         * @param string $uniq
         * @return boolean
         */
        protected function requestAllowed($uniq)
        {
            $timeWindow = 10; //The duration (in seconds) during which the request count is accumulated before resetting.
            $limit = 2; // The maximum number of allowed requests within the defined $timeWindow.
    
            $rateLimiter = new RateLimiter($this->sessionBag, $limit, $timeWindow);
    
            // Apply and check rate limit
            $result = $rateLimiter->check($uniq);
    
            if (!$result['allowed']) {
                // Append HTTP response headers
                $this->response->getHeaderBag()
                               ->appendMany($rateLimiter->createExceededHeaders($result));
                return false;
            }
    
            return true;
        }
    
        /**
         * Renders a home page
         * 
         * @return string
         */
        public function indexAction()
        {
            $uniq = $this->request->getClientIP(); // User id or IP or API key to track access
    
            if ($this->requestAllowed($uniq)) {
                return 'Proceed with your application logic';
            } else {
                return 'Rate limit exceeded. Please try again later.';
            }
        }