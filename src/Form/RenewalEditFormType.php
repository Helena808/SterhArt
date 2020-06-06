<?php

namespace App\Form;

use App\Entity\Renewal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RenewalEditFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentExec', TextareaType::class, array(
                'label' => 'Комментарий исполнителя',
                'attr' => array(
                    'placeholder' => 'Введите комментарий'),
            ))
            ->add('sketches', FileType::class, array(
                'required' => false,
                'mapped' => false,
                'label' => 'Загрузить изображения',
                'multiple' => true,       
            ))
            ->add('save', SubmitType::class, array(
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ))

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Renewal::class,
        ]);
    }
}
