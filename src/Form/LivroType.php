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

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'attr' => ['placeholder' => 'Ex.: Dom Casmurro'],
                'constraints' => [
                    new NotBlank(message: 'Informe o título.'),
                    new Length(max: 40, maxMessage: 'Máximo de 40 caracteres.'),
                ],
            ])
            ->add('editora', TextType::class, [
                'label' => 'Editora',
                'attr' => ['placeholder' => 'Ex.: Garnier'],
                'constraints' => [
                    new NotBlank(message: 'Informe a editora.'),
                    new Length(max: 40, maxMessage: 'Máximo de 40 caracteres.'),
                ],
            ])
            ->add('edicao', IntegerType::class, [
                'label' => 'Edição',
                'attr' => ['min' => 1],
                'constraints' => [
                    new NotBlank(message: 'Informe a edição.'),
                    new Positive(message: 'A edição deve ser um número positivo.'),
                ],
            ])
            ->add('anoPublicacao', TextType::class, [
                'label' => 'Ano de Publicação',
                'attr' => [
                    'data-mask' => 'year',
                    'inputmode' => 'numeric',
                    'maxlength' => 4,
                    'placeholder' => 'Ex.: 2020',
                ],
                'constraints' => [
                    new NotBlank(message: 'Informe o ano.'),
                    new Range(
                        min: 1500,
                        max: 2100,
                        notInRangeMessage: 'Ano inválido.',
                        invalidMessage: 'O ano deve conter exatamente 4 dígitos.',
                    ),
                ],
            ])
            ->add('valor', MoneyType::class, [
                'label' => 'Preço',
                'currency' => 'BRL',
                'html5' => false,
                'scale' => 2,
                'invalid_message' => 'Informe um valor válido.',
                'grouping' => true,
                'attr' => [
                    'data-mask' => 'money',
                    'inputmode' => 'decimal',
                    'placeholder' => '0,00',
                    'autocomplete' => 'off',
                ],
                'constraints' => [
                    new NotBlank(message: 'Informe o valor.'),
                    new Positive(message: 'O valor deve ser positivo.'),
                ],
            ])
            ->add('autores', EntityType::class, [
                'class' => Autor::class,
                'choice_label' => 'nome',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('assuntos', EntityType::class, [
                'class' => Assunto::class,
                'choice_label' => 'descricao',
                'multiple' => true,
                'expanded' => true,
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
