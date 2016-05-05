package com.foozup.restaurant.model.request;

import java.util.List;

public class RestauantFindRequestType {

	private String userId;
	private List<Integer> locationIds;
	private Integer cityId;
	public String getUserId() {
		return userId;
	}
	public void setUserId(String userId) {
		this.userId = userId;
	}
	public List<Integer> getLocationIds() {
		return locationIds;
	}
	public void setLocationIds(List<Integer> locationIds) {
		this.locationIds = locationIds;
	}
	public Integer getCityId() {
		return cityId;
	}
	public void setCityId(Integer cityId) {
		this.cityId = cityId;
	}
	
}
