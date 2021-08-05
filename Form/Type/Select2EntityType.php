<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\FormSelect2Bundle\Form\Type;

use Klipper\Bundle\FormSelect2Bundle\Form\ChoiceList\Formatter\Select2AjaxChoiceListFormatter;
use Klipper\Component\Form\Doctrine\Type\AbstractAjaxEntityType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Select2 entity form type.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class Select2EntityType extends AbstractAjaxEntityType
{
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['attr'] = array_merge($view->vars['attr'], [
            'data-ajax--url' => $this->generateAjaxUrl($options),
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'ajax_formatter' => new Select2AjaxChoiceListFormatter(),
        ]);
    }
}
