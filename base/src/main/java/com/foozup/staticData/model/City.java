package com.foozup.staticData.model;

import java.util.Map;

public class City {

	private int id;
	private String name;
	private String latitude;
	private String longitude;
	private Map<Integer,Area> areas;
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
	public Map<Integer, Area> getAreas() {
		return areas;
	}
	public void setAreas(Map<Integer, Area> areas) {
		this.areas = areas;
	}
	

}
