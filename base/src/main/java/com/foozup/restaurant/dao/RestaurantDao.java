package com.foozup.restaurant.dao;

import java.util.List;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.RestaurantInfo;

public interface RestaurantDao {

	public List<RestaurantInfo>findRestaurantByName(String keyword);
	
	public List<RestaurantBase> findRestrauantByLocation(List<Integer> locationIds);
	
	public List<RestaurantBase> findRestrauantByArea(List<String> areaIds);
	
	public List<RestaurantBase> findRestrauantByAreaServed(List<String> areaIds);

	
	
	
	
}
