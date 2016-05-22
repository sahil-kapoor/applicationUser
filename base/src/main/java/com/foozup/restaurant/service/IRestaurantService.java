package com.foozup.restaurant.service;

import java.util.List;
import java.util.Map;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;

public interface IRestaurantService {

	// public RestaurantMetaDataRepsoneType
	// getRestaurantByLocation(RestauantFindRequestType request);
	public Map<String, List<RestaurantBase>> getRestaurantByLocation(RestauantFindRequestType request);

	public Map<String, List<RestaurantBase>> formatRestaruantData(Map<String, List<RestaurantBase>> restaurantData);

	public void collateRestaurantData(Map<String, List<RestaurantBase>> restaurantData,
			RestaurantMetaDataRepsoneType restaurantMetaDataRepsoneType);
}
