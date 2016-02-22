package com.foozup.helper.admin;

import org.springframework.beans.factory.annotation.Autowired;

import com.foozup.dao.admin.AdminDao;
import com.foozup.model.admin.Credentials;
import com.foozup.model.admin.User;

public class AdminServceHelperImpl implements AdminServiceHelper{

	@Autowired 
	private AdminDao adminDaoService; 
	
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
