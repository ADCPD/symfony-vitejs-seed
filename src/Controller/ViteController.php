<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ViteController
 * @package App\Controller
 */
class ViteController extends AbstractController
{
    /**
     * @Route("/", name="homepage_vite")
     */
    public function index(): Response
    {
        //$manifestDir = \dirname(__DIR__). '/../public/assets/manifest.json';
        //dump(json_decode(file_get_contents($manifestDir,true)));
        return $this->render('vite/index.html.twig', [
            'controller_name' => 'ViteController',
        ]);
    }
}
