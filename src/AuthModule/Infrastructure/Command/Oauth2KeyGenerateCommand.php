<?php

declare(strict_types=1);

namespace Netborg\Fediverse\Api\AuthModule\Infrastructure\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'oauth2:key:generate', description: 'Generates RSA keys for OAuth2 server')]
class Oauth2KeyGenerateCommand extends Command
{
    private const FORCE = 'force';
    private const USER = 'user';
    private const GROUP = 'group';
    private const BITS = 'bits';

    private int $bits = 2048;
    private string $user;
    private string $group;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $privateKeyLocation,
        private readonly string $publicKeyLocation,
        #[\SensitiveParameter]
        private readonly ?string $passPhrase = null,
        string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->addOption(
            name: self::FORCE,
            shortcut: 'f',
            mode: InputOption::VALUE_NONE,
            description: 'Force override existing RSA keys'
        );

        $this->addOption(
            name: self::USER,
            mode: InputOption::VALUE_REQUIRED,
            description: 'Provide your Web Server\'s system USER it is being run by (ie. most likely your Apache server runs as `www-data`)',
            default: 'www-data'
        );

        $this->addOption(
            name: self::GROUP,
            mode: InputOption::VALUE_REQUIRED,
            description: 'Provide your Web Server\'s system GROUP it is being run by (ie. most likely your Apache server runs as `www-data`)',
            default: 'www-data'
        );

        $this->addOption(
            name: self::BITS,
            mode: InputOption::VALUE_REQUIRED,
            description: 'Provide how many bits should be used to generate a private key (default: 2048)',
            default: 2048
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $force = $input->getOption(self::FORCE);
        $this->bits = $input->getOption(self::BITS);
        $this->user = $input->getOption(self::USER);
        $this->group = $input->getOption(self::GROUP);

        if (!$force && (file_exists($this->privateKeyLocation) || file_exists($this->publicKeyLocation))) {
            $io->warning('RSA keys already exists! Execute with `--force`|`-f` option if you want to override existing keys.');

            return self::SUCCESS;
        }

        try {
            $this->generateKeys();
        } catch (\Throwable $exception) {
            $msg = sprintf("Unable to to generate RSA key pair due to:\n%s", $exception->getMessage());
            $io->error($msg);
            $this->logger->error($exception->getMessage(), $exception->getTrace());

            return self::FAILURE;
        }

        $io->success('RSA keys successfully generated!');

        return self::SUCCESS;
    }

    private function generateKeys(): void
    {
        $config = [
            'digest_alg' => 'sha512',
            'private_key_bits' => $this->bits,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];

        if ($this->passPhrase) {
            $config += [
                'encrypt_key' => true,
                'encrypt_key_cipher' => OPENSSL_CIPHER_AES_256_CBC,
            ];
        }

        $res = openssl_pkey_new($config);
        openssl_pkey_export($res, $privateKey, $this->passPhrase);
        $publicKey = openssl_pkey_get_details($res)['key'];

        $this->saveKey($this->privateKeyLocation, $privateKey, 0600);
        $this->saveKey($this->publicKeyLocation, $publicKey, 0644);
    }

    private function saveKey(string $filePath, string $content, int $fileMode): void
    {
        if (!file_exists($dir = dirname($filePath))) {
            mkdir($dir, 0775, true);
            chown($dir, $this->user);
            chgrp($dir, $this->group);
        }

        file_put_contents($filePath, $content);
        chmod($filePath, $fileMode);
        chown($filePath, $this->user);
        chgrp($filePath, $this->group);
    }
}
