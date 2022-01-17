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
use App\Post\Repository\PostRepository;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Update;

class PostCommand extends AbstractCommand implements PublicCommandInterface
{
    private const REGEX_INDEX = '#/post_(\d+)#';

    public function __construct(private PostRepository $repository)
    {
    }

    public function getName(): string
    {
        return '/post';
    }

    public function getDescription(): string
    {
        return 'Post list';
    }

    public function execute(BotApi $api, Update $update): void
    {
        $posts = $this->repository->findAll();
        $index = (int) $this->getIndex($update);
        $index = isset($posts[$index]) ? $index : 0;

        $messageId = $chatId = null;
        if ($update->getCallbackQuery()) {
            $chat = $update->getCallbackQuery()->getMessage()->getChat();
            $messageId = $update->getCallbackQuery()->getMessage()->getMessageId();
        } else {
            $chat = $update->getMessage()->getChat();
        }

        $this->post($api, $posts[$index], $index, (string) $chat->getId(), $messageId);
    }

    public function isApplicable(Update $update): bool
    {
        if (parent::isApplicable($update)) {
            return true;
        }

        return $this->getIndex($update) !== null;
    }

    private function getIndex(Update $update): ?int
    {
        if ($update->getMessage() && preg_match(self::REGEX_INDEX, $update->getMessage()->getText(), $matches)) {
            return (int) $matches[1];
        }
        if ($update->getCallbackQuery() && preg_match(self::REGEX_INDEX, $update->getCallbackQuery()->getData(), $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    private function post(BotApi $api, Post $post, int $index, string $chatId, int $messageId = null): void
    {
        $prev = $next = null;
        if ($index - 1 >= 0) {
            $prev = $index - 1;
        }
        if ($index + 1 < 5) {
            $next = $index + 1;
        }

        $buttons = [];
        if ($prev !== null) {
            $buttons[] = ['text' => 'Prev', 'callback_data' => '/post_'.$prev];
        }
        if ($next !== null) {
            $buttons[] = ['text' => 'Next', 'callback_data' => '/post_'.$next];
        }

        $text = sprintf("%d *%s*\n%s", $index, $post->getName(), $post->getDescription());

        if ($messageId) {
            $api->editMessageText(
                $chatId,
                $messageId,
                $text,
                'markdown',
                false,
                new InlineKeyboardMarkup([$buttons])
            );
        } else {
            $api->sendMessage(
                $chatId,
                $text,
                'markdown',
                false,
                null,
                new InlineKeyboardMarkup([$buttons])
            );
        }
    }
}
