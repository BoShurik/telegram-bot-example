<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Post\Telegram\Command;

use App\Post\Model\Post;
use App\Post\Pagerfanta\InlineKeyboardMarkupFactory;
use App\Post\Repository\PostRepository;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use Pagerfanta\Pagerfanta;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

class PostCommand extends AbstractCommand implements PublicCommandInterface
{
    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * @var InlineKeyboardMarkupFactory
     */
    private $markupFactory;

    public function __construct(PostRepository $repository, InlineKeyboardMarkupFactory $markupFactory)
    {
        $this->repository = $repository;
        $this->markupFactory = $markupFactory;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return '/post';
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Post list';
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Update $update)
    {
        if (!$page = (int) $this->getCommandParameters($update)) {
            $page = 1;
        }

        $posts = $this->repository->findAllPaginated();
        $posts->setMaxPerPage(1);
        $posts->setCurrentPage($page);

        $messageId = null;
        if ($update->getCallbackQuery()) {
            $chat = $update->getCallbackQuery()->getMessage()->getChat();
            $messageId = $update->getCallbackQuery()->getMessage()->getMessageId();
        } else {
            $chat = $update->getMessage()->getChat();
        }

        $text = $this->getContent($posts);

        if ($messageId) {
            $api->editMessageText(
                $chat->getId(),
                $messageId,
                $text,
                'markdown',
                false,
                $this->markupFactory->create($posts)
            );
        } else {
            $api->sendMessage(
                $chat->getId(),
                $text,
                'markdown',
                false,
                null,
                $this->markupFactory->create($posts)
            );
        }
    }

    private function getContent(Pagerfanta $pagerfanta): string
    {
        $text = '';

        /** @var Post $post */
        foreach ($pagerfanta as $post) {
            $text .= sprintf("%d *%s*\n%s", $pagerfanta->getCurrentPage(), $post->getName(), $post->getDescription());
        }

        return $text;
    }

    protected function getTarget(): int
    {
        return self::TARGET_ALL;
    }
}
