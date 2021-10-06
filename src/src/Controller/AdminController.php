<?php declare(strict_types=1);


namespace App\Controller;


use App\Entity\SwShop;
use App\Repository\SwShopRepository;
use App\Shopware\AuthenticationHelper;
use MeiliSearch\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends AbstractController
{
    public const SESSION_INDEX_SELECTION = 'adminConfigIndexSelection';

    /**
     * @Route("/admin/startSession", name="admin.start-session")
     */
    public function startSession(Request $request, SwShopRepository $shopRepository): Response
    {
        $shopId = $request->get('shop-id');
        $shop = $shopRepository->findOneBy(['id' => $shopId]);

        if (!$shop || !AuthenticationHelper::authenticateGet($shop)) {
            return new Response(null, 401);
        }

        return $this->redirectToRoute('admin.index');
    }

    /**
     * @Route("/admin/index", name="admin.index")
     */
    public function index(): Response
    {
        /** @var ?SwShop $shop */
        $shop = $this->getUser();

        if (!$shop) {
            return new Response(null, 401);
        }

        return $this->render('admin/index.html.twig', [
            'shop' => $shop,
        ]);
    }
}