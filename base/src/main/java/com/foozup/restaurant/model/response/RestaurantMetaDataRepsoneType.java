package com.foozup.restaurant.model.response;

import java.util.List;

import com.foozup.model.BaseResponse;
import com.foozup.restaurant.model.RestaurantBase;

public class RestaurantMetaDataRepsoneType extends BaseResponse{

	private List<RestaurantBase> restaurantBasedData;

	public List<RestaurantBase> getRestaurantBasedData() {
		return restaurantBasedData;
	}

	public void setRestaurantBasedData(List<RestaurantBase> restaurantBasedData) {
		this.restaurantBasedData = restaurantBasedData;
	}
	
	
}