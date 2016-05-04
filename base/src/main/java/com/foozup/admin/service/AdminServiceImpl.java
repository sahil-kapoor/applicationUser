package com.foozup.admin.service;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.User;
import com.foozup.admin.model.response.UserLoginRepsonse;
import com.foozup.admin.service.helper.IAdminServiceHelper;
import com.foozup.common.Messages;

@Service("adminService")
public class AdminServiceImpl implements IAdminService {

	@Autowired
	private IAdminServiceHelper adminServiceHelper;
	
	private static final Logger logger = LoggerFactory.getLogger(AdminServiceImpl.class);;
	
	@Override
	public UserLoginRepsonse loginService(Credentials credentials) {
		UserLoginRepsonse userLoginResponse=new UserLoginRepsonse();
		if(adminServiceHelper.isLoginRequestValid(credentials)){
			User user=new User();
			if(credentials.getPersist()==1){
				user=adminServiceHelper.validateUserId(credentials.getUserId());
			}else{
				user=adminServiceHelper.validateUserCredentials(credentials);
			}
			if(null ==user){
				logger.debug("userId"+credentials.getUserId()+" , login: failed" );
			}else{
				userLoginResponse.setName(user.getName());
				userLoginResponse.setCode(1);
				userLoginResponse.setMessage(Messages.SUCCESS);
				userLoginResponse.setUserId(user.getId());
			}
		}else{
			userLoginResponse.setCode(0);
			userLoginResponse.setMessage(Messages.LOGIN_INAVLID_CREDENTIALS);
			logger.debug("userId"+credentials.getUserId()+" , login: failed" );
		}
		return userLoginResponse;
	}

	
}
