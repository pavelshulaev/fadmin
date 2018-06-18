# Описание класса \Rover\Fadmin\Inputs\Input
Абстрактный класс, реализующий общие методы и логику работы для всех инпутов решения.

Все инпуты унаследованы от него:
* \Rover\Fadmin\Inputs\Addpreset
* \Rover\Fadmin\Inputs\Checkbox
* \Rover\Fadmin\Inputs\Clock
* \Rover\Fadmin\Inputs\Color
* \Rover\Fadmin\Inputs\Custom
* \Rover\Fadmin\Inputs\Date
* \Rover\Fadmin\Inputs\Datetime
* \Rover\Fadmin\Inputs\File
* \Rover\Fadmin\Inputs\Header
* \Rover\Fadmin\Inputs\Hidden
* \Rover\Fadmin\Inputs\Iblock
* \Rover\Fadmin\Inputs\Label
* \Rover\Fadmin\Inputs\Number
* \Rover\Fadmin\Inputs\Presetname
* \Rover\Fadmin\Inputs\Radio
* \Rover\Fadmin\Inputs\Removepreset
* \Rover\Fadmin\Inputs\Schedule
* \Rover\Fadmin\Inputs\Selectbox
* \Rover\Fadmin\Inputs\Selectgroup
* \Rover\Fadmin\Inputs\Submit
* \Rover\Fadmin\Inputs\Subtabcontrol
* \Rover\Fadmin\Inputs\Subtab
* \Rover\Fadmin\Inputs\Text
* \Rover\Fadmin\Inputs\Textarea

* [Константы](#Константы)
* [Поля](#Поля)
* [Методы](#Методы)

## Константы
Класс содержит константы, определяющие типы поддерживаемых инпутов

    const TYPE__ADD_PRESET      = 'addpreset';
	const TYPE__CHECKBOX        = 'checkbox';
    const TYPE__CLOCK           = 'clock';
	const TYPE__COLOR           = 'color';
    const TYPE__CUSTOM          = 'custom';
    const TYPE__DATE            = 'date';
	const TYPE__DATETIME        = 'datetime';
    const TYPE__FILE            = 'file';
	const TYPE__HEADER          = 'header';
    const TYPE__HIDDEN          = 'hidden';
    const TYPE__IBLOCK          = 'iblock';
    const TYPE__LABEL           = 'label';
    const TYPE__NUMBER          = 'number';
    const TYPE__PRESET_NAME     = 'presetname';
    const TYPE__RADIO           = 'radio';
    const TYPE__REMOVE_PRESET   = 'removepreset';
    const TYPE__SELECTBOX       = 'selectbox';
    const TYPE__SELECT_GROUP    = 'selectgroup';
    const TYPE__SCHEDULE        = 'schedule';
    const TYPE__SUBMIT          = 'submit';
    const TYPE__SUBTABCONTROL   = 'subtabcontrol';
    const TYPE__SUBTAB          = 'subtab';
    const TYPE__TEXT            = 'text';
	const TYPE__TEXTAREA        = 'textarea';

## Поля
### `public static $type`
Содержит тип инпута. Тип должен соотвествовать одной из констант, описанной выше

### `protected $id`
Параметр `id` инпута

### `protected $name`
Уникальное имя инпута

### `protected $label`
Подпись инпута

### `protected $value`
Значение инпута

### `protected $default`
Значение по-умолчанию

### `protected $multiple = false` 
Флаг множественности значений

### `protected $help` 
Вспомогатеьная информация

### `protected $tab` 
Ссылка на вкладку, на которой находится инпут

### `protected $sort = 500` 
"Вес" для сортировки

### `protected $hidden = false` 
Флаг скрытости инпута. Если установлен, то инпут не выводится

### `protected $disabled = 500` 
Атрибут `disabled`

## Методы
Раздел в разработке