<?php

namespace App\Command;

use Error;
use App\Service\EmailService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:send-email',
    description: 'Envoies les Emails en base de données',
)]
class sendEmailCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('limit-message', InputArgument::OPTIONAL, 'Nombre limite de messages à envoyer')
        ;
    }

    public function __construct(private EmailService $emailService)
    {
        $emailService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,$output);
       (int) $limitMessage = $input->getArgument('limit-message');
        
        $nbEmails = $this->emailService->sendEmail($limitMessage);
        
        $io->success($nbEmails . ' messages ont bien été envoyés');
        return Command::SUCCESS;
    }
}