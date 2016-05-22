package com.foozup.updates.model;

import java.sql.Time;

public class UpdateBase {

	private Integer id;
	private String updateText;
	private Integer restaurntId;
	private String restaurntName;
	private String city;
	private Integer cityId;
	private Integer areaId;
	private String area;
	private String location;
	private Integer locationId;
	private String photo;
	private Integer cost;
	private boolean isFranchisee;
	private Integer franchiseeId;
	private Integer minDeliveryCost;
	private boolean isPrimary;
	private boolean isTodayOnly;
	private Time endTime;
	private Time startTime;
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getUpdateText() {
		return updateText;
	}
	public void setUpdateText(String updateText) {
		this.updateText = updateText;
	}
	public Integer getRestaurntId() {
		return restaurntId;
	}
	public void setRestaurntId(Integer restaurntId) {
		this.restaurntId = restaurntId;
	}
	public String getRestaurntName() {
		return restaurntName;
	}
	public void setRestaurntName(String restaurntName) {
		this.restaurntName = restaurntName;
	}
	public String getCity() {
		return city;
	}
	public void setCity(String city) {
		this.city = city;
	}
	public Integer getCityId() {
		return cityId;
	}
	public void setCityId(Integer cityId) {
		this.cityId = cityId;
	}
	public Integer getAreaId() {
		return areaId;
	}
	public void setAreaId(Integer areaId) {
		this.areaId = areaId;
	}
	public String getArea() {
		return area;
	}
	public void setArea(String area) {
		this.area = area;
	}
	public String getLocation() {
		return location;
	}
	public void setLocation(String location) {
		this.location = location;
	}
	public Integer getLocationId() {
		return locationId;
	}
	public void setLocationId(Integer locationId) {
		this.locationId = locationId;
	}
	public String getPhoto() {
		return photo;
	}
	public void setPhoto(String photo) {
		this.photo = photo;
	}
	public Integer getCost() {
		return cost;
	}
	public void setCost(Integer cost) {
		this.cost = cost;
	}
	public boolean isFranchisee() {
		return isFranchisee;
	}
	public void setFranchisee(boolean isFranchisee) {
		this.isFranchisee = isFranchisee;
	}
	public Integer getFranchiseeId() {
		return franchiseeId;
	}
	public void setFranchiseeId(Integer franchiseeId) {
		this.franchiseeId = franchiseeId;
	}
	public Integer getMinDeliveryCost() {
		return minDeliveryCost;
	}
	public void setMinDeliveryCost(Integer minDeliveryCost) {
		this.minDeliveryCost = minDeliveryCost;
	}
	public boolean isPrimary() {
		return isPrimary;
	}
	public void setPrimary(boolean isPrimary) {
		this.isPrimary = isPrimary;
	}
	public boolean isTodayOnly() {
		return isTodayOnly;
	}
	public void setTodayOnly(boolean isTodayOnly) {
		this.isTodayOnly = isTodayOnly;
	}
	public Time getEndTime() {
		return endTime;
	}
	public void setEndTime(Time endTime) {
		this.endTime = endTime;
	}
	public Time getStartTime() {
		return startTime;
	}
	public void setStartTime(Time startTime) {
		this.startTime = startTime;
	}
	
	
}
