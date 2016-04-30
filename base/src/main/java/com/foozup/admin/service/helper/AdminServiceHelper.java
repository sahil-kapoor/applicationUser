package com.foozup.admin.service.helper;

import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.User;

public interface AdminServiceHelper {

	public boolean isLoginRequestValid(Credentials credentials);
	
	public User validateUserId(Integer userId);
	
	public User validateUserCredentials(Credentials credentials);
}
