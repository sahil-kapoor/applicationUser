package com.foozup.restaurant.service;

import java.util.ArrayList;
import java.util.Collections;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.util.StringUtils;

import com.foozup.common.Constants;
import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.restaurant.service.helper.IRestaurantServiceHelper;
import com.foozup.staticData.service.IStaticDataService;

@Service("restaurantService")
public class RestaurantServiceImpl implements IRestaurantService {

	@Autowired
	private IRestaurantServiceHelper restaurantServiceHelper;

	@Autowired
	private IStaticDataService staticDataService;

	@Override
	public Map<String, List<RestaurantBase>> getRestaurantByLocation(Integer cityId,List<Integer> locationIds) {
		Map<String, List<RestaurantBase>> restaurantMetaDataByType = new HashMap<>();

		// If location is specified
		if (null != locationIds && !locationIds.isEmpty()) {
			List<RestaurantBase> restaurantsByAreaNotLocation = new ArrayList<>();
			List<RestaurantBase> restaurantsByLocationSelected = new ArrayList<>();
			List<Integer> areaIdList = staticDataService.getAreabyLocIds(cityId, locationIds);
			List<RestaurantBase> restaurantsByAreaId = restaurantServiceHelper.getRestaurantsByAreaId(areaIdList);
			// Restaurant for location selected
			restaurantsByAreaId.forEach(restaurant -> {
				if (locationIds.contains(restaurant.getLocationId())) {
					restaurantsByLocationSelected.add(restaurant);
				}
			});
			// Restaurant for area selected
			restaurantsByAreaId.forEach(restaurant -> {
				if (!restaurantsByLocationSelected.contains(restaurant)) {
					restaurantsByAreaNotLocation.add(restaurant);
				}
			});
			// Restaurant for area served
			List<RestaurantBase> restaurantsByAreaServed = restaurantServiceHelper
					.getRestaurantByAreaServed(areaIdList);

			Collections.shuffle(restaurantsByLocationSelected);
			Collections.shuffle(restaurantsByAreaNotLocation);
			Collections.shuffle(restaurantsByAreaServed);
			restaurantMetaDataByType.put(Constants.RESTAURANT_BY_LOCATION_SELECTED, restaurantsByLocationSelected);
			restaurantMetaDataByType.put(Constants.RESTAURANT_BY_AREA_LOCATION_SELECTED, restaurantsByAreaNotLocation);
			restaurantMetaDataByType.put(Constants.RESTAURANT_BY_AREA_SERVED, restaurantsByAreaServed);

		} // If location is not specified, fetch results by city
		else {
			List<RestaurantBase> restaurantsByCityId = restaurantServiceHelper
					.getRestaurantsByCityId(cityId);
			Collections.shuffle(restaurantsByCityId);
			restaurantMetaDataByType.put(Constants.RESTAURANT_BY_NO_LOCATION_SELECTED, restaurantsByCityId);
		}
		return restaurantMetaDataByType;
	}

	@Override
	public Map<String, List<RestaurantBase>> formatRestaruantData(Map<String, List<RestaurantBase>> restaurantData) {
		restaurantData.forEach((k, v) -> {
			v.forEach(restaurant -> {
				restaurant.setArea(
						staticDataService.getAreaById(restaurant.getCityId(), restaurant.getAreaId()).getName());
				restaurant.setCity(staticDataService.getCityById(restaurant.getCityId()).getName());
				restaurant.setLocation(staticDataService
						.getLocation(restaurant.getCityId(), restaurant.getAreaId(), restaurant.getLocationId())
						.getName());
				restaurant.setPhoto(StringUtils.isEmpty(restaurant.getPhoto()) ? 
						Constants.IMAGE_RESTAURANT_DEFAULT : (Constants.IMAGE_RESTAURANT + restaurant.getPhoto()));
			});
		});
		return restaurantData;
	}
 
	@Override
	public void collateRestaurantData(Map<String, List<RestaurantBase>> restaurantData,
			RestaurantMetaDataRepsoneType restaurantMetaDataRepsoneType) {
		List<RestaurantBase> finalRestList=new ArrayList<>();
		if(restaurantData.containsKey(Constants.RESTAURANT_BY_LOCATION_SELECTED)){
			finalRestList.addAll(restaurantData.get(Constants.RESTAURANT_BY_LOCATION_SELECTED));
		}if(restaurantData.containsKey(Constants.RESTAURANT_BY_AREA_LOCATION_SELECTED)){
			finalRestList.addAll(restaurantData.get(Constants.RESTAURANT_BY_AREA_LOCATION_SELECTED));
		}if(restaurantData.containsKey(Constants.RESTAURANT_BY_AREA_SERVED)){
			finalRestList.addAll(restaurantData.get(Constants.RESTAURANT_BY_AREA_SERVED));
		}if(restaurantData.containsKey(Constants.RESTAURANT_BY_NO_LOCATION_SELECTED)){
			finalRestList.addAll(restaurantData.get(Constants.RESTAURANT_BY_NO_LOCATION_SELECTED));
		}
		restaurantMetaDataRepsoneType.setRestaurantBasedData(finalRestList);
	}

	/*
	 * public RestaurantMetaDataRepsoneType
	 * getRestaurantByLocation(RestauantFindRequestType request) {
	 * RestaurantMetaDataRepsoneType restaurantMetaDataRepsoneType=new
	 * RestaurantMetaDataRepsoneType();
	 * 
	 * //If location is specified if(null!=request.getLocationIds() &&
	 * !request.getLocationIds().isEmpty()){ List<RestaurantBase>
	 * restaurantsByAreaNotLocation=new ArrayList<>(); List<RestaurantBase>
	 * restaurantsByLocationSelected=new ArrayList<>(); List<Integer>
	 * areaIdList=staticDataService.getAreabyLocIds(request.getCityId(),
	 * request.getLocationIds()); List<RestaurantBase>
	 * restaurantsByAreaId=restaurantServiceHelper.getRestaurantsByAreaId(
	 * areaIdList); //Restaurant for location selected
	 * restaurantsByAreaId.forEach(restaurant->{
	 * if(request.getLocationIds().contains(restaurant.getLocationId())){
	 * restaurantsByLocationSelected.add(restaurant); } }); //Restaurant for
	 * area selected restaurantsByAreaId.forEach(restaurant->{
	 * if(!restaurantsByLocationSelected.contains(restaurant)){
	 * restaurantsByAreaNotLocation.add(restaurant); } }); //Restaurant for area
	 * served List<RestaurantBase>
	 * restaurantsByAreaServed=restaurantServiceHelper.getRestaurantByAreaServed
	 * (areaIdList);
	 * 
	 * Collections.shuffle(restaurantsByLocationSelected);
	 * Collections.shuffle(restaurantsByAreaNotLocation);
	 * Collections.shuffle(restaurantsByAreaServed); List<RestaurantBase>
	 * finalRestList=new ArrayList<>();
	 * finalRestList.addAll(restaurantsByLocationSelected);
	 * finalRestList.addAll(restaurantsByAreaNotLocation);
	 * finalRestList.addAll(restaurantsByAreaServed);
	 * restaurantMetaDataRepsoneType.setRestaurantBasedData(finalRestList);
	 * 
	 * }//If location is not specified, fetch results by city else{
	 * List<RestaurantBase>
	 * restaurantsByCityId=restaurantServiceHelper.getRestaurantsByCityId(
	 * request.getCityId()); Collections.shuffle(restaurantsByCityId);
	 * restaurantMetaDataRepsoneType.setRestaurantBasedData(restaurantsByCityId)
	 * ; } return restaurantMetaDataRepsoneType; }
	 */

}
