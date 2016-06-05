package com.foozup.updates.model.dao;

import java.sql.Date;
import java.sql.Time;

public class UpdateFranchiseDto {
	private Integer id;
	private String updateText;
	private Integer restaurntId;
	private boolean isFranchisee;
	private Integer franchiseeId;
	private boolean isPrimary;
	private Time endTime;
	private Time startTime;
	private Date endDate;
	private Date startDate;
	private String activeDays;
	private boolean isAllTime;

	
	public boolean isAllTime() {
		return isAllTime;
	}
	public void setAllTime(boolean isAllTime) {
		this.isAllTime = isAllTime;
	}
	public Integer getId() {
		return id;
	}
	public void setId(Integer id) {
		this.id = id;
	}
	public String getActiveDays() {
		return activeDays;
	}
	public void setActiveDays(String activeDays) {
		this.activeDays = activeDays;
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
	public boolean isPrimary() {
		return isPrimary;
	}
	public void setPrimary(boolean isPrimary) {
		this.isPrimary = isPrimary;
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
	

}
