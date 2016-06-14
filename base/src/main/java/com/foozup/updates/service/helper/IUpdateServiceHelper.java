package com.foozup.updates.service.helper;

import java.util.List;
import java.util.Map;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.updates.model.UpdateBase;

public interface IUpdateServiceHelper {

	public Map<Integer,List<UpdateBase>> getUpdatesByRestaurant(List<RestaurantBase> restaurants,int limit);

	public List<UpdateBase> collateUpdateData(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory,
			Map<String, List<RestaurantBase>> formattedRestData);


}
