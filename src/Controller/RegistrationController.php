<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/{_locale?en}/register', name: 'app_register', requirements: ['_locale' => 'en|de'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'current_locale' => $request->getLocale(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $user->setLocale($form->get('locale')->getData());

            $acceptedAt = new DateTimeImmutable();
            $user->acceptTerms($acceptedAt);
            $user->acceptPrivacy($acceptedAt);

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@bahn-commuter.ddev.site', 'Bahn Commuter'))
                    ->to((string) $user->getEmail())
                    ->subject($translator->trans('registration.email.subject', locale: $user->getLocale()))
                    ->locale($user->getLocale())
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context([
                        'locale' => $user->getLocale(),
                    ])
            );

            $this->addFlash('success', 'registration.flash.check_email');

            return $this->redirectToRoute('app_register', ['_locale' => $user->getLocale()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/{_locale?en}/verify/email', name: 'app_verify_email', requirements: ['_locale' => 'en|de'])]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UserRepository $userRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register', ['_locale' => $request->getLocale()]);
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register', ['_locale' => $request->getLocale()]);
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register', ['_locale' => $request->getLocale()]);
        }

        $this->addFlash('success', 'registration.flash.email_verified');

        return $this->redirectToRoute('app_home', ['_locale' => $request->getLocale()]);
    }
}
