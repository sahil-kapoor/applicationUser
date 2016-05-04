package com.foozup.restaurant.service;

import org.springframework.stereotype.Service;

import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;

@Service("restaurantService")
public class RestaurantServiceImpl implements IRestaurantService{

	@Override
	public RestaurantMetaDataRepsoneType getRestaurantByLocation(RestauantFindRequestType request) {
		// TODO Auto-generated method stub
		return null;
	}

}
