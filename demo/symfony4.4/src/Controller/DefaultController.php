<?php

namespace App\Controller;

use App\Entity\Secret;
use App\Repository\SecretRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route(name="home", path="/")
     */
    public function index(SecretRepository $secretRepository): Response
    {
        $secrets = $secretRepository->findAll();

        return $this->render('index.html.twig',['secrets' => $secrets]);
    }

    /**
     * @Route(name="create", path="/create")
     */
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        if (!$request->query->has('name') || !$request->query->has('secret')) {
            return new Response('Please specify name and secret in url-query');
        }

        $secret = new Secret();

        $secret
            ->setName($request->query->getAlnum('name'))
            ->setSecret($request->query->getAlnum('secret'));

        $em->persist($secret);
        $em->flush();

        return new Response(sprintf('OK - secret %s stored',$secret->getName()));
    }
}