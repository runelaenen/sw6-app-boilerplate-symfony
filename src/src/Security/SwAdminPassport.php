<?php declare(strict_types=1);

namespace App\Security;

use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportTrait;

class SwAdminPassport implements PassportInterface
{
    use PassportTrait;

    private string $shopId;

    public function __construct(
        string $shopId
    ) {
        $this->shopId = $shopId;
    }

    public function getShopId(): string
    {
        return $this->shopId;
    }

    public function setShopId(string $shopId): void
    {
        $this->shopId = $shopId;
    }
}