package com.foozup.restaurant.service.helper;

import java.util.List;

import com.foozup.restaurant.model.RestaurantBase;

public interface IRestaurantServiceHelper {

	public List<RestaurantBase> getRestaurantsByLocationId(List<Integer> locationIds);
	
	public List<RestaurantBase> getRestaurantsByCityId(Integer cityId);

	public List<RestaurantBase> getRestaurantsByAreaId(List<Integer> areaIdList);

	public List<RestaurantBase> getRestaurantByAreaServed(List<Integer> areaIdList);


}
