<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class SearchController extends Controller {

    /**
     * @Route("/search", name="search")
     * @Security("has_role('ROLE_USER')")
     */
    public function searchAction(Request $request) {
        
    }


}

