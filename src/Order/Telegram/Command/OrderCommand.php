<?php

/*
 * This file is part of the boshurik-bot-example.
 *
 * (c) Alexander Borisov <boshurik@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Order\Telegram\Command;

use App\Order\Event\OrderEvent;
use App\Order\Model\Order;
use App\Order\Telegram\OrderHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;
use TelegramBot\Api\Types\Update;

class OrderCommand extends AbstractCommand implements PublicCommandInterface
{
    /**
     * @var OrderHandler
     */
    private $orderHandler;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(OrderHandler $orderHandler, ValidatorInterface $validator, EventDispatcherInterface $eventDispatcher)
    {
        $this->orderHandler = $orderHandler;
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return '/order';
    }

    /**
     * @inheritDoc
     */
    public function getDescription()
    {
        return 'Send order';
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Update $update)
    {
        $id = (string) $update->getMessage()->getChat()->getId();

        if ($this->isCancelStep($update)) {
            $this->cancelStep($api, $update->getMessage(), $id);

            return;
        }

        if (parent::isApplicable($update)) {
            $step = 0;
            $order = $this->orderHandler->createOrder();
        } else {
            $step = $this->orderHandler->getCurrentStep($id);
            $order = $this->orderHandler->getOrder($id);
        }

        $method = sprintf('step%d', $step);
        $nextMethod = sprintf('step%d', $step + 1);

        $result = $this->$method($api, $update->getMessage(), $id, $order);
        if (!$result) {
            return;
        }

        if (method_exists($this, $nextMethod)) {
            $this->orderHandler->setOrder($id, $order);
            $this->orderHandler->setCurrentStep($id, $step + 1);
        } else {
            $this->finalStep($api, $update->getMessage(), $id, $order);
            $this->orderHandler->clearData($id);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(Update $update)
    {
        if ($result = parent::isApplicable($update)) {
            return true;
        }
        if (!$update->getMessage()) {
            return false;
        }

        return $this->orderHandler->hasData((string) $update->getMessage()->getChat()->getId());
    }

    protected function step0(BotApi $api, Message $message, string $chatId, Order $order): bool
    {
        $api->sendMessage($chatId, 'To cancel type "/order cancel"');
        $api->sendMessage($chatId, 'Enter your name');

        return true;
    }

    protected function step1(BotApi $api, Message $message, string $chatId, Order $order): bool
    {
        $order->setName($message->getText());

        $violations = $this->validateOrder($order, __FUNCTION__);
        if ($violations->count() > 0) {
            $this->sendErrorMessage($chatId, $api, $violations);

            return false;
        }

        $api->sendMessage($chatId, 'Enter your phone');

        return true;
    }

    protected function step2(BotApi $api, Message $message, string $chatId, Order $order): bool
    {
        $order->setPhone($message->getText());

        $violations = $this->validateOrder($order, __FUNCTION__);
        if ($violations->count() > 0) {
            $this->sendErrorMessage($chatId, $api, $violations);

            return false;
        }

        $api->sendMessage($chatId, 'Enter your email');

        return true;
    }

    protected function step3(BotApi $api, Message $message, string $chatId, Order $order): bool
    {
        $order->setEmail($message->getText());

        $violations = $this->validateOrder($order, __FUNCTION__);
        if ($violations->count() > 0) {
            $this->sendErrorMessage($chatId, $api, $violations);

            return false;
        }

        $api->sendMessage($chatId, 'Enter message');

        return true;
    }

    protected function step4(BotApi $api, Message $message, string $chatId, Order $order): bool
    {
        $order->setMessage($message->getText());

        $violations = $this->validateOrder($order, __FUNCTION__);
        if ($violations->count() > 0) {
            $this->sendErrorMessage($chatId, $api, $violations);

            return false;
        }

        return true;
    }

    protected function finalStep(BotApi $api, Message $message, string $chatId, Order $order): void
    {
        $this->eventDispatcher->dispatch(new OrderEvent($order));
        $api->sendMessage($chatId, 'Thank you!');
    }

    protected function cancelStep(BotApi $api, Message $message, string $chatId): void
    {
        $this->orderHandler->clearData($chatId);
    }

    protected function validateOrder(Order $order, string $group): ConstraintViolationListInterface
    {
        return $this->validator->validate($order, null, [$group]);
    }

    protected function sendErrorMessage(string $chatId, BotApi $api, ConstraintViolationListInterface $violations): void
    {
        $messages = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $messages[] = sprintf('%s - %s', $violation->getInvalidValue(), (string) $violation->getMessage());
        }
        $api->sendMessage($chatId, implode("\n", $messages));
    }

    protected function isCancelStep(Update $update): bool
    {
        if (!parent::isApplicable($update)) {
            return false;
        }

        preg_match(self::REGEXP, $update->getMessage()->getText(), $matches);

        return 'cancel' == mb_strtolower($matches[3]);
    }
}
