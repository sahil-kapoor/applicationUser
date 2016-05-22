package com.foozup.updates.service;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.updates.model.UpdateBase;

public interface IUpdatesService {
	
	public HashMap<String,List<UpdateBase>> getUpdatesByRestaurant(Map<String,List<RestaurantBase>> foprmattedRestData); 
	public RestaurantMetaDataRepsoneType collateRestaurantData(Map<String,List<UpdateBase>> updateBaseListByType);

}
