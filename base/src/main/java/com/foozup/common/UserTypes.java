package com.foozup.common;

public enum UserTypes {
	
	USER_ADMIN(0),
	USER_FRANCHISEE(2),
	USER_OWNER(3),
	USER_MANAGER(4),
	USER_TRAINEE(5);
		
	private Integer userType;
	private UserTypes(Integer i) {
			userType = i;
	}

	public Integer getStatusCode() {
		return userType;
	}
}
