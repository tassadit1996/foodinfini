<?php

namespace App\Controller;


use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MailerController extends AbstractController
{
    #[Route('/mail', name: 'mail')]
    public function sendEmail(MailerInterface $mailer, Request $request): Response
    {
        $emailForm = $this->createFormBuilder()
               ->add('message', TextareaType::class, [
                      'attr'=> array('rows'=>'5')
               ])
               ->add('envoyer', SubmitType::class)
               ->getForm();
        $emailForm->handleRequest($request);
        
        if ($emailForm->isSubmitted()) {
           $input= $emailForm->getData();
           $text= ($input['message']);
           $tables = 'tables1';
        $email = (new TemplatedEmail())
                 ->from('tassadit.mgh@gmail.com')
                 ->to('adoudou.mgh@gmail.com')
                 ->subject('Message')
                 ->text('votre est prète')

                 ->htmlTemplate('mailer/mail.html.twig')
                 ->context([
                     'tables'=>$tables,
                     'text'=>$text
                 ]);

         $mailer->send($email);

         $this->addFlash('message', 'message envoyé');
        return $this->redirect($this->generateUrl('mail'));
        }
        return $this->render('mailer/index.html.twig',[
            'emailForm'=>$emailForm->createView()
        ]);
        
    }
}
