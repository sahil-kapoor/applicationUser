package com.foozup.updates.model.dto;

import java.sql.Date;
import java.sql.Time;

public class BaseUpdateDto {

	private Integer id;
	private String updateText;
	private Integer restaurantId;
	private Time endTime;
	private Time startTime;
	private Date endDate;
	private Date startDate;
	private String activeDays;
	private boolean isAllTime;
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
		return restaurantId;
	}
	public void setRestaurntId(Integer restaurntId) {
		this.restaurantId = restaurntId;
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
	public Date getEndDate() {
		return endDate;
	}
	public void setEndDate(Date endDate) {
		this.endDate = endDate;
	}
	public Date getStartDate() {
		return startDate;
	}
	public void setStartDate(Date startDate) {
		this.startDate = startDate;
	}
	public String getActiveDays() {
		return activeDays;
	}
	public void setActiveDays(String activeDays) {
		this.activeDays = activeDays;
	}
	public boolean isAllTime() {
		return isAllTime;
	}
	public void setAllTime(boolean isAllTime) {
		this.isAllTime = isAllTime;
	}
	
	
}
