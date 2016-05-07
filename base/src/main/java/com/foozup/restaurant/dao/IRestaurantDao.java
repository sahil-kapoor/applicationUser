package com.foozup.restaurant.dao;

import java.util.List;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.RestaurantInfo;

public interface IRestaurantDao {

	public List<RestaurantInfo>findRestaurantByName(String keyword);
	
	public List<RestaurantBase> findRestrauantByLocation(List<Integer> locationIds);
	
	public List<RestaurantBase> findRestrauantByArea(List<Integer> areaIds);
	
	public List<RestaurantBase> getRestaurntByAreaServed(List<Integer> areaIdList);

	public List<RestaurantBase> getRestaurntByCity(Integer cityId);
	
	
	
	
}
