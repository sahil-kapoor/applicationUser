package com.foozup.updates.service;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.aspectj.weaver.ArrayAnnotationValue;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.common.Constants;
import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.updates.model.Filters;
import com.foozup.updates.model.UpdateBase;
import com.foozup.updates.service.helper.IUpdateServiceHelper;

@Service("updatesService")
public class UpdatesServiceImpl implements IUpdatesService{

	@Autowired
	private IUpdateServiceHelper updateServiceHelper;

	@Override
	public Map<String,List<UpdateBase>> getUpdatesByRestaurant(Map<String, List<RestaurantBase>> formattedRestData,int limit) {
		Map<String,Map<Integer,List<UpdateBase>>> updateByRestCategory=new HashMap<>();
		formattedRestData.forEach((k,v)->{
			if (null !=v &&  !v.isEmpty()){
				//Map -> rest Id , list of updates
				updateByRestCategory.put(k, updateServiceHelper.getUpdatesByRestaurant(v,limit));
			}
		});
		
		//transformUpdatesObject(updateByRestCategory);
		
		return transformUpdatesObject(updateByRestCategory);
		
	}

	private Map<String,List<UpdateBase>> transformUpdatesObject(Map<String, Map<Integer, List<UpdateBase>>> updateByRestCategory) {
		Map<String,List<UpdateBase>> updatesByCategory=new HashMap<>();
		
		updateByRestCategory.forEach((category,updateByRest)->{
			List<UpdateBase> updateByCategory=new ArrayList<>();
			updateByRest.forEach((k,v)->{
				updateByCategory.addAll(v);
			});
			Collections.shuffle(updateByCategory);
			updatesByCategory.put(category, updateByCategory);
		});
		return updatesByCategory;
	}

	@Override
	public void collateUpdateData(Map<String, List<UpdateBase>> updateBaseListByType,
			RestaurantMetaDataRepsoneType restaurantMetaDataRepsoneType) {
			List<UpdateBase> finalRestList=new ArrayList<>();
			if(updateBaseListByType.containsKey(Constants.RESTAURANT_BY_LOCATION_SELECTED)){
				finalRestList.addAll(updateBaseListByType.get(Constants.RESTAURANT_BY_LOCATION_SELECTED));
			}if(updateBaseListByType.containsKey(Constants.RESTAURANT_BY_AREA_LOCATION_SELECTED)){
				finalRestList.addAll(updateBaseListByType.get(Constants.RESTAURANT_BY_AREA_LOCATION_SELECTED));
			}if(updateBaseListByType.containsKey(Constants.RESTAURANT_BY_AREA_SERVED)){
				finalRestList.addAll(updateBaseListByType.get(Constants.RESTAURANT_BY_AREA_SERVED));
			}if(updateBaseListByType.containsKey(Constants.RESTAURANT_BY_NO_LOCATION_SELECTED)){
				finalRestList.addAll(updateBaseListByType.get(Constants.RESTAURANT_BY_NO_LOCATION_SELECTED));
			}
			restaurantMetaDataRepsoneType.setUpdateBaseData(finalRestList);
	}

	@Override
	public Map<String, List<UpdateBase>> getUpdatesByRestaurantByFilter(
			Map<String, List<RestaurantBase>> foprmattedRestData, Filters filters, int limit) {
		// TODO Auto-generated method stub
		return null;
	}
	
	
	
}
