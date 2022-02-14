<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController{

    public function index(){
        $title = 'Home';
        return $this->render('home.html.twig',
            array('title' => $title));
    }

    public function contact(){
        $title = 'Contact';
        return $this->render('contact.html.twig',
            array('title' => $title));
    }


}

