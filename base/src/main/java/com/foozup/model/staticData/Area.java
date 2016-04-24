package com.foozup.model.staticData;

import java.util.Map;

public class Area {

	private int id;
	private String name;
	private String latitude;
	private String longitude;
	private Map<Integer,Location> locations;
	private int cityId;
	public int getId() {
		return id;
	}
	public void setId(int id) {
		this.id = id;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	public String getLatitude() {
		return latitude;
	}
	public void setLatitude(String latitude) {
		this.latitude = latitude;
	}
	public String getLongitude() {
		return longitude;
	}
	public void setLongitude(String longitude) {
		this.longitude = longitude;
	}
	
	public Map<Integer, Location> getLocations() {
		return locations;
	}
	public void setLocations(Map<Integer, Location> locations) {
		this.locations = locations;
	}
	public int getCityId() {
		return cityId;
	}
	public void setCityId(int cityId) {
		this.cityId = cityId;
	}
	
	
}
