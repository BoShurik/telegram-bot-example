<?php
/**
 * User: boshurik
 * Date: 19.03.17
 * Time: 16:34
 */

namespace AppBundle\Telegram\Command;

use AppBundle\Event\Order\OrderEvent;
use AppBundle\Event\OrderEvents;
use AppBundle\Model\Order;
use AppBundle\Telegram\OrderHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use BoShurik\TelegramBotBundle\Telegram\Command\PublicCommandInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

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
    public function execute(BotApi $api, Message $message)
    {
        $id = $message->getChat()->getId();

        if ($this->isCancelStep($message)) {
            $this->cancelStep($api, $message, $id);

            return;
        }

        if (parent::isApplicable($message)) {
            $step = 0;
            $order = $this->orderHandler->createOrder();
        } else {
            $step = $this->orderHandler->getCurrentStep($id);
            $order = $this->orderHandler->getOrder($id);
        }

        $method = sprintf('step%d', $step);
        $nextMethod = sprintf('step%d', $step + 1);

        $result = $this->$method($api, $message, $id, $order);
        if (!$result) {
            return;
        }

        if (method_exists($this, $nextMethod)) {
            $this->orderHandler->setOrder($id, $order);
            $this->orderHandler->setCurrentStep($id, $step + 1);
        } else {
            $this->finalStep($api, $message, $id, $order);
            $this->orderHandler->clearData($id);
        }
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(Message $message)
    {
        if ($result = parent::isApplicable($message)) {
            return true;
        }

        return $this->orderHandler->hasData($message->getChat()->getId());
    }

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     * @param Order $order
     * @return bool
     */
    protected function step0(BotApi $api, Message $message, $chatId, Order $order)
    {
        $api->sendMessage($chatId, 'To cancel type "/order cancel"');
        $api->sendMessage($chatId, 'Enter your name');

        return true;
    }

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     * @param Order $order
     * @return bool
     */
    protected function step1(BotApi $api, Message $message, $chatId, Order $order)
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

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     * @param Order $order
     * @return bool
     */
    protected function step2(BotApi $api, Message $message, $chatId, Order $order)
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

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     * @param Order $order
     * @return bool
     */
    protected function step3(BotApi $api, Message $message, $chatId, Order $order)
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

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     * @param Order $order
     * @return bool
     */
    protected function step4(BotApi $api, Message $message, $chatId, Order $order)
    {
        $order->setMessage($message->getText());

        $violations = $this->validateOrder($order, __FUNCTION__);
        if ($violations->count() > 0) {
            $this->sendErrorMessage($chatId, $api, $violations);

            return false;
        }

        return true;
    }

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     * @param Order $order
     */
    protected function finalStep(BotApi $api, Message $message, $chatId, Order $order)
    {
        $this->eventDispatcher->dispatch(OrderEvents::SUBMIT, new OrderEvent($order));
        $api->sendMessage($chatId, 'Thank you!');
    }

    /**
     * @param BotApi $api
     * @param Message $message
     * @param string $chatId
     */
    protected function cancelStep(BotApi $api, Message $message, $chatId)
    {
        $this->orderHandler->clearData($chatId);
    }

    /**
     * @param Order $order
     * @param string $group
     * @return ConstraintViolationListInterface
     */
    protected function validateOrder(Order $order, $group)
    {
        return $this->validator->validate($order, null, [$group]);
    }

    /**
     * @param string $chatId
     * @param BotApi $api
     * @param ConstraintViolationListInterface $violations
     */
    protected function sendErrorMessage($chatId, BotApi $api, ConstraintViolationListInterface $violations)
    {
        $messages = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $messages[] = sprintf('%s - %s', $violation->getInvalidValue(), $violation->getMessage());
        }
        $api->sendMessage($chatId, implode("\n", $messages));
    }

    /**
     * @param Message $message
     * @return bool
     */
    protected function isCancelStep(Message $message)
    {
        if (!parent::isApplicable($message)) {
            return false;
        }

        preg_match(self::REGEXP, $message->getText(), $matches);

        return 'cancel' == mb_strtolower($matches[3]);
    }
}