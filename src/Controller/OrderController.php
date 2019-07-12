<?php


namespace App\Controller;

use App\Entity\Ordre;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @Route("/admin")
 */
class OrderController extends AdminController
{
    /**
     * @Route("/order_list", name="orderList")
     */
    public function orderList()
    {
        $orders = $this->getDoctrine()->getRepository(Ordre::class)->findAll();

        return $this->render('order/orderList.html.twig',[
            'orders'=> $orders
        ]);
    }

    /**
     * @Route("/order_view/{codiOrdre}", name="orderView", requirements={"codiOrdre"="\d+"}, methods={"GET"})
     * @ParamConverter("order", class="App:Ordre")
     */
    public function orderView($order)
    {
        return $this->render('order/orderView.html.twig', [
            'order' => $order
        ]);
    }

    /**
     * @Route("order_add", name="orderAdd")
     */
    public function orderAdd(Request $request, EntityManagerInterface $em)
    {

    }

    /**
     * @Route("/order_edit/{codiOrdre}", name="orderEdit", requirements={"codiOrdre"="\d+"})
     */
    public function orderEdit(Request $request, EntityManagerInterface $em)
    {

    }

    /**
     * @Route("/order_delete/{codiOrdre"), name="orderDelete", requirements={"codiOrdre"="\d+"}
     */
    public function orderDelete(Request $request, EntityManagerInterface $em, Ordre $ordre)
    {

    }
}