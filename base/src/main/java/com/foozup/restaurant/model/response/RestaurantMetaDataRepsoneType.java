package com.foozup.restaurant.model.response;

import java.util.List;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.updates.model.UpdateBase;

public class RestaurantMetaDataRepsoneType{

	private List<RestaurantBase> restaurantBaseData;
	private List<UpdateBase> updateBaseData;
	
	public List<RestaurantBase> getRestaurantBasedData() {
		return restaurantBaseData;
	}

	public void setRestaurantBasedData(List<RestaurantBase> restaurantBasedData) {
		this.restaurantBaseData = restaurantBasedData;
	}

	public List<UpdateBase> getUpdateBaseData() {
		return updateBaseData;
	}

	public void setUpdateBaseData(List<UpdateBase> updateBaseData) {
		this.updateBaseData = updateBaseData;
	}
	
	
}
