package com.foozup.updates.service;

import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;

public interface IUpdatesService {
	
	public RestaurantMetaDataRepsoneType getUpdatesByRestaurant(); 
	

}
