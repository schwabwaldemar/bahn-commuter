<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\MailerAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RegistrationFlowTest extends WebTestCase
{
    use MailerAssertionsTrait;

    protected function setUp(): void
    {
        parent::setUp();

        self::ensureKernelShutdown();
        $kernel = self::bootKernel();

        /** @var ManagerRegistry $registry */
        $registry = $kernel->getContainer()->get('doctrine');
        $entityManager = $registry->getManager();

        $schemaTool = new SchemaTool($entityManager);
        $metadata = $entityManager->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropDatabase();

        if (!empty($metadata)) {
            $schemaTool->createSchema($metadata);
        }

        self::ensureKernelShutdown();
    }

    public function testRegistrationSendsConfirmationEmail(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/register');

        self::assertResponseIsSuccessful();
        self::assertGreaterThan(0, $crawler->filter('form')->count(), 'Registration form should be present.');

        $form = $crawler->filter('form')->form();
        $client->submit($form, [
            'registration_form[email]' => 'pendler@example.com',
            'registration_form[locale]' => 'de',
            'registration_form[plainPassword]' => 'Str0ngPass!',
            'registration_form[acceptTerms]' => '1',
            'registration_form[acceptPrivacy]' => '1',
        ]);

        self::assertResponseRedirects('http://localhost/de/register');

        $client->followRedirect();

        self::assertEmailCount(1);

        $email = self::getMailerMessage();
        self::assertNotNull($email, 'Expected an activation email to be sent.');
        self::assertEmailAddressContains($email, 'To', 'pendler@example.com');
        self::assertEmailSubjectContains($email, 'Bitte bestätige dein Bahn-Commuter-Konto');
        self::assertEmailHtmlBodyContains($email, 'E-Mail bestätigen');
    }

    public function testRegistrationRejectsInvalidEmail(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/en/register');

        self::assertResponseIsSuccessful();

        $form = $crawler->filter('form')->form();
        $client->submit($form, [
            'registration_form[email]' => 'superadmin',
            'registration_form[locale]' => 'de',
            'registration_form[plainPassword]' => 'Str0ngPass!',
            'registration_form[acceptTerms]' => '1',
            'registration_form[acceptPrivacy]' => '1',
        ]);

        $responseContent = $client->getResponse()->getContent();
        self::assertNotFalse($responseContent);
        self::assertResponseStatusCodeSame(422);
        self::assertStringContainsString('Please enter a valid email address.', $responseContent);
        self::assertEmailCount(0);

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);
        self::assertNull($userRepository->findOneBy(['email' => 'superadmin']));
    }
}
