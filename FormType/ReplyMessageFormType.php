<?php

namespace Ornicar\MessageBundle\FormType;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;

/**
 * Form type for a reply
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 */
class ReplyMessageFormType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('body', 'textarea');
    }

    public function getName()
    {
        return 'ornicar_message_reply_message';
    }
}
