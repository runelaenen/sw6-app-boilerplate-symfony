<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\SwShop;
use Shopware\Core\Framework\App\Hmac\Guzzle\AuthMiddleware;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationHelper
{
    public static function authenticatePost(Request $request, SwShop $shop): bool
    {
        return hash_equals(
            \hash_hmac('sha256', $request->getContent(), $shop->getSecret()),
            $request->headers->get('shopware-shop-signature')
        );
    }

    public static function authenticateGet(SwShop $shop): bool
    {
        $queryString = $_SERVER['QUERY_STRING'];

        $queries = [];

        parse_str($queryString, $queries);

        $queryString = sprintf(
            'shop-id=%s&shop-url=%s&timestamp=%s&sw-version=%s&sw-context-language=%s&sw-user-language=%s',
            $shop->getId(),
            $shop->getUrl(),
            $queries['timestamp'],
            $queries['sw-version'],
            $queries['sw-context-language'],
            $queries['sw-user-language']
        );

        $hmac = \hash_hmac('sha256', htmlspecialchars_decode($queryString), $shop->getSecret());

        return hash_equals($hmac, $queries['shopware-shop-signature']);
    }
}