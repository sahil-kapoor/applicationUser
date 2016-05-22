package com.foozup.updates.service.helper;

import java.util.List;
import java.util.Map;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.updates.model.UpdateBase;

public interface IUpdateServiceHelper {

	public void getUpdatesByRestaurant(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory,
			List<RestaurantBase> restaurants);

	public List<UpdateBase> formatCollateUpdateData(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory,
			Map<String, List<RestaurantBase>> formattedRestData);

}
