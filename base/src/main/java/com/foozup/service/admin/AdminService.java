package com.foozup.service.admin;

import com.foozup.model.admin.Credentials;
import com.foozup.model.response.UserLoginRepsonse;

public interface AdminService {

	public UserLoginRepsonse loginService(Credentials credentials);
	
}
