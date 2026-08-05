# Описание класса `\Rover\Fadmin\Options`
* [Поля](#Поля)
* [Методы](#Методы)
* [Магические геттеры опций](#Магические-геттеры-опций)

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

## Магические геттеры опций
Помимо описанных выше методов, `Options::__call()` позволяет получать значение любой константы вида `const OPTION__XXX = '...'`, объявленной в вашем классе-наследнике, через вызов несуществующего метода `getXxx(...)`. Имя метода собирается из имени константы без префикса `OPTION__`, переведённого в camelCase, например:
```php
const OPTION__SOME_TEXT = 'some_text';
```
даёт доступ к значению опции двумя способами:
* `getSomeText(string $siteId = '', bool $reload = false)` — обычное значение опции;
* `getPresetSomeText(string $presetId, string $siteId = '', bool $reload = false)` — значение опции в рамках пресета (обратите внимание на префикс `Preset` в имени метода).

Так как эти методы не объявлены явно в коде, IDE и статические анализаторы (PhpStorm, PHPStan, Psalm) не видят их и не подсказывают автодополнение — опечатка в имени метода обнаружится только в рантайме исключением `SystemException`. Чтобы это исправить, добавьте в докблок своего класса-наследника `Options` аннотации `@method` для каждой своей константы:
```php
/**
 * @method mixed getSomeText(string $siteId = '', bool $reload = false)
 * @method mixed getPresetSomeText(string $presetId, string $siteId = '', bool $reload = false)
 */
class MyOptions extends \Rover\Fadmin\Options
{
    const OPTION__SOME_TEXT = 'some_text';
    // ...
}
```
Эти аннотации ни на что не влияют в рантайме — это чисто подсказка для IDE/анализатора. Автоматически сгенерировать их из констант нельзя (PHPDoc не умеет читать значения во время выполнения), поэтому их нужно поддерживать вручную при добавлении/переименовании констант. Пример полного набора аннотаций — в докблоке класса `\Rover\Fadmin\TestOptions` (`lib/testoptions.php`).

**Важный нюанс:** если имя константы начинается со слова `PRESET_` (например, `OPTION__PRESET_COLOR`), магический геттер для неё недоступен — префикс `Preset` в имени метода `__call()` использует для того, чтобы понять, что нужно вызвать `getPresetValue()`, и вырезает его из имени перед поиском константы. Для таких констант нужно объявить обычный публичный метод-обёртку, вызывающий `getNormalValue()`/`getPresetValue()` напрямую (см. `TestOptions::getS1PresetColor()`).
