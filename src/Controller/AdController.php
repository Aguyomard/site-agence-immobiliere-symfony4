<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Ad;
use App\Form\AdType;
use App\Form\SearchType;
use App\Repository\AdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     * @param AdRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function index(AdRepository $repo, PaginatorInterface $paginator, Request $request)
    {
        //$repo = $this->getDoctrinObjectManager e()->getRepository(Ad::class);

        $data2 = new SearchData();
        $form = $this->createForm(SearchType::class, $data2);
        $form->handleRequest($request);
        // dd($data2);

        $data = $repo->findSearch($data2);
        $ads = $paginator->paginate($data, $request->query->getInt('page', 1), 6);

        if ($request->get('ajax')) {
            return new JsonResponse([
                'content' => $this->renderView('ad/_ads.html.twig', ['ads' => $ads]),
                'sorting' => $this->renderView('ad/_sorting.html.twig', ['ads' => $ads]),
                'pagination' => $this->renderView('ad/_pagination.html.twig', ['ads' => $ads]),
            ]);
        }

        return $this->render('ad/index.html.twig', [
            'ads' => $ads,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ads/new", name="ads_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $ad->setAuthor($this->getUser());
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a été enregistrée"
            );
            return $this->redirectToRoute('ads_show',
                ['slug' => $ad->getSlug()]
            );
        }

        return $this->render('ad/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/ads/{slug}", name="ads_show")
     * @param Ad $ad
     * @return Response
     */
    public function show(Ad $ad)
    {
        //$ad = $repo->findOneBySlug($slug);
        return $this->render('ad/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @param Ad $ad
     * @param Request $request
     * @param ObjectManager $manager
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }
            $manager->persist($ad);
            $manager->flush();
            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont été enregistrées"
            );
            return $this->redirectToRoute('ads_show',
                ['slug' => $ad->getSlug()]
            );
        }
        return $this->render('ad/edit.html.twig',
            [
                'form' => $form->createView(),
                'ad' => $ad,
            ]
        );
    }


    /**
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Vous n'avez pas le droit d'accéder à cette ressource")
     */
    public function delete(Ad $ad, EntityManagerInterface $manager)
    {
        $manager->remove($ad);
        $manager->flush();
        $this->addFlash(
            'success',
            "L'annonce <strong>{$ad->getTitle()}</strong> a été supprimée"
        );
        return $this->redirectToRoute("ads_index");
    }
}
