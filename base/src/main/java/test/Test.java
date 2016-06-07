package test;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import java.time.format.TextStyle;
import java.util.Locale;

import com.foozup.common.Days;

public class Test {

	
	public static void main(String args[]){
		
		System.out.println(Days.forValue(1));
		System.out.println(Days.forValue(Integer.parseInt("1")).name());
/*		if (BCrypt.checkpw("passfbpass", "$2a$10$TYqJjMMDl6dlMPGMFrxE9efgJnJ1Cj.LaTlviebP8aMzyol122V12"))
			System.out.println("It matches");
		else
			System.out.println("It does not match");
		
		
		
		RestaurantBase r1=new RestaurantBase();
		r1.setId(1);
		r1.setName("Abc");
		RestaurantBase r2=new RestaurantBase();
		r2.setId(1);
		r2.setName("Abc");
		
		Set<RestaurantBase> s1= new HashSet<>();
		s1.add(r1);
		System.out.println("length 1 "+  s1.size());
		s1.add(r2);
		System.out.println("length 2 "+ s1.size());
		
		Integer locationId=5;
		Location loc1=new Location();
		loc1.setId(1);
		Location loc2=new Location();
		loc2.setId(2);
		Location loc3=new Location();
		loc3.setId(3);
		Location loc4=new Location();
		loc4.setId(4);
		Location loc5=new Location();
		loc5.setId(5);
		Area area1=new Area();
		area1.setId(1);
		Area area2=new Area();
		area2.setId(2);
		Area area3=new Area();
		area3.setId(3);
		area1.setLocations(new HashMap<>());
		area3.setLocations(new HashMap<>());
		area2.setLocations(new HashMap<>());
		area1.getLocations().put(1, loc1);
		area1.getLocations().put(2, loc2);
		area2.getLocations().put(3, loc3);
		area2.getLocations().put(4, loc4);
		area3.getLocations().put(5, loc5);
		
		Map<Integer,Area> areaMap=new HashMap<>();
		areaMap.put(1, area1);
		areaMap.put(2, area2);
		areaMap.put(3, area3);
		Area area=new Area();
		
		area=areaMap.entrySet().stream().filter(p->p.getValue().getLocations().entrySet().stream().
				filter(x->x.getKey().intValue()==locationId.intValue()).findAny().isPresent()).findAny().get().getValue();
		System.out.println(area.getId());
		
		*/
		
		
		LocalDateTime now= LocalDateTime.now();
		System.out.println(now);
		  DateTimeFormatter formatter = DateTimeFormatter.ofPattern("HH:mm:ss");
		  String text = now.format(formatter);
		  System.out.println(text);
		  System.out.println(now.getDayOfWeek().getDisplayName(TextStyle.SHORT, Locale.US));
		  
	//	DateTimeFormatter formatter=DateTimeFormatter.ofPattern("yyyy-mm-dd");
		//System.out.println(now.format(formatter));
		
	}
}
