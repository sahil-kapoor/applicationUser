package com.foozup.updates.service;

import java.util.List;
import java.util.Map;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.updates.model.Filters;
import com.foozup.updates.model.UpdateBase;

public interface IUpdatesService {

	public Map<String, List<UpdateBase>> getUpdatesByRestaurant(Map<String, List<RestaurantBase>> foprmattedRestData,
			int limit);

	public void collateUpdateData(Map<String, List<UpdateBase>> updateBaseListByType,
			RestaurantMetaDataRepsoneType restaurantMetaDataRepsoneType);


	public Map<String, List<UpdateBase>> getUpdatesByRestaurantByFilter(Map<String, List<RestaurantBase>> foprmattedRestData,Filters filters,
			int limit);

}
