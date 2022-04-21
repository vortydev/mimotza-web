<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Utilisateur;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/inscription', name: 'inscription')]
    public function inscription(): Response
    {
        $form = $this->createFormBuilder()
            ->add('prenom', TextType::class, ['label' => 'Prénom'])
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('username', TextType::class, ['label' => 'Nom d\'utilisateur'])
            ->add('mdp', PasswordType::class, ['label' => 'Mot de passe'])
            ->add('submit', SubmitType::class, ['label' => 'S\'inscrire!'])
            ->setAction($this->generateUrl('confirmation'))
            ->getForm();

        return $this->renderForm('user/inscription.html.twig', [
            'controller_name' => 'UserController',
            'form' => $form,
        ]);
    }

    #[Route('/confirmation', name: 'confirmation')]
    public function confirmation() : Response {
        $request = Request::createFromGlobals();
        $form = $request->get('form');

        return $this->render('user/confirmation.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/user/{id}', name: 'particular_user')]
    public function showUser(ManagerRegistry $regis, $id): Response
    {
        $userRepository = $regis->getRepository(Utilisateur::class);
        //$user = $userRepository->findOneBy(['id'=>$id]);

        $user = array('username'=>'bob',
                        'avatar'=>'none',
                        'partie'=>'6',
                        'partieWin'=>'3',
                        'temps'=>'4h',
                        'dateCreation'=>'21/04/2022',
                        'statut'=>'Banni'
                    );

        return $this->render('user/user.html.twig', [
            'controller_name' => 'UserController',
            'user' => $user
        ]);
    }
}
