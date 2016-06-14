package com.foozup.common;

import java.time.LocalDate;
import java.time.LocalDateTime;
import java.time.LocalTime;
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
	
	public static String getCurrentTimeAsString(){
		LocalDateTime now= LocalDateTime.now();
		DateTimeFormatter formatter = DateTimeFormatter.ofPattern("HH:mm:ss");
		return now.format(formatter);
		
	}
	
	public static LocalTime parseStringInHrsfromHour(String time){
		if(null !=time && !time.isEmpty()){
			DateTimeFormatter formatter = DateTimeFormatter.ofPattern("hh:mm a");
			return LocalTime.parse(time.toUpperCase(), formatter);	
		}else
			return null;
		
	}
	
	public static LocalTime getCurrentTimeAsTime(){
		LocalTime now= LocalTime.now();
		DateTimeFormatter formatter = DateTimeFormatter.ofPattern("HH:mm");
		return LocalTime.parse(now.format(formatter));
		
	}
	
	public static int compareTimes(LocalTime startTime,LocalTime endTime){
		if(null !=startTime && null !=endTime)
			return startTime.compareTo(endTime);
		else 
			return 0;
	}
	
	public static boolean isCurrentTimeInBetween(LocalTime startTime,LocalTime endTime){
		if(null !=startTime && null !=endTime){
			LocalTime currentTime=Utils.getCurrentTimeAsTime();
			if((startTime.isBefore(currentTime)|| startTime.compareTo(currentTime)==0)
					&& (endTime.isAfter(currentTime)|| endTime.compareTo(currentTime)==0))
				return true;
			else 
				return false;
		}else
			return false;
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
