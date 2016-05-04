package com.foozup.restaurant.service;

import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;

public interface RestaurantService {
	
	public RestaurantMetaDataRepsoneType getRestaurantByLocation(RestauantFindRequestType request);

}
