<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\SwShop;
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

        if (array_key_exists('sw-context-language', $queries)) {
            // 6.4.5 and higher
            $queryString = sprintf(
                'shop-id=%s&shop-url=%s&timestamp=%s&sw-version=%s&sw-context-language=%s&sw-user-language=%s',
                $shop->getId(),
                $shop->getUrl(),
                $queries['timestamp'],
                $queries['sw-version'],
                $queries['sw-context-language'],
                $queries['sw-user-language']
            );
        } else {
            //lower than 6.4.5
            $queryString = sprintf(
                'shop-id=%s&shop-url=%s&timestamp=%s&sw-version=%s',
                $shop->getId(),
                $shop->getUrl(),
                $queries['timestamp'],
                $queries['sw-version']
            );
        }

        $hmac = \hash_hmac('sha256', htmlspecialchars_decode($queryString), $shop->getSecret());

        return hash_equals($hmac, $queries['shopware-shop-signature']);
    }
}