package com.foozup.helper.admin;

import com.foozup.model.admin.Credentials;
import com.foozup.model.admin.User;

public interface AdminServiceHelper {

	public boolean isLoginRequestValid(Credentials credentials);
	
	public User validateUserId(Integer userId);
	
	public User validateUserCredentials(Credentials credentials);
}
