package com.foozup.restaurant.service.helper;

import java.util.List;

import com.foozup.restaurant.model.RestaurantBase;

public interface IRestaurantServiceHelper {

	public List<RestaurantBase> getRestaurantsByLocationId(List<Integer> locationIds);

}
