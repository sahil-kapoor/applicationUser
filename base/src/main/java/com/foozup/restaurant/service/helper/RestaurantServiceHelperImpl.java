package com.foozup.restaurant.service.helper;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.foozup.restaurant.dao.IRestaurantDao;
import com.foozup.restaurant.model.RestaurantBase;

@Component("restaurantServiceHelper")
public class RestaurantServiceHelperImpl implements IRestaurantServiceHelper {

	@Autowired
	private IRestaurantDao restaurantaDaoImpl;
	@Override
	public List<RestaurantBase> getRestaurantsByLocationId(List<Integer> locationIds){
		return restaurantaDaoImpl.findRestrauantByLocation(locationIds);
	}
	
	@Override
	public List<RestaurantBase> getRestaurantsByCityId(Integer cityId) {
		return restaurantaDaoImpl.getRestaurntByCity(cityId);
		
	}

	@Override
	public List<RestaurantBase> getRestaurantsByAreaId(List<Integer> areaIdList) {
		return restaurantaDaoImpl.findRestrauantByArea(areaIdList);	
	}

	@Override
	public List<RestaurantBase> getRestaurantByAreaServed(List<Integer> areaIdList) {
		List<RestaurantBase> restaurantByAreaServed=restaurantaDaoImpl.getRestaurntByAreaServed(areaIdList);
		return restaurantByAreaServed;
	}
	
}
