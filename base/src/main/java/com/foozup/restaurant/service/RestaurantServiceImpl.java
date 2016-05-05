package com.foozup.restaurant.service;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.restaurant.service.helper.IRestaurantServiceHelper;
import com.foozup.staticData.service.IStaticDataService;

@Service("restaurantService")
public class RestaurantServiceImpl implements IRestaurantService{

	@Autowired
	private IRestaurantServiceHelper restaurantServiceHelper;
	
	@Autowired
	private IStaticDataService staticDataService;
	@Override
	public RestaurantMetaDataRepsoneType getRestaurantByLocation(RestauantFindRequestType request) {
		if(null!=request.getLocationIds() && !request.getLocationIds().isEmpty()){
			List<RestaurantBase> restaurantsByLocationId=restaurantServiceHelper.getRestaurantsByLocationId(request.getLocationIds());
			staticDataService.getAreabyLocIds(request.getCityId(), request.getLocationIds());
			
		}
		else{
			
		}
		return null;
	}

}
