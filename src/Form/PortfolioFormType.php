<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\Project;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) 
    {
        $builder
            ->add('projectTitle', TextType::class, array(
            	'label' => 'Название',
            ))
            ->add('city', TextType::class, array(
            	'label' => 'Город',
            ))
            ->add('description', TextareaType::class, array(
            	'label' => 'Описание',
            ))
            ->add('photos', FileType::class, array(
	            'mapped' => false,
	            'label' => 'Загрузить изображения',
	            'multiple' => true,       
	        ))
            ->add('save', SubmitType::class, array(
            	'attr' => [
            		'class' => 'btn btn-success'
            	]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}