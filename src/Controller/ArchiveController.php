<?php

namespace App\Controller;

use App\Entity\Archive;
use App\Repository\ArchiveRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ArchiveController.
 *
 * @Route("archive")
 * @package App\Controller
 */
class ArchiveController extends Controller
{

    /**
     * Lists all archive entities.
     *
     * @Route("/", name="archive_index")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(ArchiveRepository $archiveRepository): Response
    {
        $archives = $archiveRepository->findAll();

        return $this->render('archive/index.html.twig', array(
            'archives' => $archives,
        ));
    }

    /**
     * Display presentation of the association.
     *
     * @Route("/association", name="present_asso")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function asso(ArchiveRepository $archiveRepository): Response
    {
        $presentAsso = $archiveRepository->findOneBy(['title'=> 'Présentation Association']);
        return $this->render('archive/present_asso.html.twig', ['archive'=>$presentAsso]);
    }

    /**
     * Finds and displays a archive entity.
     *
     * @Route("/{id}", name="archive_show")
     * @Method("GET")
     *
     * @param \App\Entity\Archive $archive
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Archive $archive): Response
    {

        return $this->render('archive/show.html.twig', array(
            'archive' => $archive,
        ));
    }
}
