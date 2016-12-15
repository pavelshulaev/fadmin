<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 15.12.2016
 * Time: 0:12
 *
 * @author Pavel Shulaev (http://rover-it.me)
 */

namespace Rover\Fadmin\Inputs;

use Bitrix\Bizproc\BaseType\Date;
use Bitrix\Main\Type\DateTime;
use Rover\Fadmin\Tab;
use Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Event;

Loc::loadMessages(__FILE__);

/**
 * Class Schedule
 *
 * @package Rover\Fadmin\Inputs
 * @author  Pavel Shulaev (http://rover-it.me)
 */
class Schedule extends Input
{
	/**
	 * @var string
	 */
	public static $type = self::TYPE__SCHEDULE;

	protected static $assetsAdded = false;

	/**
	 * @var string
	 */
	protected $periodLabel;

	/**
	 * default height
	 * @var int
	 */
	protected $height = 300;

	/**
	 * default width
	 * @var int
	 */
	protected $width = 500;

	/**
	 * @param array $params
	 * @param Tab   $tab
	 * @throws \Bitrix\Main\ArgumentNullException
	 */
	public function __construct(array $params, Tab $tab)
	{
		// for automatic serialize/unserialize
		$params['multiple'] = true;

		parent::__construct($params, $tab);

		$this->periodLabel = isset($params['periodLabel'])
			? $params['periodLabel']
			: Loc::getMessage('rover-fa__schedule-default-period');

		if (isset($params['width']) && intval($params['width']))
			$this->width = $params['width'];

		if (isset($params['height']) && intval($params['height']))
			$this->height = $params['height'];

		$this->addEventHandler(self::EVENT__BEFORE_SAVE_REQUEST, [$this, 'beforeSaveRequest']);
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function addAssets()
	{
		\CJSCore::Init(array("jquery"));

		$asset = \Bitrix\Main\Page\Asset::getInstance();

		//add css
		if (self::$assetsAdded)
			return;

		echo $asset->insertCss('/bitrix/css/rover.fadmin/vendor/jqwidgets/jqx.base.css');

		$jsPath = '/bitrix/js/rover.fadmin/vendor/jqwidgets';

		$asset->addJs($jsPath . '/jqxcore.js');
		$asset->addJs($jsPath . '/jqxbuttons.js');
		$asset->addJs($jsPath . '/jqxscrollbar.js');
		$asset->addJs($jsPath . '/jqxdata.js');
		$asset->addJs($jsPath . '/jqxdata.export.js');
		$asset->addJs($jsPath . '/jqxdate.js');
		$asset->addJs($jsPath . '/jqxscheduler.js');
		$asset->addJs($jsPath . '/jqxscheduler.api.js');
		$asset->addJs($jsPath . '/jqxdatetimeinput.js');
		$asset->addJs($jsPath . '/jqxmenu.js');
		$asset->addJs($jsPath . '/jqxcalendar.js');
		$asset->addJs($jsPath . '/jqxtooltip.js');
		$asset->addJs($jsPath . '/jqxwindow.js');
		$asset->addJs($jsPath . '/jqxcheckbox.js');
		$asset->addJs($jsPath . '/jqxlistbox.js');
		$asset->addJs($jsPath . '/jqxdropdownlist.js');
		$asset->addJs($jsPath . '/jqxnumberinput.js');
		$asset->addJs($jsPath . '/jqxradiobutton.js');
		$asset->addJs($jsPath . '/jqxinput.js');
		$asset->addJs($jsPath . '/globalization/globalize.js');
		$asset->addJs($jsPath . '/globalization/globalize.culture.ru-RU.js');

		self::$assetsAdded = true;
	}

	/**
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function draw()
	{
		$this->addAssets();

		$valueId    = $this->getValueId();
		$valueName  = $this->getValueName();

		$this->showLabel($valueId);

		?><input type="hidden"
		         id="<?=$valueId?>"
		         value="<?=$this->value?>"
		         name="<?=$valueName?>">
		<div id="scheduler-<?=$valueId?>"></div>
		<style>
			.jqx-scheduler-all-day-cell span{
				display: none;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function () {
				var appointments = [];

				// prepare the data
				var source =
				{
					dataType: "array",
					dataFields: [
						{ name: 'id', type: 'string' },
						{ name: 'subject', type: 'string' },
						{ name: 'calendar', type: 'string' },
						{ name: 'start', type: 'date' },
						{ name: 'end', type: 'date' }
					],
					id: 'id',
					localData: appointments
				};
				var adapter = new $.jqx.dataAdapter(source),
					$scheduler = $("#scheduler-<?=$valueId?>");

				$scheduler.jqxScheduler({
					date: new $.jqx.date(2016, 11, 23),
					width: <?=$this->width?>,
					height: <?=$this->height?>,
					rowsHeight: 15,
					columnsHeight: 30,
					source: adapter,
					view: 'weekView',
					enableHover: false,
					exportSettings: {
						serverURL: null,
						characterSet: null,
						fileName: null,
						dateTimeFormatString: "S",
						resourcesInMultipleICSFiles: true
					},
					showToolbar: false,
					resources:
					{
						dataField: "calendar",
						source:  adapter
					},
					appointmentDataFields:
					{
						from: "start",
						to: "end",
						id: "id",
						subject: "subject",
						resourceId: "calendar"
					},
					localization: {
						firstDay: 1,
						days: {
							// full day names
							names: [
								"<?=Loc::getMessage('rover-fa__schedule-sunday')?>",
								"<?=Loc::getMessage('rover-fa__schedule-monday')?>",
								"<?=Loc::getMessage('rover-fa__schedule-tuesday')?>",
								"<?=Loc::getMessage('rover-fa__schedule-wednesday')?>",
								"<?=Loc::getMessage('rover-fa__schedule-thursday')?>",
								"<?=Loc::getMessage('rover-fa__schedule-friday')?>",
								"<?=Loc::getMessage('rover-fa__schedule-saturday')?>"],
							// abbreviated day names
							//namesAbbr: ["Sonn", "Mon", "Dien", "Mitt", "Donn", "Fre", "Sams"],
							// shortest day names
							//namesShort: ["So", "Mo", "Di", "Mi", "Do", "Fr", "Sa"]
						},
					},
					editDialogOpen: function (dialog, fields, editAppointment) {
						fields.locationContainer.hide();
						fields.repeatContainer.hide();
						fields.subject.val("<?=$this->periodLabel?>");
						fields.subjectContainer.hide();
						fields.statusContainer.hide();
						fields.timeZoneContainer.hide();
						fields.colorContainer.hide();
						fields.descriptionContainer.hide();
						fields.resourceContainer.hide();
					},
					views:
						[
							{
								type: 'weekView',
								workTime:
								{
									fromDayOfWeek: 1,
									toDayOfWeek: 0,
									fromHour: 1,
									toHour: 0
								},
								timeRuler:
								{
									formatString: "HH:mm",
									scale: 'hour'
								}
							}
						]
				});

				var $export = $('#<?=$valueId?>');

				$scheduler.on('appointmentChange appointmentDelete appointmentAdd', function (event) {

					event.args.appointment.subject = "<?=$this->periodLabel?>";

					var timeout = event.type == "appointmentChange"
						? 100
						: 200;
					console.log(event);

					setTimeout(function(){
						exportPeriods();
					}, timeout);
				});

				/**
				 *
				 */
				function exportPeriods()
				{
					var schedule = JSON.parse($scheduler.jqxScheduler('exportData', 'json')),
						propNum, period, result = [];

					for (propNum in schedule)
					{
						period = schedule[propNum];

						delete period.id;
						delete period.calendar;
						delete period.subject;

						//period.start = period.start.getTime();
						//period.end   = period.end.getTime();
console.log(period);
						result.push(period);
					}


					console.log(result);
					$export.val(JSON.stringify(result));
				}
			});

		</script>

		<?php

		$this->showHelp();
	}


	/**
	 * @param Event $event
	 * @return \Bitrix\Main\EventResult
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function beforeSaveRequest(Event $event)
	{
		if ($event->getSender() !== $this)
			return $this->getEvent()->getErrorResult($this);

		$value = $event->getParameter('value');

		$result = [];

		$periods = json_decode($value, true);
		if (!is_array($periods)){
			$value = [];
			return $this->getEvent()->getSuccessResult($this, compact('value'));
		}

		foreach ($periods as $period)
		{
			$period['start'] = $this->createTimestamp($period['start']);
			$period['end'] = $this->createTimestamp($period['end']);

			if (intval($period['start']) && intval($period['end']))
				$result[] = $period;
		}

		$result = $this->pasteTogetherPeriods($result);

	}

	/**
	 * @param $periods
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function pasteTogetherPeriods($periods)
	{
		do {
			$result = [];
			$pastedTogether = false;

			foreach ($periods as $periodNum => $period){

				// first value
				if (!count($result)) {
					$result[] = $period;
					continue;
				}

				$periodInResult = false;

				foreach ($result as &$resultPeriod){

					if (($period['start'] >= $resultPeriod['start'])
						&& ($period['start'] <= $resultPeriod['end']))
					{
						if ($period['end'] > $resultPeriod['end']){
							$resultPeriod['end'] = $period['end'];
							$pastedTogether = true;
						}

						$periodInResult = true;
						break;
					}

					if (($period['end'] <= $resultPeriod['end'])
						&& ($period['end'] >= $resultPeriod['start']))
					{
						if ($period['start'] < $resultPeriod['start']){
							$resultPeriod['start'] = $period['start'];
							$pastedTogether = true;
						}

						$periodInResult = true;
						break;
					}
				}

				if (!$periodInResult)
					$result[] = $period;
			}

			$periods = $result;

		} while ($pastedTogether);

		return $result;
	}

	/**
	 * @param $time
	 * @return int|null
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function createTimestamp($time)
	{
		$dateTime = \DateTime::createFromFormat('Y-m-d\TH:i:s', $time);

		if ($dateTime instanceof \DateTime)
			return $dateTime->getTimestamp();

		return null;
	}
}