package com.foozup.restaurant.model.response;

import java.util.List;

import com.foozup.restaurant.model.RestaurantBase;

public class RestaurantMetaDataRepsoneType{

	private List<RestaurantBase> restaurantBaseData;

	public List<RestaurantBase> getRestaurantBasedData() {
		return restaurantBaseData;
	}

	public void setRestaurantBasedData(List<RestaurantBase> restaurantBasedData) {
		this.restaurantBaseData = restaurantBasedData;
	}
	
	
}
