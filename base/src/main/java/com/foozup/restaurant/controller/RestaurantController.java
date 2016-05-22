package com.foozup.restaurant.controller;

import java.util.List;
import java.util.Map;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.util.StopWatch;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.common.BaseController;
import com.foozup.model.BaseResponse;
import com.foozup.restaurant.model.RestaurantBase;
import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.restaurant.service.IRestaurantService;
import com.foozup.updates.service.IUpdatesService;

@RestController
@RequestMapping(value="/restaurant/")
public class RestaurantController extends BaseController{

	private static final Logger logger = LoggerFactory.getLogger(RestaurantController.class);;
	
	@Autowired
	private IRestaurantService restaurantService;

	@Autowired
	private IUpdatesService updateService;
	
	@RequestMapping(value = "", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<BaseResponse> getRestaurantByName(@RequestBody RestauantFindRequestType request) {
		StopWatch stopWatch = new StopWatch();
		stopWatch.start();
		RestaurantMetaDataRepsoneType restResponse=new RestaurantMetaDataRepsoneType();
		Map<String,List<RestaurantBase>> foprmattedRestData=restaurantService.
				formatRestaruantData(restaurantService.getRestaurantByLocation(request));
		restaurantService.collateRestaurantData(foprmattedRestData,restResponse);
		//HashMap<String,List<UpdateBase>> updateType=updateService.getUpdatesByRestaurant(foprmattedRestData); 
		BaseResponse response=new BaseResponse();
		response.setData(restResponse);
		response.setMessage("");
		stopWatch.stop();
		logger.info("Service {} , Time Taken : {}ms","/restaurant/",stopWatch.getTotalTimeMillis());
		return new ResponseEntity<BaseResponse>((BaseResponse) initializeResponse(response), HttpStatus.OK);
	}
}
