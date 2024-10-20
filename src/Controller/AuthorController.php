<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;
use App\Form\FormNameType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/author/{name}', name: 'app_author')]
    public function showAuthor($name): Response
    {
        return $this->render('author/show.html.twig', ['name' => $name]);
    }
    #[Route('/list',name:'app_list')]
    public function listAuthors():Response
    {
         $authors = array(
    array('id' => 1, 'picture' => '\images\victor_hugo.jpg', 'username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com', 'nb_books' => 100),
    array('id' => 2, 'picture' => '/images/william.jpg', 'username' => 'William Shakespeare', 'email' => 'william.shakespeare@gmail.com', 'nb_books' => 200),
    array('id' => 3, 'picture' => '/images/taha_hussein.jpg', 'username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
);
return $this->render('author/list.html.twig',['authors'=>$authors]);
    }
    #[Route('/affiche',name:'app_affiche')]
    public function afficher(AuthorRepository $ar):Response
    {
        $list=$ar->findAll();
        return $this->render('author/afficher.html.twig',['list'=>$list]);
    }
    #[Route('/Add',name:'author_add')]
    public function ajouter(ManagerRegistry $doctrine, Request $request):Response
    {
        $author=new Author(); //nouveau objet author
    
       
        $form = $this->createForm(FormNameType::class, $author);
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            
          
        $em=$doctrine->getManager(); // appel entity manager
        $em->persist($author); // insert into
        $em->flush();// d'envoyer tout ce qui a été persisté avant à la base de données
        return $this->redirectToRoute('app_affiche');
        }
             return $this->render('author/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[route('/delete/{id}',name:'app_delete')]
public function delete(AuthorRepository $repoAuthor,int $id,EntityManagerInterface $entityManager):Response{
    $auth=$repoAuthor->find($id);
    $entityManager->remove($auth);
     $entityManager->flush();
    return $this->redirectToRoute('app_affiche');
}
#[Route('/Update/{id}',name:'author_update')]
    public function update(ManagerRegistry $doctrine,Request $request,$id,AuthorRepository $repoAuthor):response
    {
        $author=$repoAuthor->find($id);
        $form=$this->createForm(FormNameType::class,$author);// creation formulaire à partir de classe authortype
        $form->handleRequest($request);//traiter les données recus 
       if ($form->isSubmitted() )//verifier  form envoyer valide 
       {
        $em=$doctrine->getManager(); 
        $em->flush();// d'envoyer tout ce qui a été persisté avant à la base de données
        return $this->redirectToRoute('app_affiche');
    }
    return $this->render('author/update.html.twig',['form'=>$form->createView()]) ;
    
    }

              }    