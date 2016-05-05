package com.foozup.admin.service;

import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.response.UserLoginRepsonse;

public interface IAdminService {

	public UserLoginRepsonse loginService(Credentials credentials);
	
}
