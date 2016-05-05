package com.foozup.admin.service.helper;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.admin.dao.IAdminDao;
import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.User;

@Service("adminServiceHelper")
public class AdminServceHelperImpl implements IAdminServiceHelper{

	@Autowired 
	private IAdminDao adminDaoService; 
	
	public boolean isLoginRequestValid(Credentials credentials){
		return true;
		
	}
	
	public User validateUserId(Integer userId){
		return null;
		
	}
	
	public User validateUserCredentials(Credentials credentials){
		return null;
	}
}
