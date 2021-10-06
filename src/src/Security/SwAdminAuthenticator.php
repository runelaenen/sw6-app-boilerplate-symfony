<?php

namespace App\Security;

use App\Repository\SwShopRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

class SwAdminAuthenticator extends AbstractAuthenticator
{
    private SwShopRepository $shopRepository;

    private RouterInterface $router;

    public function __construct(
        SwShopRepository $shopRepository,
        RouterInterface $router
    )
    {
        $this->shopRepository = $shopRepository;
        $this->router = $router;
    }

    public function supports(Request $request): ?bool
    {
        return 'admin.start-session' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): PassportInterface
    {
        return new SwAdminPassport($request->get('shop-id'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return new RedirectResponse($this->router->generate('admin.index'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response('Something went wrong. Try re-activating your plugin.');
    }

    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
    {
        if (!$passport instanceof SwAdminPassport) {
            throw new \Exception();
        }

        $shop = $this->shopRepository->findOneBy(['id' => $passport->getShopId()]);
        if (!$shop) {
            throw new \Exception();
        }

        if (!AuthenticationHelper::authenticateGet($shop)) {
            throw new \Exception();
        }

        return new PostAuthenticationToken($shop, $firewallName, ['ROLE_USER']);
    }
}
