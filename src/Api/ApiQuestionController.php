<?php

namespace App\Api;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ApiQuestionController
 * @package App\src\Api
 * @Route("/api")
 */
class ApiQuestionController extends AbstractController
{
    /**
     * @Route("/list", name="api_question_list", methods={"GET"})
     */
    public function list(QuestionRepository $repository)
    {
        $this->json(
            $repository->findAll(),
            200,
            [],
            ["groups" => ["question:list"]]
        );
    }

    /**
     * @param Question $question
     * @Route("/{id}/view", name="api_question_view", methods={"GET"})
     */
    public function questionView(Question $question)
    {
        $this->json(
            $question,
        200,
        [],
        ["groups" => ["question:view"]]);
    }
}