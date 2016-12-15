#Получение значений опций
При сохранении значений в административной части, значения инпутов записываются в опции модуля. Для получения этих значений в любой части сайта доступны 2 метода:
* `getNormalValue($inputName, $siteId = '', $reload = false)` - для получения значения обычной опции
* `getPresetValue($inputName, $presetId, $siteId = '', $reload = false)` - для поличения значения опции из пресета

Аргументы:
* `$inputName` – имя инпута (равное атрибуту `name` инпута)
* `$presetId` – номер пресета
* `$siteId` – идентификатор сайта
* `$reload` – при первом обращении значение опции попадает в кеш и при следующих обращениях по умолчанию берется из него. Если аргумент равен true, то значение повторно берется из базы.

На практике бывает удобно сделать обёртку над этими методами, например, как это сделано в демо-классе. Для получения значения обычной опции:
	
	public function getTextareaValueS1($reload = false)
	{
	    return $this->getNormalValue('input_textarea', 's1', $reload);
	}
Для получения значения опции из пресета:

	public function getS1PresetColor($presetId, $reload = false)
	{
	    return $this->getPresetValue('preset_color', $presetId, 's1', $reload);
	}