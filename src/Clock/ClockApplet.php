<?php 

namespace Epesi\Applets\Clock;

use Epesi\Base\Dashboard\Integration\Joints\AppletJoint;
use Epesi\Base\Dashboard\Seeds\Applet;
use Epesi\Core\System\Modules\Concerns\HasOptions;

class ClockApplet extends AppletJoint
{
	use HasOptions;
	
	public function caption()
	{
		return __('Clock');
	}
	
	public function info()
	{
		return __('Analog JS clock');
	}
	
	public function elements()
	{
		return [
				[
						'name' => 'skin',
						'decorator' => [
								'DropDown',
								'caption' => __('Clock skin'),
								'values' => [
										'swissRail' => 'swissRail',
										'chunkySwiss' => 'chunkySwiss',
										'chunkySwissOnBlack' => 'chunkySwissOnBlack',
										'fancy' => 'fancy',
										'machine' => 'machine',
										'classic' => 'classic',
										'modern' => 'modern',
										'simple' => 'simple',
										'securephp' => 'securephp',
										'Tes2' => 'Tes2',
										'Lev' => 'Lev',
										'Sand' => 'Sand',
										'Sun' => 'Sun',
										'Tor' => 'Tor',
										'Babosa' => 'Babosa',
										'Tumb' => 'Tumb',
										'Stone' => 'Stone',
										'Disc' => 'Disc',
										'flash' => 'flash'
								]
						],
						'default' => 'swissRail',
						'rule' => [
								[
										'message' => __('Field required'),
										'type' => 'required'
								]
						],
						
				],
				[
						'name' => 'type',
						'decorator' => [
								'DropDown',
								'caption' => __('Type'),
								'values' => [
										'single' => __('Single Clock'),
										'double' => __('Double Clock')
								],
						],
						'default' => 'double',
				],
				[
						'name' => 'second_clock_timezone',
						'decorator' => [
								'DropDown',
								'caption' => __('Second clock timezone'),
								'values' => self::timezones(),
						],
						'default' => '8.0',						
						'display' => ['type' => 'isExactly[double]']
				],
				[
						'name' => 'second_clock_label',
						'decorator' => [
								'caption' => __('Second clock label'),
						],
						'default' => __('Singapore / China'),
						'display' => ['type' => 'isExactly[double]']
				],
		];
	}

	public function body(Applet $applet, $options = [])
	{
		$skin = $options['skin']?? 'swissRail';
		
		$type = $options['type']?? null;
		
		load_js(url('js/coolclock.js'));
		load_js(url('js/moreskins.js'));

		if ($type == 'double') {
			$size = 60;

			$timezone = $options['second_clock_timezone'] ?? '7.0';
			$label = $options['second_clock_label'] ?? self::timezones()[$timezone];
			$offset = $timezone * 60 * 60;
			
			$columns = $applet->add(['Columns', 'ui' => 'two stackable grid']);
			
			$wrap = $columns->addColumn()->setStyle(['text-align' => 'center', 'min-width' => '140px']);
			$wrap->add(['View', 'attr' => ['coolclock' => "$skin:$size"]])->setElement('canvas');
			$wrap->add('View')->set(date('d F Y'));
			
			$wrap = $columns->addColumn()->setStyle(['text-align' => 'center', 'min-width' => '140px']);
			$wrap->add(['View', 'attr' => ['coolclock' => "$skin:$size:noSeconds:$timezone"]])->setElement('canvas');
			$wrap->add('View')->set($label?: gmdate('d F Y', time() + $offset));
			
// 			print('<table style="width: 100%"><tr><td style="width: 100px;text-align:center;"><canvas id="' . $this->get_path() . '1_canvas" class="CoolClock:' . $skin . ':' . $size . '"></canvas>');
// // 			print('<br>Local Time<br><span class="local_time">' . Base_RegionalSettingsCommon::time2reg(null, false) . '</span></td>');
// 			print('<td style="width: 100px;text-align:center;"><canvas id="' . $this->get_path() . '2_canvas" class="CoolClock:' . $skin . ':' . $size . ':noSeconds:' . $timezone . '"></canvas>');
// 			print('<br>' . $label . '<br>' . gmdate('d F Y', time() + $offset) . '</td></tr></table>');
// 			eval_js('jq(".local_time").html(function() {return jq.datepicker.formatDate("d MM yy", new Date());});');
			
// 			print('</center>');
// 			return;
		}
		else {
			$size = 100;
			
			$wrap = $applet->add('View')->setStyle(['text-align' => 'center']);
			
			$wrap->add(['View', 'attr' => ['coolclock' => "$skin:$size"]])->setElement('canvas');
			
			$wrap->add('View')->set(date('d F Y'));
		}
	}
	
	public static function timezones() {
		return [
				'-12.0' => __('(GMT-12:00) Eniwetok, Kwajalein'),
				'-11.0' => __('(GMT-11:00) Midway Island, Samoa'),
				'-10.0' => __('(GMT-10:00) Hawaii'),
				'-9.0' => __('(GMT-9:00) Alaska'),
				'-8.0' => __('(GMT-8:00) Pacific Time (US &amp; Canada)'),
				'-7.0' => __('(GMT-7:00) Mountain Time (US &amp; Canada)'),
				'-6.0' => __('(GMT-6:00) Central Time (US &amp; Canada), Mexico City'),
				'-5.0' => __('(GMT-5:00) Eastern Time (US &amp; Canada), Bogota, Lima'),
				'-4.0' => __('(GMT-4:00) Atlantic Time (Canada), Caracas, La Paz'),
				'-3.5' => __('(GMT-3:30) Newfoundland'),
				'-3.0' => __('(GMT-3:00) Brazil, Buenos Aires, Georgetown'),
				'-2.0' => __('(GMT-2:00) Mid-Atlantic'),
				'-1.0' => __('(GMT-1:00 hour) Azores, Cape Verde Islands'),
				'0.0' => __('(GMT) Western Europe Time, London, Lisbon, Casablanca'),
				'1.0' => __('(GMT+1:00 hour) Hamburg, Berlin, Brussels, Madrid, Paris'),
				'2.0' => __('(GMT+2:00) Kaliningrad, South Africa'),
				'3.0' => __('(GMT+3:00) Baghdad, Riyadh, Moscow, St. Petersburg'),
				'3.5' => __('(GMT+3:30) Tehran'),
				'4.0' => __('(GMT+4:00) Abu Dhabi, Muscat, Baku, Tbilisi'),
				'4.5' => __('(GMT+4:30) Kabul'),
				'5.0' => __('(GMT+5:00) Ekaterinburg, Islamabad, Karachi, Tashkent'),
				'5.5' => __('(GMT+5:30) Bombay, Calcutta, Madras, New Delhi'),
				'5.75' => __('(GMT+5:45) Kathmandu'),
				'6.0' => __('(GMT+6:00) Almaty, Dhaka, Colombo'),
				'7.0' => __('(GMT+7:00) Bangkok, Hanoi, Jakarta'),
				'8.0' => __('(GMT+8:00) Beijing, Perth, Singapore, Hong Kong'),
				'9.0' => __('(GMT+9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk'),
				'9.5' => __('(GMT+9:30) Adelaide, Darwin'),
				'10.0' => __('(GMT+10:00) Eastern Australia, Guam, Vladivostok'),
				'11.0' => __('(GMT+11:00) Magadan, Solomon Islands, New Caledonia'),
				'12.0' => __('(GMT+12:00) Auckland, Wellington, Fiji, Kamchatka')
		];
	}
}