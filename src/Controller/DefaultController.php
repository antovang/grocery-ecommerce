<?php
namespace App\Controller;
use App\Repository\ProduitRepository;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController{

    /**
     * @return mixed
     */
    public function index()
    {
        $title = 'Home';
        return $this->render('home.html.twig',
            array('title' => $title));
    }

    /**
     * @return mixed
     */
    public function contact()
    {
        $title = 'Contact';
        return $this->render('contact.html.twig',
            array('title' => $title));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(ProduitRepository $repo)
    {
        $title = 'Recherche';
        $categoryLabel = 'Recherche';
        $search = '';
        if(isset($_GET['search']))
            $search = $_GET['search'];
        $produits = $repo->findByLibelle($search);

        return $this->render('rayon.html.twig',
            [
                'produits' => $produits,
                'title' => $title,
                'categoryLabel' => $categoryLabel]
        );
    }
}

