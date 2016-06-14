package com.foozup.updates.model.request;

import java.util.List;

import com.foozup.updates.model.Filters;

public class UpdatesByFilterRequest  {
	
	private String userId;
	private List<Integer> locationIds;
	private Integer cityId;
	private Filters filter;
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
	public Filters getFilter() {
		return filter;
	}
	public void setFilter(Filters filter) {
		this.filter = filter;
	}
	
	

}
