<?php

namespace App\Telegram\Location\Command;

use App\Telegram\Location\LocationHandler;
use BoShurik\TelegramBotBundle\Telegram\Command\AbstractCommand;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Update;

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
    public function execute(BotApi $api, Update $update)
    {
        $this->locationCommandManager->setLocationCommand($update->getMessage()->getChat()->getId(), $this->getId());
        $api->sendMessage($update->getMessage()->getChat()->getId(), $this->getMessage());
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