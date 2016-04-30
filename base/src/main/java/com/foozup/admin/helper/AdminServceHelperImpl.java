package com.foozup.admin.helper;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.foozup.admin.dao.AdminDao;
import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.User;

@Service("adminHelper")
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
