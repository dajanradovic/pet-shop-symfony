<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\SmallIntType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;


class UserEditType extends AbstractType
{

    private $security;
    private $doctrine;

    public function __construct(Security $security, ManagerRegistry $doctrine)
    {
        // Avoid calling getUser() in the constructor: auth may not
        // be complete yet. Instead, store the entire Security object.
        $this->security = $security;
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, ['required' => false])
            ->add('name', TextType::class, ['required' => false]  )
            ->add('age', IntegerType::class, ['required' => false])
            ->add('interests', TextType::class, ['required' => false])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'required' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    /*new NotBlank([
                        'message' => 'Please enter a password',
                    ]),*/
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('save', SubmitType::class)
            /*->addEventListener(
                FormEvents::POST_SUBMIT, [$this, 'onPostSubmit']
            )*/

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function onPostSubmit(FormEvent $event)
{

    $entityManager = $this->doctrine->getManager();
    $uow = $entityManager->getUnitOfWork();
    $originalUserData = $uow->getEntityChangeSet($this->security->getUser());

    if($event->getData()->getEmail() != $originalUserData['email'])
    dd($event->getData()->getEmail());
    //$user = $this->security->getUser();
//dd($user);
    //$newData = $event->getData();
   // $newContent = $newData->getContent();
    $uow = $this->em->getUnitOfWork();
    dd($uow);
    $oldData = $uow->getOriginalEntityData($newData);
    dd($oldData);
    //oldContent = $OriginalEntityData["content"];


   /* if($newContent != $oldContent) {
        // ...
    }*/
}
}
