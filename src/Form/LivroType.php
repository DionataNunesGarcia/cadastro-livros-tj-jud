<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Informe o título.'),
                    new Length(max: 40, maxMessage: 'Máximo de 40 caracteres.'),
                ],
            ])
            ->add('editora', TextType::class, [
                'constraints' => [
                    new NotBlank(message: 'Informe a editora.'),
                    new Length(max: 40, maxMessage: 'Máximo de 40 caracteres.'),
                ],
            ])
            ->add('edicao', IntegerType::class, [
                'constraints' => [
                    new NotBlank(message: 'Informe a edição.'),
                    new Positive(message: 'A edição deve ser um número positivo.'),
                ],
            ])
            ->add('anoPublicacao', TextType::class, [
                'label' => 'Ano de Publicação',
                'constraints' => [
                    new NotBlank(message: 'Informe o ano.'),
                    new Regex(
                        pattern: '/^\d{4}$/',
                        message: 'O ano deve conter exatamente 4 dígitos.',
                    ),
                    new Range(min: 1500, max: 2100, notInRangeMessage: 'Ano inválido.'),
                ],
            ])
            ->add('valor', MoneyType::class, [
                'currency' => 'BRL',
                'html5' => true,
                'scale' => 2,
                'constraints' => [
                    new NotBlank(message: 'Informe o valor.'),
                    new Positive(message: 'O valor deve ser positivo.'),
                ],
            ])
            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'nome',
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'descricao',
                'multiple' => true,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
        ]);
    }
}
