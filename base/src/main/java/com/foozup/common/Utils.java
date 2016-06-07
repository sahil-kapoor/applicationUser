package com.foozup.common;

import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.List;

public final class Utils {

	private Utils(){
		
	};
	
	public static String getTodayStartDateTime(){
		LocalDate now= LocalDate.now();
		DateTimeFormatter formatter=DateTimeFormatter.ofPattern("yyyy-MM-dd");
		return now.format(formatter)+" 00:00:00";
	}
	
	public static String getTodayEndDateTime(){
		LocalDate now= LocalDate.now();
		DateTimeFormatter formatter=DateTimeFormatter.ofPattern("yyyy-MM-dd");
		return now.format(formatter)+" 23:59:59";
	}
	
	public static String getcurrentTime(){
		LocalDateTime now= LocalDateTime.now();
		DateTimeFormatter formatter = DateTimeFormatter.ofPattern("HH:mm:ss");
		return now.format(formatter);
		
	}

	public static List<String> convertIntDaytoString(String dayString){
		List<String> days=new ArrayList<>();
		String[] dayInNumber=dayString.split(",");
	    for(int i=0;i<dayInNumber.length;i++){
	    	days.add(Days.forValue(Integer.parseInt(dayInNumber[i])).name());
	    }
	    return days;
	}
}
