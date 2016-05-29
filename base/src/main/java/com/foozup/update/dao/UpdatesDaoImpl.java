package com.foozup.update.dao;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.foozup.dao.AbstractDao;

public class UpdatesDaoImpl implements IUpdatesDao {

	private AbstractDao abstractDao;
	private static final Logger logger = LoggerFactory.getLogger(UpdatesDaoImpl.class);;

	public UpdatesDaoImpl(AbstractDao abstractDao) {
		this.abstractDao = abstractDao;
	}
	
	
/*	
	select * from foozup_restaurant.restaurant_updates;

	select
	case
	when restaurants.end_time_2 is null OR restaurants.end_time_2 ='' then  restaurants.end_time
	else restaurants.end_time_2
	end as end_time,franchisee_updates.end_time as update_end_time, franchisee_updates.all_time,
	franchisee_updates.id as update_id, restaurants.id, franchisee_updates.updates, franchisee_updates.activation_type,
	franchisee_updates.start_date, franchisee_updates.end_date from restaurants
	left join franchisee_updates_restaurant on franchisee_updates_restaurant.restaurant_id = restaurants.id
	left join franchisee_updates on franchisee_updates.id = franchisee_updates_restaurant.update_id
	where restaurants.id = 2 and  franchisee_updates.status = 1 and franchisee_updates.start_date <= "2016-06-02 00:00:00" and
	franchisee_updates.end_date >= "2016-06-02 00:00:00" and (
	(franchisee_updates.all_time = 1 and 
	   (time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'10:00:00'" or 
	   time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'10:00:00'"))
	 or
	(franchisee_updates.all_time = 1 and franchisee_updates.end_time >= "10:00:00"));*/

	
/*	select  case
	when restaurants.end_time_2 is null OR restaurants.end_time_2 ='' then  restaurants.end_time
	else restaurants.end_time_2
	end as end_time, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
restaurant_updates.id as update_id, restaurants.id, restaurant_updates.updates, restaurant_updates.activation_type, restaurant_updates.start_date, restaurant_updates.end_date, restaurant_updates.is_primary from restaurants 
left join restaurant_updates on restaurant_updates.restaurant_id = restaurants.id 
where restaurants.id = '.$restaurant_id.' and restaurant_updates.status = 1 and restaurant_updates.start_date = "'.date('Y-m-d'). ' 00:00:00" and 
restaurant_updates.end_date = "'.date('Y-m-d'). '" and ((restaurant_updates.all_time = 1 and 
(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
 or (restaurant_updates.all_time = 0 and restaurant_updates.end_time >= "'.date('H:i:s').'"));
																			
																			

select  case
	when restaurants.end_time_2 is null OR restaurants.end_time_2 ='' then  restaurants.end_time
	else restaurants.end_time_2
	end as end_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
	restaurant_updates.id as update_id, restaurants.id,restaurant_updates.activation_type, restaurant_updates.start_date, restaurant_updates.end_date, restaurant_updates.is_primary from restaurants 
		left join restaurant_updates on restaurant_updates.restaurant_id = restaurants.id 
	where restaurants.id = '.$restaurant_id.' and restaurant_updates.status = 1 and restaurant_updates.start_date <= "'.date('Y-m-d'). ' 00:00:00" and 
	restaurant_updates.end_date >= "'.date('Y-m-d'). '" and ((restaurant_updates.all_time = 1 and 
		(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
		 or 
		(restaurant_updates.all_time = 0 and restaurant_updates.end_time >= "'.date('H:i:s').'"))
		order by restaurant_updates.updated_at desc;
				*/
	
	
}
