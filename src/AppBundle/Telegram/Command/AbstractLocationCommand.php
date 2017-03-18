<?php
/**
 * User: boshurik
 * Date: 18.03.17
 * Time: 21:34
 */

namespace AppBundle\Telegram\Command;

use AppBundle\Telegram\LocationHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

abstract class AbstractLocationCommand extends AbstractCommand implements LocationCommandInterface
{
    /**
     * @var LocationHandler
     */
    private $locationCommandManager;

    public function __construct(LocationHandler $locationHandler)
    {
        $this->locationCommandManager = $locationHandler;
    }

    /**
     * @inheritDoc
     */
    public function execute(BotApi $api, Message $message)
    {
        $this->locationCommandManager->setLocationCommand($message->getChat()->getId(), $this->getId());
        $api->sendMessage($message->getChat()->getId(), $this->getMessage());
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    protected function getMessage()
    {
        return "Пожалуйста, отправьте Ваше местоположение:\n• Нажмите \xF0\x9F\x93\x8E\n• Выберите \"Location\"\n• Нажмите \"Send my current location\"";
    }
}