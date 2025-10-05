
Authentication
==============

Authentication component provides a service to manage authentication mechanism on your site. It's meant for controller actions only, so in order to activate it, you have to inherit `\Krystal\Application\Controller\AbstractAuthAwareController` rather than default `\Krystal\Application\Controller\AbstractController`. Usually you would want to make all controllers protected that belong to administration area of your site.

Once you inherit `\Krystal\Application\Controller\AbstractAuthAwareController` controller, you have to implement 4 protected methods in a descendant:

## getAuthService()

Must return a service (we'll discuss it below)

## onSuccess()

This method will be invoked automatically, once user enters valid credentials.

## onFailure()

This method will be invoked automatically if not-logged-in user tried to access protected area (i.e protected controller actions).

## onNoRights()

This method will be invoked if when user tried to access an area he has no rights to.

# Writing the service

The service must implement `\Krystal\Authentication\UserAuthServiceInterface`:

## getId()

Returns an id from storage.

## getRole()

Returns a role from storage.

## authenticate($login, $password, $remember, $hash = true)

Performs an authentication. When implementing this method you would query a database against provided data.

## logout()

Logouts a user.

## isLoggedIn()

Determines whether a user is already logged in.

# RBAC

As soon as you define roles, you would want to protect some controller actions. Suppose you have a route that points to controller action which adds something to a database. In that case, you might want to allow all users that belong to "admin" group perform that action, and do forbid for the rest roles.

Well, by default the RBAC protector allows everyone to perform actions. In order to disallow a particular group to perform desired controller action, you have to add `disallow` with associated route definition. That array must contain forbidden role names.

For example:

'/admin/some-secured-action' => array(
	'controller' => 'SomeController@someAction',
	'disallow' => array('reviewer')
)

So if a user with his credentials logged as "reviewer" and navigated to `/admin/some-secured-action` then he won't be allowed to do that and the inherited method `onNoRights()` will be called automatically.