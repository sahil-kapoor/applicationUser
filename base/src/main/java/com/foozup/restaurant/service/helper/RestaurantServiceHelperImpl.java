package com.foozup.restaurant.service.helper;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.foozup.restaurant.dao.IRestaurantDao;
import com.foozup.restaurant.model.RestaurantBase;

@Component("restaurantServiceHelper")
public class RestaurantServiceHelperImpl implements IRestaurantServiceHelper {

	@Autowired
	private IRestaurantDao restaurantDao;
	@Override
	public List<RestaurantBase> getRestaurantsByLocationId(List<Integer> locationIds){
		return null;
	}
	
}
