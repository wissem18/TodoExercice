<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route("/todo")]
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
    public function index(Request $r): Response
    {
        $session=$r->getSession();
        if(!$session->has('todos')){
            $todos=[
                'achat'=>'acheter clé usb',
                'cours'=>'finaliser mon cours',
                'correction'=>'corriger mes examens'
            ];
            $session->set('todos',$todos);
        }
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }
    #[Route('/add/{name}/{content}',name:'todo.add')]
    public function addTodo(Request $r,$name,$content):RedirectResponse
    {
        $session=$r->getSession();
        $todos=$session->get('todos');
        if($session->has('todos')){
            if(isset($todos[$name])){
                $this->addFlash('error','le todo avec cet id est déja existant');
            }
            else{
                $todos[$name]=$content;
                $session->set('todos',$todos);
                $this->addFlash('success','todo ajouté avec succès');
            }
        }
        else
            $this->addFlash('error',"la liste n'est pas encore initialisée");
        return $this->redirectToRoute('todo');
    }
    #[Route('/update/{name}/{content}',name:'todo.update')]
    public function updateTodo(Request $r,$name,$content):RedirectResponse
    {
        $session=$r->getSession();
        $todos=$session->get('todos');
        if($session->has('todos')){
            if(isset($todos[$name])){
                $todos[$name]=$content;
                $session->set('todos',$todos);
                $this->addFlash('success',"todo modifié avec succès");
            }
            else{
                $this->addFlash('error',"le todo avec cet id n'existe pas" );
            }
        }
        else
            $this->addFlash('error',"la liste n'est pas encore initialisée");
        return $this->redirectToRoute('todo');
    }
    #[Route('/delete/{name}',name:'todo.delete')]
    public function deleteTodo(Request $r,$name):RedirectResponse
    {
        $session=$r->getSession();
        $todos=$session->get('todos');
        if($session->has('todos')){
            if(isset($todos[$name])){
                unset($todos[$name]);
                $session->set('todos',$todos);
                $this->addFlash('sucess',"todo suprimé avec succes");
            }
            else{
                $this->addFlash('error',"le todo avec cet id n'existe pas" );
            }
        }
        else
            $this->addFlash('error',"la liste n'est pas encore initialisée");
        return $this->redirectToRoute('todo');
    }
    #[Route('/reset','todo.reset')]
    public function reset(Request $r):RedirectResponse{
        $session=$r->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('todo');
    }
}

