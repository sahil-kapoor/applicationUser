package com.foozup.updates.service;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.updates.model.UpdateBase;
import com.foozup.updates.service.helper.IUpdateServiceHelper;

@Service("updatesService")
public class UpdatesServiceImpl implements IUpdatesService{

	@Autowired
	private IUpdateServiceHelper updateServiceHelper;

	@Override
	public HashMap<String,List<UpdateBase>> getUpdatesByRestaurant(Map<String, List<RestaurantBase>> formattedRestData) {
		Map<String,Map<Integer,List<UpdateBase>>> updateByRestCategory=new HashMap<>();
		formattedRestData.forEach((k,v)->{
			//Map -> rest Id , list of updates
			Map<Integer,List<UpdateBase>> updateByRestaurant=new HashMap<>();
			updateByRestCategory.put(k, updateByRestaurant);
			updateServiceHelper.getUpdatesByRestaurant(updateByRestCategory,v);	
		});
		updateServiceHelper.formatCollateUpdateData(updateByRestCategory,formattedRestData);
		
		return null;
		
	}

	@Override
	public RestaurantMetaDataRepsoneType collateRestaurantData(Map<String, List<UpdateBase>> updateBaseListByType) {
		// TODO Auto-generated method stub
		return null;
	}
}
