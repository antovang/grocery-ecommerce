<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController{

    /**
     * @return mixed
     */
    public function index(){
        $title = 'Home';
        return $this->render('home.html.twig',
            array('title' => $title));
    }

    /**
     * @return mixed
     */
    public function contact(){
        $title = 'Contact';
        return $this->render('contact.html.twig',
            array('title' => $title));
    }

}

