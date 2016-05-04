package com.foozup.admin.controller;

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
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.admin.model.Credentials;
import com.foozup.admin.model.response.UserLoginRepsonse;
import com.foozup.admin.service.IAdminService;

@RestController
@RequestMapping(value="/admin/")
public class AdminController {

	@Autowired
	IAdminService adminService;
	
	private static final Logger logger = LoggerFactory.getLogger(AdminController.class);
	
	@RequestMapping(value="login", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<UserLoginRepsonse> loginUser(@RequestBody Credentials credentials,HttpServletRequest httpServletRequest) {
		logger.info("ip:"+httpServletRequest.getHeader("X-FORWARDED-FOR")+", userId:"+credentials.getUserId()+", method: login");
		UserLoginRepsonse userLoginResponse=adminService.loginService(credentials);
		return new ResponseEntity<UserLoginRepsonse>(userLoginResponse, HttpStatus.OK);
	}
	
	@RequestMapping(value="link", method = RequestMethod.GET, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<String> getUser(@RequestParam("ownerId") long ownerId,@RequestParam("restId") long restId) {
		
		return new ResponseEntity<String>(String.valueOf(restId), HttpStatus.OK);
	}
}
