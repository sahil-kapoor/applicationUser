package com.foozup.admin.service;

import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.response.UserLoginRepsonse;

public interface AdminService {

	public UserLoginRepsonse loginService(Credentials credentials);
	
}
