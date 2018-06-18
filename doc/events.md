# События
Для более гибкой работы с настройками в «Конструкторе» предусмотрена система событий. Типы всех событий определены в константах класса `\Rover\Fadmin\Options\Event`. Их описание можно найти в [api по \Rover\Fadmin\Options\Event](./api/options/event.md#Константы).

Эти типы генерируются каждым модулем, использующим «Конструктор». Пример обработчика для модуля `'partner.module'` и события `afterMakePresetTab`:

    $eventManager = \Bitrix\Main\EventManager::getInstance();
    $eventManager->addEventHandler('partner.module', 'afterMakePresetTab', array('\Partner\Module\Event', 'onAfterMakePresetTab'));

Сам обработчик имеет стандартный вид:

    namespace Partner\Module;
    
    class Event
    {
        public static function onAfterMakePresetTab(Bitrix\Main\Event $event)
        {
            $parameters = $event->getParameters();
            $tab        = $event->getParameter('tab');
           
            ...
    
            $parameters['tab'] = $tab;
    
            return new Bitrix\Main\EventResult(Bitrix\Main\EventResult::SUCCESS, $parameters, 'partner.module');
        }
    }    
    
Подробнее про [события](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3113&LESSON_PATH=3913.5062.3113) и их [обработку](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2244#events) в ядре d7 (офф. документация).

> Если событие, начинающееся с `'before'`, вернет реузьтат типа `Bitrix\Main\EventResult::ERROR`, то действие, обычно совершаемое после этого события, выполнено не будет. Это не относится к `'beforeGetTabInfo'`. Оно позволяет только изменить информацию о вкладке, но не отменить ее отображение. Для отмены отображения таба можно воспользоваться событием `beforeShowTab`.


## Старый стиль
Ранее события можно было обрабатывать, определяя соответствующий метод классе, унаследованном от `\Rover\Fadmin\Options`. На данный момент эта система событий считается устаревшей и больше не поддерживается.
