# События
Для более гибкой работы с настройками в «Конструкторе» предусмотрена система событий. Базовые названия всех событий определены в константах класса `\Rover\Fadmin\Options\Event`. Их описание можно найти в [api по \Rover\Fadmin\Options\Event](./api/options/event.md#Константы).

Для каждого модуля, использующего «Конструктор», генерируются типы событий вида `'ИдМодуляНужноеСобыте'`. Если ваш модуль имеет id `'partner.module'` и необходимо обработать событие `afterMakePresetTab`, то обрабтчик нужно вешать на событие `PartnerModuleAfterMakePresetTab`:

    $eventManager = \Bitrix\Main\EventManager::getInstance();
    $eventManager->addEventHandler('partner.module', 'PartnerModuleAfterMakePresetTab', array('\Partner\Module\Event', 'onAfterMakePresetTab'));

Сам обработчик имеет стандартный вид:

    namespace Partner\Module;
    
    class Event
    {
        public static function onAfterMakePresetTab(EventMain $event)
        {
            $parameters = $event->getParameters();
           
            ...
    
            return new EventResult($event->getEventType(), $parameters, 'partner.module');
        }
    }    
    
Подробнее про [события](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3113&LESSON_PATH=3913.5062.3113) и их [обработку](https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2244#events) в ядре d7 (офф. документация).

> Если событие, начинающееся с `'before'`, вернет `false`, то действие, обычно совершаемое после этого события, выполнено не будет. Это не относится к `'beforeGetTabInfo'`. Оно позволяет только изменить информацию о вкладке, но не отменить ее отображение.


## Старый стиль
Ранее события можно было обрабатывать, определяя соответствующий метод классе, унаследованном от `\Rover\Fadmin\Options`. На данный момент эта система событий считается устаревшей и больше не поддерживается.
