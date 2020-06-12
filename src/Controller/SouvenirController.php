<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\Photo;
use App\Entity\Souvenir;
use App\Form\SouvenirType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SouvenirController extends AbstractController
{
    /**
     * @Route("/booking/{id}/souvenir", name="souvenir")
     * @param Booking $booking
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Booking $booking, Request $request, EntityManagerInterface $manager)
    {
        $souvenir = new Souvenir();


        /*$photo = new Photo();
        $photo->setLegende("fake legende")
        ->setFilename("C:\Users\alecl\OneDrive\Pictures\maxresdefault.jpg");
        $souvenir->addPhoto($photo);*/

        $form = $this->createForm(SouvenirType::class, $souvenir);
        $form->handleRequest($request);

        dump($souvenir);
        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($souvenir->getPhotos() as $photo) {
                $photo->setSouvenirId($souvenir);
                $manager->persist($photo);
            }

            $souvenir->setBooking($booking);
            $manager->persist($souvenir);
            $manager->flush();
            $this->addFlash(
                'success',
                "Le souvenir a été enregistré"
            );
            return $this->redirectToRoute('souvenir',
                [
                    'id' => $booking->getId(),
                ]
            );
        }

        return $this->render('souvenir/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }
}
