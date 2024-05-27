<?php

namespace App\Form;

use App\Entity\Activite;
use App\Entity\Coach;
use App\Entity\NomActivite;
use App\Entity\TypeActivite;
use Doctrine\Common\Collections\Expr\Value;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;


class ActiviteType extends AbstractType
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jour', options: [
                'constraints' => [
                    new Length([
                        'min' => 1,
                        'max' => 7,
                        'minMessage' => 'The day must be between 1 and 7',
                        'maxMessage' => 'The day must be between 1 and 7',
                    ]),
                ]
            ])
            ->add('heureDebut', options: [
                'constraints' => [
                    new Length([
                        'min' => 0,
                        'max' => 23,
                        'minMessage' => 'The hour must be between 0 and 23',
                        'maxMessage' => 'The hour must be between 0 and 23',
                    ]),
                ]
            ])
            ->add('nom', EntityType::class, [
                'class' => NomActivite::class,
                'choice_label' => 'nom',
            ])
            ->add('coach', EntityType::class, [
                'class' => Coach::class,
                'choice_label' => 'nom',
            ])
            ->add('type', EntityType::class, [
                'class' => TypeActivite::class,
                'choice_label' => 'nom',
            ])
            ->add('Submit', SubmitType::class);
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $activite = $event->getData();

            $existingActivite = $this->entityManager
                ->getRepository(Activite::class)
                ->findOneBy(['jour' => $activite->getJour(), 'heureDebut' => $activite->getHeureDebut()]);

            if ($existingActivite) {
                $form->addError(new FormError('An activity already exists for this day and start time.'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Activite::class,
        ]);
    }
}
