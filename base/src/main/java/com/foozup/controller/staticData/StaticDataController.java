package com.foozup.controller.staticData;

import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.controller.admin.AdminController;
import com.foozup.model.admin.Credentials;
import com.foozup.model.response.UserLoginRepsonse;
import com.foozup.service.admin.AdminService;
@RestController
@RequestMapping(value="/staticData/")
public class StaticDataController {

	@Autowired
	AdminService adminService;
	
	private static final Logger logger = LoggerFactory.getLogger(AdminController.class);
	
	@RequestMapping(value="login", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<UserLoginRepsonse> loginUser(@RequestBody Credentials credentials,HttpServletRequest httpServletRequest) {
		logger.info("ip:"+httpServletRequest.getHeader("X-FORWARDED-FOR")+", userId:"+credentials.getUserId()+", method: login");
		UserLoginRepsonse userLoginResponse=adminService.loginService(credentials);
		return new ResponseEntity<UserLoginRepsonse>(userLoginResponse, HttpStatus.OK);
	}

}
