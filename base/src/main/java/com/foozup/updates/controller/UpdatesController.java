package com.foozup.updates.controller;

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
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.restaurant.service.IRestaurantService;
import com.foozup.updates.model.UpdateBase;
import com.foozup.updates.model.request.UpdatesByFilterRequest;
import com.foozup.updates.service.IUpdatesService;


@RestController
@RequestMapping(value="/update/")
public class UpdatesController extends BaseController{


	private static final Logger logger = LoggerFactory.getLogger(UpdatesController.class);;

	@Autowired
	private IUpdatesService updateService;
	
	@Autowired
	private IRestaurantService restaurantService;
	
	@RequestMapping(value = "/filterBytag", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<BaseResponse> getUpdateByTag(@RequestBody UpdatesByFilterRequest request) {
		StopWatch stopWatch = new StopWatch();
		stopWatch.start();
		RestaurantMetaDataRepsoneType restResponse=new RestaurantMetaDataRepsoneType();
		Map<String,List<RestaurantBase>> foprmattedRestData=restaurantService.
				formatRestaruantData(restaurantService.getRestaurantByLocation(request.getCityId(),request.getLocationIds()));
		//restaurantService.collateRestaurantData(foprmattedRestData,restResponse);
		Map<String,List<UpdateBase>> updateType=updateService.getUpdatesByRestaurantByFilter(foprmattedRestData ,request.getFilter(),-1 );
		updateService.collateUpdateData(updateType, restResponse);
		BaseResponse response=new BaseResponse();
		response.setData(restResponse);
		response.setMessage("");
		stopWatch.stop();
		logger.info("Service {} , Time Taken : {}ms","/update filter/",stopWatch.getTotalTimeMillis());
		return new ResponseEntity<BaseResponse>((BaseResponse) initializeResponse(response), HttpStatus.OK);
	}
}