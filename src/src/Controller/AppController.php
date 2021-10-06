<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\SwShop;
use App\Repository\SwShopRepository;
use App\Security\AuthenticationHelper;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class AppController extends AbstractController
{
    private RouterInterface $router;
    private SwShopRepository $shopRepository;

    public function __construct(
        RouterInterface $router,
        SwShopRepository $shopRepository
    ) {
        $this->router = $router;
        $this->shopRepository = $shopRepository;
    }

    /**
     * @Route("/app/registration", name="app.registration")
     */
    public function registration(Request $request): Response
    {
        $appName = $_SERVER['SW_APP_NAME'];
        $appSecret = $_SERVER['SW_APP_SECRET'];

        $hmac = \hash_hmac('sha256', htmlspecialchars_decode($request->server->get('QUERY_STRING')), $appSecret);

        if (!hash_equals($hmac, $request->headers->get('shopware-app-signature'))) {
            return new JsonResponse(['error' => 'Registration failed! Invalid signature.']);
        }

        $proof = \hash_hmac(
            'sha256',
            $request->get('shop-id') . $request->get('shop-url') . $appName,
            $appSecret);

        if (!$shop = $this->shopRepository->find($request->get('shop-id'))) {
            $shop = new SwShop();
        }

        $shop->setId($request->get('shop-id'));
        $shop->setUrl($request->get('shop-url'));
        $shop->setSecret(Uuid::uuid4()->toString());

        $em = $this->getDoctrine()->getManager();
        $em->persist($shop);
        $em->flush();

        return new JsonResponse([
            'proof' => $proof,
            'secret' => $shop->getSecret(),
            'confirmation_url' => $this->router->generate('app.confirm', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @Route("/app/confirm", name="app.confirm")
     */
    public function confirm(Request $request, SwShopRepository $shopRepository): Response
    {
        $data = $request->toArray();
        $shopId = $data['shopId'];
        $shop = $shopRepository->findOneBy(['id' => $shopId]);

        if (!$shop || !AuthenticationHelper::authenticatePost($request, $shop)) {
            return new Response(null, 401);
        }

        $shop->setApiKey($data['apiKey']);
        $shop->setApiSecret($data['secretKey']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($shop);
        $em->flush();

        return new Response();
    }
}