package com.foozup.service.admin;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.common.Messages;
import com.foozup.helper.admin.AdminServiceHelper;
import com.foozup.model.admin.Credentials;
import com.foozup.model.admin.User;
import com.foozup.model.response.UserLoginRepsonse;

@Service("adminService")
public class AdminServiceImpl implements AdminService {

	@Autowired
	private AdminServiceHelper adminHelper;
	
	private static final Logger logger = LoggerFactory.getLogger(AdminServiceImpl.class);;
	
	@Override
	public UserLoginRepsonse loginService(Credentials credentials) {
		UserLoginRepsonse userLoginResponse=new UserLoginRepsonse();
		if(adminHelper.isLoginRequestValid(credentials)){
			User user=new User();
			if(credentials.getPersist()==1){
				user=adminHelper.validateUserId(credentials.getUserId());
			}else{
				user=adminHelper.validateUserCredentials(credentials);
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
