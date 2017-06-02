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

	/**
	 * @var bool
	 */
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

	protected $inputValue = [];

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
		$this->addEventHandler(self::EVENT__AFTER_LOAD_VALUE, [$this, 'afterLoadValue']);
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
		         value='<?=json_encode($this->inputValue)?>'
		         name="<?=$valueName?>">
		<div id="scheduler-<?=$valueId?>"></div>
		<style>
			.jqx-scheduler-all-day-cell span{
				display: none;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function () {
				var appointments = [
					<?php

					$num = 1;

					foreach ($this->value as $period):	?>{
						id: "<?=$valueId?>-<?=$num?>",
						subject: "<?=$this->periodLabel?>",
						calendar: "1",
						start: new Date(<?=$period['start']->format('Y, ' . $period['jqwStartMonth'] .', d, H, i, s')?>),
						end: new Date(<?=$period['end']->format('Y, ' . $period['jqwEndMonth'] .', d, H, i, s')?>)
					},
					<?php

					$num++;

					endforeach; ?>
				];

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
					$scheduler = $("#scheduler-<?=$valueId?>"),
					$export = $('#<?=$valueId?>');

				$scheduler.jqxScheduler({
					//date: new $.jqx.date(),
					date: new $.jqx.date('todayDate'),
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
						editDialogFromString: "<?=Loc::getMessage('rover-fa__schedule-start')?>",
						editDialogToString: "<?=Loc::getMessage('rover-fa__schedule-end')?>",
						editDialogAllDayString: "<?=Loc::getMessage('rover-fa__schedule-all-day')?>",
						editDialogTitleString: "<?=Loc::getMessage('rover-fa__schedule-edit-period')?>",
						contextMenuEditAppointmentString: "<?=Loc::getMessage('rover-fa__schedule-edit-period')?>",
						editDialogCreateTitleString: "<?=Loc::getMessage('rover-fa__schedule-create-period')?>",
						contextMenuCreateAppointmentString: "<?=Loc::getMessage('rover-fa__schedule-create-period')?>",
						editDialogSaveString: "<?=Loc::getMessage('rover-fa__schedule-save')?>",
						editDialogDeleteString: "<?=Loc::getMessage('rover-fa__schedule-delete')?>",
						editDialogCancelString: "<?=Loc::getMessage('rover-fa__schedule-cancel')?>",
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
									fromDayOfWeek: 0,
									toDayOfWeek: 6,
									fromHour: -1,
									toHour: 24
								},
								timeRuler:
								{
									formatString: "HH:mm",
									scale: 'hour'
								}
							}
						]
				});

				$scheduler.on('appointmentChange appointmentDelete appointmentAdd', function (event) {
					var timeout = event.type == "appointmentChange"
						? 100
						: 200;

					setTimeout(function(){
						exportPeriods();
					}, timeout);
				});

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

						result.push(period);
					}
//console.log(result);
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

		$value      = $event->getParameter('value');
		$periods    = json_decode($value, true);

		if (is_array($periods)){

			$value = $this->preparePeriodsDates($periods);
			$value = $this->pastePeriodsTogether($value);
			$value = $this->markWeekDays($value);

		} else
			$value = [];

		return $this->getEvent()->getSuccessResult($this, compact('value'));
	}

	/**
	 * @param $periods
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function markWeekDays($periods)
	{
		$result = [];

		foreach ($periods as $period)
		{
			$dateStart  = (new \DateTime())->setTimestamp($period['start']);
			$dateEnd    = (new \DateTime())->setTimestamp($period['end']);

			$result[] = [
				'startWeekDay'  => $dateStart->format('l'),
				'startTime'     => $dateStart->format('H:i:s'),
				'endWeekDay'    => $dateEnd->format('l'),
				'endTime'       => $dateEnd->format('H:i:s'),
			];
		}

		return $result;
	}

	/**
	 * make timestamps from periods` dates, remove invalid periods
	 * @param array $periods
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function preparePeriodsDates(array $periods)
	{
		$result = [];

		$minTimestamp = $this->getMinTimestamp();
		$maxTimestamp = $this->getMaxTimestamp();

		foreach ($periods as $period)
		{
			$period['start']    = $this->createTimestamp($period['start']);
			$period['end']      = $this->createTimestamp($period['end']);

			if ($period['start'] < $minTimestamp)
				$period['start'] = $minTimestamp;

			if ($period['end'] > $maxTimestamp)
				$period['end'] = $maxTimestamp;

			if (intval($period['start']) && intval($period['end'])
				&& ($period['start'] < $period['end']))
				$result[] = $period;
		}

		return $result;
	}

	/**
	 * @param $periods
	 * @return array
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function pastePeriodsTogether(array $periods)
	{
		do {
			$result = [];
			$pasted = false;

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
							$pasted = true;
						}

						$periodInResult = true;

						break;
					}

					if (($period['end'] <= $resultPeriod['end'])
						&& ($period['end'] >= $resultPeriod['start']))
					{
						if ($period['start'] < $resultPeriod['start']){
							$resultPeriod['start'] = $period['start'];
							$pasted = true;
						}

						$periodInResult = true;

						break;
					}
				}

				if (!$periodInResult)
					$result[] = $period;
			}

			$periods = $result;

		} while ($pasted);

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

		if (false === $dateTime instanceof \DateTime)
			return null;

		return $dateTime->getTimestamp();
	}

	/**
	 * @return int
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getMinTimestamp()
	{
		return (new \DateTime('Monday this week'))->getTimestamp();
	}

	/**
	 * @return int
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getMaxTimestamp()
	{
		return (new \DateTime('Monday next week'))->getTimestamp() - 1;
	}

	/**
	 * @param Event $event
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	public function afterLoadValue(Event $event)
	{
		if ($event->getSender() !== $this)
			return;

		foreach ($this->value as &$period)
		{
			$period['start']    = $this->getDateByWeekDayTime($period['startWeekDay'], $period['startTime']);
			$period['end']      = $this->getDateByWeekDayTime($period['endWeekDay'], $period['endTime']);

			$period['jqwStartMonth']    = $period['start']->format('m') - 1;
			$period['jqwEndMonth']      = $period['end']->format('m') - 1;

			$this->inputValue[] = [
				'start' => $period['start']->format('Y-m-d\TH:i:s'),
				'end'   => $period['end']->format('Y-m-d\TH:i:s')
			];
		}
	}

	/**
	 * @param $weekDay
	 * @param $time
	 * @return \DateTime
	 * @author Pavel Shulaev (http://rover-it.me)
	 */
	protected function getDateByWeekDayTime($weekDay, $time)
	{
		$date = new \DateTime($weekDay . ' this week');
		$time = explode(':', $time);
		$date->setTime($time[0], $time[1], $time[2]);

		return $date;
	}
}