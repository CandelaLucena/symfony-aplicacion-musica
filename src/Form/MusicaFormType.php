<?php

namespace App\Form;

use App\Entity\Musica;
use App\Entity\Autor;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre')
            ->add('precio')
            ->add('autor',EntityType::class,array(
                'class' =>Autor::class,
                'choice_label' => 'nombre',))
            ->add ('save',SubmitType::class,array('labe'=>'Enviar'));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Musica::class,
        ]);
    }
}
