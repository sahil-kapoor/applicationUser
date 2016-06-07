package com.foozup.update.common;

import java.time.LocalDateTime;
import java.time.format.TextStyle;
import java.util.List;
import java.util.Locale;

public class UpdateUtils {

	  
	public static boolean isTodayOnly(List<String> days){
		if(days.size()==1){
			LocalDateTime currentTime = LocalDateTime.now();
			String currentDay=currentTime.getDayOfWeek().getDisplayName(TextStyle.SHORT, Locale.US);
			if(days.get(0).equalsIgnoreCase(currentDay))
				return true;
			else
				return false;
		}else
		{
			return false;
		}
	}
	
	  
}
