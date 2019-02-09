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

    const SEPARATOR = '__'; // разделитель ид сайта, номера пресета и имени инпута в полном наименовании
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

### `protected $disabled = false` 
Атрибут `disabled`

## Методы
Раздел в разработке