<?php

namespace App\Controller;

use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class AnswerController extends AbstractController
{
    /**
     * @Route("/answer/validate/{id}", name="answer_validate")
     * @IsGranted ("ROLE_USER")
     * @IsGranted ("validate", subject="answer")

     */
    public function validate(Answer $answer)
    {
        // L'auteur de la question est-il le user qui valide ?
        $user = $this->getUser();
        /* if ($user !== $answer->getQuestion()->getUser()) {
            throw $this->createAccessDeniedException('Non autorisé.');
        } */
        if ($answer->getQuestion()->getActive() == 1)
        {
            // Valide réponse
            $answer->setIsValidated(true);
            // Valide question
            $answer->getQuestion()->setIsSolved(true);
            // Flush
            $this->getDoctrine()->getManager()->flush();
            // Flash
            $this->addFlash('success', 'Réponse acceptée');
            // Redirection
            return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
        }
        else
        {
            $this->addFlash('failure', 'La question est trop vieille, tant pis pour toi');
            return $this->redirectToRoute('question_show');
        }

    }

    /**
     * @Route("/admin/answer/toggle/{id}", name="admin_answer_toggle")
     *
     */
    public function adminToggle(Answer $answer = null)
    {
        if (null === $answer) {
            throw $this->createNotFoundException('Réponse non trouvée.');
        }

        // Inverse the boolean value via not (!)
        $answer->setIsBlocked(!$answer->getIsBlocked());
        // Save
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success', 'Réponse modérée.');

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }

}
