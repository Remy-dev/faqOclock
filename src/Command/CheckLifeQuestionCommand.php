<?php

namespace App\Command;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Helper\QuestionHelper;

class CheckLifeQuestionCommand extends Command
{
    protected static $defaultName = 'app:checkLifeQuestion';
    protected $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Check if the questions are older than 7 days and classify them as inactive')
            ->addArgument('arg1',InputArgument::REQUIRED, 'Le premier argument optionnel pour déterminer le nombre de jour avant d\'être inactif, la valeur a insérer est un entier représentant le nombre de jour')
            ->addOption('enable', null, InputOption::VALUE_NONE, '[option] précise si la question doit être réactivé  [enable]')

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $repo = $this->manager->getRepository(Question::class);
        // $questions = $repo->findOldQuestions();
        $questions = $repo->findAll();

        $arg1 = $input->getArgument('arg1');
        $option = $input->hasOption('enable');

        // vérifie l'argument passé a la commande
        if ('uniq' == $arg1 )
        {
            $helper = $this->getHelper('question');

            $question = new \Symfony\Component\Console\Question\Question('Please enter the title of question you want to modify : ', false);

            $titleToFind = $helper->ask($input, $output, $question);

            // vérification de l'intégrité de la longueur de la chaine
            if(preg_match('#^[^\d+][a-zA-Z]{1,255}$#', $titleToFind))
            {

                foreach ($questions as $question)
                {
                    if($question->getTitle() == $titleToFind)
                    {

                        if($option) {
                            $question->setActive(true);
                            $io->success('La question a été réactivé');
                            $this->manager->flush();
                            return 0;
                        }

                        $question->setActive(false);
                        $io->success('La question a été désactivé');
                        $this->manager->flush();
                        return 0;
                    }
                }

                $io->success('erreur');
                return 1;

            } else {

                $io->success('Le format du titre n\'est pas bon');
                return 1;
            }

        }
        if(preg_match('#\d+#', $arg1))
        {
            foreach ($questions as $question)
            {

                $deadLine = $arg1 ;
                $date = new \DateTime('now');
                $intervalOfCreation = $question->getCreatedAt()->diff($date);
                $dayIntervalCreation = $intervalOfCreation->format('%a');

                if($question->getUpdatedAt() != null)
                {
                    $intervalOfUpdate = $question->getUpdatedAt()->diff($date);
                    $dayIntervalUpdate = $intervalOfUpdate->format('%a');

                    if ($dayIntervalCreation > $deadLine || $dayIntervalUpdate > $deadLine)
                    {
                        $question->setActive(false);
                    } else {
                        $question->setActive(true);
                    }

                }

                if ($dayIntervalCreation > $deadLine)
                {
                    $question->setActive(false);
                }
                else
                {
                    $question->setActive(true);
                }
            }

            $this->manager->flush();

            $io->success('Les questions ont été mise a jour');

            return 0;
        }

    }
}
