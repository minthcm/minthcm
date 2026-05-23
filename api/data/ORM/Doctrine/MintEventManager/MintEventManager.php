<?php 

namespace MintHCM\Data\ORM\Doctrine\MintEventManager;

use Doctrine\Common\EventManager;
use Doctrine\Common\EventSubscriber;

class MintEventManager
{
    const SUBSCRIBERS_LOCALIZATIONS = [
        'MintHCM\\Data\\ORM\\Doctrine\\MintEventManager\\Subscribers\\' => 'data/ORM/Doctrine/MintEventManager/Subscribers/',
        'MintHCM\\Custom\\Data\\ORM\\Doctrine\\MintEventManager\\Subscribers\\' => 'custom/data/ORM/Doctrine/MintEventManager/Subscribers/',
    ];

    public static function getEventManager(): EventManager
    {
        $event_manager = new EventManager();

        $subscribers = self::getSubscribers();
        foreach ($subscribers as $subscriber) {
            $event_manager->addEventSubscriber($subscriber);
        }

        return $event_manager;
    }

    protected static function getSubscribers(): array
    {
        $subscribers = [];
        foreach (self::SUBSCRIBERS_LOCALIZATIONS as $namespace => $dir) {
            if (is_dir($dir)) {
                $subscriber_classes = self::getClassesFromDir($dir);
                foreach ($subscriber_classes as $class) {
                    $full_class_name = $namespace . $class;
                    if (!class_exists($full_class_name)) {
                        continue;
                    }
                    $subscriber_instance = new $full_class_name();
                    if ($subscriber_instance instanceof EventSubscriber) {
                        $subscribers[] = $subscriber_instance;
                    }
                }
            }
        }
        return $subscribers;
    }

     /** @return string[] */

    protected static function getClassesFromDir(string $dir): array
    {
        $classes = [];
        $files = scandir($dir);
        foreach ($files as $file) {
            if (is_file($dir . '/' . $file) && substr($file, -4) === '.php') {
                $classes[] = substr($file, 0, -4);
            }
        }
        return $classes;
    }
}