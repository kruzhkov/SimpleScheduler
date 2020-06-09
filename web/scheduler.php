<?php

	echo "Starting...\n";
	
	include_once("lib.php");

	while(true) :
	
		$seconds = date("s");

		if ($seconds=="00") :
			
			$current_time = date("H:i");
			$array_on = explode(":", $s->on_tod);

			$t_hour = 0;
			$t_minute = 0;
			
			if (count($array_on) == 0) {
				continue;
			} elseif (count($array_on) > 1) {
				$t_minute = (int)$array_on[1];
			}

			$t_hour = $array_on[0];	
			$current_duration = (int)($s->off_tod);
echo $s->off_tod;
echo $t_hour;
echo "\n";
echo $current_duration;
echo '##8';
			if ($current_duration == 0) {
				continue;
			}
echo '##9';
			$current_end = $t_hour . ':' . (string)($t_minute + $current_duration);
			$current_dow = date("N");
			echo $current_end;
			
			$sun = get_sunset_sunrise();
			$is_sunset  = (bool)($current_time==$sun["sunset"]);
			$is_sunrise = (bool)($current_time==$sun["sunrise"]);
			
			$sched = load_data();
			
			foreach ($sched as $s) :
		
				if ($s->enabled):
				
					if (strpos($s->on_dow,  $current_dow)!== false) :
						if ( $s->on_tod==$current_time  ) call_HA($s->entity_id,"on");
						if ( strtolower($s->on_tod)=="sunset"  && $is_sunset   ) call_HA($s->entity_id,"on");
						if ( strtolower($s->on_tod)=="sunrise" && $is_sunrise  ) call_HA($s->entity_id,"on");
					endif;
					
// 					if (strpos($s->off_dow,  $current_dow)!== false) :
						if ( $current_end==$current_time  ) call_HA($s->entity_id,"off");
// 						if ( $s->off_tod==$current_time  ) call_HA($s->entity_id,"off");					
						if ( strtolower($s->off_tod)=="sunset"  && $is_sunset  ) call_HA($s->entity_id,"off");
						if ( strtolower($s->off_tod)=="sunrise" && $is_sunrise ) call_HA($s->entity_id,"off");
// 					endif;
					
				endif;
				
			endforeach;
			
		endif;
		
		sleep(1);
		
	endwhile;
	
