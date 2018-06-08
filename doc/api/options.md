# Описание класса `\Rover\Fadmin\Options`
* [Поля](#Поля)
* [Методы](#Методы)

## Поля
### `protected $moduleId`
Идентификатор текущего модуля
### `protected \Rover\Fadmin\Engine\TabMap $tabMap`  
Объект менеджера табов
### `protected \Rover\Fadmin\Engine\Message $message` 
Объект менеджера административных сообщений
### `protected $cache = []`
Кеш значений опций модуля
### `protected \Rover\Fadmin\Engine\Settings $settings`
Объект менеджера настроек fadmin
### `protected static $instances = []`
Сущности Fadmin-а для каждого из использующих модулей
## Методы
### `public static getInstance($moduleId)`
Возвращает объект `\Rover\Fadmin\Options` для модуля с идентификатором `$moduleId`
### `protected __construct($moduleId)`
Создает объект `\Rover\Fadmin\Options` для модуля с идентификатором `$moduleId`
### `public runEvent($name, &$params = [])` (устарел)
Запускает внутреннее событие `\Rover\Fadmin\Options`.
* `$name` - имя события
* `$params` - параметры, передаваемые в событие

### `public getPresetsCount($siteId = '')`
Возвращает кол-во существующих пресетов. Если для модуля настроена многосайтовость, то по `$siteId` - кол-во пресетов для данного сайта.
### `public getAllTabsInfo()` 
Возвращает информацию (имя в системе, имя вкладки, описание, иконка) для всех существующих табов
### `abstract public getConfig()`
Метод, возвращающий конфигурацию. Должен быть переопределен в каждом потомке.
### `public getModuleId()`
Возвращает идентификатор модуля, для которого создан объект `\Rover\Fadmin\Options`
### `public static getFullName($name, $presetId = '', $siteId = '')`
Возвращает полное внутреннее имя сущности, включающее номер пресета и сайт (если имеются)
### `public getPresetValue($inputName, $presetId, $siteId = '', $reload = false)`
Возвращает значение опции пресета

> Описанный список методов может быть не полным, т.к. решение постоянно совершенствуется. Для актуальной информации смотрите код файла `\lib\options.php`.
