<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SearchController extends Controller {

    /**
     * @Route("/search", name="search")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchAction(Request $request) {
        
    }


}

