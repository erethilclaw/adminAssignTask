<?php

namespace App\Controller;

use App\Entity\Butlleti;
use App\Entity\LiniaButlleti;
use App\Entity\User;
use App\Form\ButlletiFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/butlleti")
 */
class ButlletiController extends AbstractController
{
    /**
     * @Route("/list", name="butlletiList")
     */
    public function butlletiList()
    {
        $user = $this->getUser();

        $buttlletins = $this->getDoctrine()->getRepository(Butlleti::class)->findBy(
            ['user' => $user->getId()]
        );

        return $this->render('butlleti/butlletiList.html.twig', [
            'butlletins' => $buttlletins ,
        ]);
    }
    /**
     * @Route("/{id}", name="butlletiView", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("butlleti", class="App:Butlleti")
     */
    public function butlletiView($butlleti)
    {
        return $this->render('butlleti/butlletiView.html.twig', [
            'butlleti' => $butlleti
        ]);
    }
    /**
     * @Route("/butlleti_add", name="butlletiAdd")
     */
    public function butlletiAdd(Request $request, EntityManagerInterface $em)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $butlleti = new Butlleti();
        $form = $this->createForm(ButlletiFormType::class, $butlleti, ['user'=> $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $butlletiOld = $this->getDoctrine()->getRepository(Butlleti::class)->findOneBy([
                'dataButlleti' => $form->get('dataButlleti')->getData(),
                'user' => $user
            ]);

            if ($butlletiOld){
                $this->addFlash('success', 'Butlleti ja estaba creat!');
                return $this->redirectToRoute('butlletiEdit', array('id' => $butlletiOld->getId()));
            }

            $this->addFlash('success', 'Butlleti creat!');
            /**
             * @var Butlleti $butlleti
             */
            $butlleti = $form->getData();
            $today = new \DateTime();
            $butlleti->setCreat($today);

            $butlleti->setUser($user);

            foreach ($butlleti->getLiniesButlleti() as $lineaButlleti){
                $this->newLinieaButlletiSetData($lineaButlleti, $user, $today);
            }

            $em->persist($butlleti);
            $em->flush();

            return $this->redirectToRoute('butlletiView', array('id' => $butlleti->getId()));
        }

        return $this->render('butlleti/butlletiForm.html.twig', [
            'butlletiForm' => $form->createView(),
            'actionText'=> 'Crear Butlleti',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="butlletiEdit")
     */
    public function butlletiEdit(Request $request, EntityManagerInterface $em, Butlleti $butlleti)
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $form = $this->createForm(ButlletiFormType::class, $butlleti, ['user'=> $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $user->getId()== $butlleti->getUser()->getId()) {
            $this->addFlash('success', 'Butlleti modificat!');
            /**
             * @var Butlleti $butlleti
             */
            $butlleti = $form->getData();
            $today = new \DateTime();
            $butlleti->setCreat($today);

            foreach ($butlleti->getLiniesButlleti() as $lineaButlleti){
                if (!$lineaButlleti->getUser()){
                    $this->newLinieaButlletiSetData($lineaButlleti, $user, $today);
                } else {
                    $lineaButlleti->setModificat($today);
                }
            }

            $em->persist($butlleti);
            $em->flush();

            return $this->redirectToRoute('butlletiView', array('id' => $butlleti->getId()));
        }

        return $this->render('butlleti/butlletiForm.html.twig', [
            'butlletiForm' => $form->createView(),
            'actionText'=> 'Editar Butlleti',
        ]);
    }

    public function newLinieaButlletiSetData(LiniaButlleti $liniaButlleti ,User $user, $today){
        $liniaButlleti->setCreat($today);
        $liniaButlleti->setUser($user);
    }
}
