package com.foozup.model.response;

import com.foozup.model.BaseResponse;

public class UserLoginRepsonse extends BaseResponse {
	
	private Integer userId;
	private String name;
	public Integer getUserId() {
		return userId;
	}
	public void setUserId(Integer userId) {
		this.userId = userId;
	}
	public String getName() {
		return name;
	}
	public void setName(String name) {
		this.name = name;
	}
	
	

}
