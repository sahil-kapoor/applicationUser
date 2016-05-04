package com.foozup.admin.model;

public class Credentials {
	private String email;
	private String password;
	private String type;
	private String status;
	private Integer persist;
	private Integer userId;
	
	
	public Integer getUserId() {
		return userId;
	}
	public void setUserId(Integer userId) {
		this.userId = userId;
	}
	public String getEmail() {
		return email;
	}
	public void setEmail(String email) {
		this.email = email;
	}
	public String getPassword() {
		return password;
	}
	public void setPassword(String password) {
		this.password = password;
	}
	public String getType() {
		return type;
	}
	public void setType(String type) {
		this.type = type;
	}
	public String getStatus() {
		return status;
	}
	public void setStatus(String status) {
		this.status = status;
	}
	public Integer getPersist() {
		return persist;
	}
	public void setPersist(Integer persist) {
		this.persist = persist;
	}
	
	
	
}
