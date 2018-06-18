# Получение значений опций
Для получения значений, сохранённых в административной части модуля, подключенного к «Констуктору», доступны 2 метода:
* `getNormalValue($inputName, $siteId = '', $reload = false)` - для получения значения из обычной вкладки
* `getPresetValue($inputName, $presetId, $siteId = '', $reload = false)` - для получения значения из вкладки пресета

Аргументы:
* `$inputName` – имя инпута (должно соотвествовать атрибуту `name` инпута)
* `$presetId` – id пресета
* `$siteId` – идентификатор сайта, необязательное
* `$reload` – флаг: брать значение из кеша или напрямую из базы.

## Лучшие практики
Часто бывает удобно сделать обёртку над этими методами, например, как это сделано в демо-классе. 

Для получения значения опции обычной вкладки:
	
	public function getTextareaValueS1($reload = false)
	{
	    return $this->getNormalValue('input_textarea', 's1', $reload);
	}
Для получения значения опции вкладки пресета:

	public function getS1PresetColor($presetId, $reload = false)
	{
	    return $this->getPresetValue('preset_color', $presetId, 's1', $reload);
	}