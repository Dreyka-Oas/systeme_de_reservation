<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityManagerInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();  // Création d'un nouvel utilisateur

        // Créer le formulaire avec les champs nécessaires
        $form = $this->createFormBuilder($user)
            ->add('email')
            ->add('plainPassword', PasswordType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'label'    => 'Agree to terms',
                'required' => true,
            ])
            ->add('name', TextType::class)
            ->add('lastName', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mot de passe en clair
            $plainPassword = $form->get('plainPassword')->getData();

            // Hacher le mot de passe
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);

            // Appliquer le mot de passe haché à l'utilisateur
            $user->setPassword($hashedPassword);

            // Enregistrer l'utilisateur dans la base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Rediriger vers la page de profil ou une autre page après l'enregistrement
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
