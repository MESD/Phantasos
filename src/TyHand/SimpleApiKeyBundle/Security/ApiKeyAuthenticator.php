<?php

namespace TyHand\SimpleApiKeyBundle\Security;

use Symfony\Component\Security\Core\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

use TyHand\SimpleApiKeyBundle\Security\ApiKeyUserProvider
use TyHand\SimpleApiKeyBundle\Type\

/**
 * Authenticates an API key
 *   Based on the API key Authenticator in the Symfony cookbook
 */
class ApiKeyAuthenticator implements 
    SimlePreAuthenticatorInterface,
    AuthenticationFailureHandlerInterface
{
    ////////////////
    // PROPERTIES //
    ////////////////

    /**
     * The user provider interface
     * @var ApiKeyUserProvider
     */
    protected $userProvider;

    /**
     * The name of the API key in the request
     * @var string
     */
    protected $keyName;

    /////////////////
    // CONSTRUCTOR //
    /////////////////

    /**
     * Constructor
     * @param ApiKeyUserProvider $userProvider The api key user provider
     */
    public function __construct(ApiKeyUserProvider $userProvider, $keyName)
    {
        $this->userProvider = $userProvider;
        $this->keyName = $keyName;
    }

    /////////////
    // METHODS //
    /////////////

    /**
     * Creates the token
     * @param Request $request     HTTP Request Object
     * @param string  $providerKey Provider key
     */
    public function createToken(Request $request, $providerKey)
    {
        // Get the api key from the request
        $apiKey = $request->query->get($this->keyName);
        if (null === $apiKey) {
            $apiKey = $request->request->get($this->keyName);
        }

        // If the api key was not found, throw an exception
        if (null === $apiKey) {
            throw new BadCredentialsException('No API Key found.');
        }

        // Return the pre authenticated token
        return new PreAuthenticatedToaken('anon.', $apiKey, $providerKey);
    }

    /**
     * Authetnicates a token
     * @param TokenInterface        $token             Token to authenticate
     * @param UserProviderInterface $providerInterface User Provider Interface
     * @param string                $providerKey       Provider key
     */
    public function authenticateToken(
        TokenInterface $token,
        UserProviderInterface $providerInterface,
        $providerKey)
    {
        $apiKey = $token->getCredentials();
        $appName = $this->userProvider->getAppNameForApiKey($apiKey);

        // Check that the app name exists
        if (null === $appName) {
            throw new AuthenticationException(sprintf('API Key for "%s" does not exist.', $apiKey));
        }

        // Load the api user
        $apiUser = $this->userProvider->loadUserByUsername($appName);

        // Return a new token
        return new PreAuthenticatedToken(
            $apiUser, $apiKey, $providerKey, $user->getRoles()
        );
    }

    /**
     * Checks whether this authenticator supports the given token
     * @param TokenInterface $token       Token to check
     * @param string         $providerKey Provider key
     */
    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken
            && $token->providerKey() === $providerKey;
    }

    /**
     * Callback for if authentication fails
     * @param Request                 $request   Request
     * @param AuthenticationException $exception Auth Exception
     * @return Response 403 Response
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception)
    {
        return new Response('Authentication Failed.', 403);
    }
}
