package com.foozup.restaurant.controller;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.restaurant.model.request.RestauantFindRequestType;
import com.foozup.restaurant.model.response.RestaurantMetaDataRepsoneType;
import com.foozup.restaurant.service.IRestaurantService;

@RestController
@RequestMapping(value="/restaurant/")
public class RestaurantController {

	@Autowired
	private IRestaurantService restaurantService;
	
	@RequestMapping(value = "", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<RestaurantMetaDataRepsoneType> getRestaurantByName(@RequestBody RestauantFindRequestType request) {
		RestaurantMetaDataRepsoneType restResponse=restaurantService.getRestaurantByLocation(request);
		return new ResponseEntity<RestaurantMetaDataRepsoneType>(restResponse, HttpStatus.OK);
	}
}
