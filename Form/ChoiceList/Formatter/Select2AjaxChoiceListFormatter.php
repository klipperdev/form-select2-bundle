<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\FormSelect2Bundle\Form\ChoiceList\Formatter;

use Klipper\Component\Form\ChoiceList\Factory\TagDecorator;
use Klipper\Component\Form\ChoiceList\Formatter\AjaxChoiceListFormatterInterface;
use Klipper\Component\Form\ChoiceList\Formatter\FormatterUtil;
use Klipper\Component\Form\ChoiceList\Loader\AjaxChoiceLoaderInterface;
use Symfony\Component\Form\ChoiceList\Factory\ChoiceListFactoryInterface;
use Symfony\Component\Form\ChoiceList\Factory\DefaultChoiceListFactory;
use Symfony\Component\Form\ChoiceList\Factory\PropertyAccessDecorator;
use Symfony\Component\Form\ChoiceList\View\ChoiceGroupView;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class Select2AjaxChoiceListFormatter implements AjaxChoiceListFormatterInterface
{
    /**
     * @var ChoiceListFactoryInterface
     */
    private $choiceListFactory;

    /**
     * Constructor.
     */
    public function __construct(ChoiceListFactoryInterface $choiceListFactory = null)
    {
        $this->choiceListFactory = $choiceListFactory ?: new PropertyAccessDecorator(new TagDecorator(new DefaultChoiceListFactory()));
    }

    public function formatResponse(array $data): Response
    {
        return new JsonResponse($data);
    }

    public function formatResponseData(AjaxChoiceLoaderInterface $choiceLoader): array
    {
        $view = $this->choiceListFactory->createView($choiceLoader->loadPaginatedChoiceList(), null, $choiceLoader->getLabel());

        return [
            'size' => $choiceLoader->getSize(),
            'pageNumber' => $choiceLoader->getPageNumber(),
            'pageSize' => $choiceLoader->getPageSize(),
            'search' => $choiceLoader->getSearch(),
            'results' => FormatterUtil::formatResultData($this, $view),
            'pagination' => [
                'more' => max(1, $choiceLoader->getPageNumber() * $choiceLoader->getPageSize()) < $choiceLoader->getSize(),
            ],
        ];
    }

    public function formatChoice(ChoiceView $choice): array
    {
        return [
            'id' => $choice->value,
            'text' => $choice->label,
        ];
    }

    public function formatGroupChoice(ChoiceGroupView $choiceGroup): array
    {
        return [
            'text' => $choiceGroup->label,
            'children' => [],
        ];
    }

    public function addChoiceInGroup($group, ChoiceView $choice): array
    {
        $group['children'][] = $this->formatChoice($choice);

        return $group;
    }

    public function isEmptyGroup($group): bool
    {
        return 0 === \count($group['children']);
    }
}
