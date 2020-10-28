<?php
/**
 * User: boshurik
 * Date: 26.10.2020
 * Time: 19:45
 */

namespace App\Post\Pagerfanta;

use Pagerfanta\Pagerfanta;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;

class InlineKeyboardMarkupFactory
{
    public function create(Pagerfanta $pagerfanta): InlineKeyboardMarkup
    {
        $buttons= [];
        if ($pagerfanta->getCurrentPage() !== 1) {
            $buttons[] = ['text' => 'First', 'callback_data' => '/post'];
        }
        if ($pagerfanta->hasPreviousPage()) {
            $buttons[] = ['text' => 'Prev', 'callback_data' => '/post '.$pagerfanta->getPreviousPage()];
        }
        if ($pagerfanta->hasNextPage()) {
            $buttons[] = ['text' => 'Next', 'callback_data' => '/post '.$pagerfanta->getNextPage()];
        }
        if ($pagerfanta->getCurrentPage() !== $pagerfanta->getNbPages()) {
            $buttons[] = ['text' => 'Last', 'callback_data' => '/post '.$pagerfanta->getNbPages()];
        }

        return new InlineKeyboardMarkup([$buttons]);
    }
}
