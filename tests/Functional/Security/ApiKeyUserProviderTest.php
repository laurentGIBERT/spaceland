<?php
declare(strict_types=1);
/**
 * /tests/Functional/Security/ApiKeyUserProviderTest.php
 *
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
namespace App\Tests\Functional\Security;

use App\Entity\ApiKey;
use App\Repository\ApiKeyRepository;
use App\Security\ApiKeyUser;
use App\Security\ApiKeyUserProvider;
use App\Security\RolesService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\User;

/**
 * Class ApiKeyUserProviderTest
 *
 * @package App\Tests\Functional\Security
 * @author  TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class ApiKeyUserProviderTest extends KernelTestCase
{
    /**
     * @var ApiKeyUserProvider
     */
    private $apiKeyUserProvider;

    /**
     * @dataProvider dataProviderTestThatGetApiKeyReturnsExpected
     *
     * @param string $shortRole
     */
    public function testThatGetApiKeyReturnsExpected(string $shortRole): void
    {
        $token = \str_pad($shortRole, 40, '_');

        $apiKey = $this->apiKeyUserProvider->getApiKeyForToken($token);

        static::assertInstanceOf(ApiKey::class, $apiKey);

        unset($apiKey);
    }

    /**
     * @dataProvider dataProviderTestThatGetApiKeyReturnsExpected
     *
     * @param string $shortRole
     */
    public function testThatGetApiKeyReturnsNullForInvalidToken(string $shortRole): void
    {
        $token = \str_pad($shortRole, 40, '-');

        $apiKey = $this->apiKeyUserProvider->getApiKeyForToken($token);

        static::assertNull($apiKey);

        unset($apiKey);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UsernameNotFoundException
     * @expectedExceptionMessage API key is not valid
     */
    public function testThatLoadUserByUsernameThrowsAnExceptionWithInvalidGuid(): void
    {
        $this->apiKeyUserProvider->loadUserByUsername((string)time());
    }

    /**
     * @dataProvider dataProviderTestThatLoadUserByUsernameWorksAsExpected
     *
     * @param string $token
     * @param array  $roles
     */
    public function testThatLoadUserByUsernameWorksAsExpected(string $token, array $roles): void
    {
        $apiKeyUser = $this->apiKeyUserProvider->loadUserByUsername($token);

        static::assertInstanceOf(ApiKeyUser::class, $apiKeyUser);
        static::assertSame($roles, $apiKeyUser->getApiKey()->getRoles());

        unset($apiKeyUser);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     * @expectedExceptionMessage API key cannot refresh user
     */
    public function testThatRefreshUserThrowsAnException(): void
    {
        $user = new User('username', 'password');

        $this->apiKeyUserProvider->refreshUser($user);

        unset($user);
    }

    /**
     * @dataProvider dataProviderTestThatSupportsClassReturnsExpected
     *
     * @param bool   $expected
     * @param string $class
     */
    public function testThatSupportsClassReturnsExpected(bool $expected, string $class): void
    {
        static::assertSame($expected, $this->apiKeyUserProvider->supportsClass($class));
    }

    /**
     * @return array
     */
    public function dataProviderTestThatGetApiKeyReturnsExpected(): array
    {
        static::bootKernel();

        // Store container and entity manager
        $container = static::$kernel->getContainer();
        $rolesService = $container->get('test.service_locator')->get(RolesService::class);

        $iterator = function (string $role) use ($rolesService): array {
            return [$rolesService->getShort($role)];
        };

        return \array_map($iterator, $rolesService->getRoles());
    }

    /**
     * @return array
     */
    public function dataProviderTestThatLoadUserByUsernameWorksAsExpected(): array
    {
        static::bootKernel();

        // Store container and entity manager
        $container = static::$kernel->getContainer();
        $managerRegistry = $container->get('doctrine');

        $repositoryClass = ApiKeyRepository::class;

        /** @var ApiKeyRepository $repository */
        $repository = new $repositoryClass($managerRegistry);

        $iterator = function (ApiKey $apiKey): array {
            return [
                $apiKey->getToken(),
                $apiKey->getRoles(),
            ];
        };

        return \array_map($iterator, $repository->findAll());
    }

    /**
     * @return array
     */
    public function dataProviderTestThatSupportsClassReturnsExpected(): array
    {
        return [
            [false, User::class],
            [true, ApiKeyUser::class],
        ];
    }

    protected function setUp(): void
    {
        gc_enable();

        parent::setUp();

        static::bootKernel();

        // Store container and entity manager
        $container = static::$kernel->getContainer();
        $managerRegistry = $container->get('doctrine');

        $repository = ApiKeyRepository::class;

        $this->apiKeyUserProvider = new ApiKeyUserProvider(
            new $repository($managerRegistry),
            $container->get('test.service_locator')->get(RolesService::class)
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->apiKeyUserProvider);
    }
}
