package com.foozup.restaurant.service;

import java.util.ArrayList;
import java.util.Collections;
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
		 RestaurantMetaDataRepsoneType restaurantMetaDataRepsoneType=new RestaurantMetaDataRepsoneType();
		 
		//If location is specified
		if(null!=request.getLocationIds() && !request.getLocationIds().isEmpty()){
			List<RestaurantBase> restaurantsByAreaNotLocation=new ArrayList<>();
			List<RestaurantBase> restaurantsByLocationSelected=new ArrayList<>();
			List<RestaurantBase> restaurantsByAreaServedForLoc=new ArrayList<>();
			List<Integer> areaIdList=staticDataService.getAreabyLocIds(request.getCityId(), request.getLocationIds());
			List<RestaurantBase> restaurantsByAreaId=restaurantServiceHelper.getRestaurantsByAreaId(areaIdList);
			//Restaurant for location selected
			restaurantsByAreaId.forEach(restaurant->{
					if(request.getLocationIds().contains(restaurant.getLocationId())){
						restaurantsByLocationSelected.add(restaurant);
						}
				});
			//Restaurant for area selected
			restaurantsByAreaId.forEach(restaurant->{
				if(!restaurantsByLocationSelected.contains(restaurant)){
					restaurantsByAreaNotLocation.add(restaurant);
				}
			});
			//Restaurant for area served
			List<RestaurantBase> restaurantsByAreaServed=restaurantServiceHelper.getRestaurantByAreaServed(areaIdList);
			
			Collections.shuffle(restaurantsByLocationSelected);
			Collections.shuffle(restaurantsByAreaNotLocation);
			Collections.shuffle(restaurantsByAreaServed);
			List<RestaurantBase> finalRestList=new ArrayList<>();
			finalRestList.addAll(restaurantsByLocationSelected);
			finalRestList.addAll(restaurantsByAreaNotLocation);
			finalRestList.addAll(restaurantsByAreaServed);
			restaurantMetaDataRepsoneType.setRestaurantBasedData(finalRestList);
			
		}//If location is not specified, fetch results by city
		else{
			List<RestaurantBase> restaurantsByCityId=restaurantServiceHelper.getRestaurantsByCityId(request.getCityId());
			Collections.shuffle(restaurantsByCityId);
			restaurantMetaDataRepsoneType.setRestaurantBasedData(restaurantsByCityId);
		}
		return restaurantMetaDataRepsoneType;
	}

}
