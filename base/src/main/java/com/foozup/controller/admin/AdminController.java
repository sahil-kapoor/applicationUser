package com.foozup.controller.admin;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RequestParam;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.service.admin.AdminService;

@RestController
@RequestMapping(value="/admin/")
public class AdminController {

	@Autowired
	AdminService adminService;
	
	@RequestMapping(value="login", method = RequestMethod.POST, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<String> loginUser(@RequestBody ) {
		
		return new ResponseEntity<String>(String.valueOf(restId), HttpStatus.OK);
	}
	
	@RequestMapping(value="link", method = RequestMethod.GET, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<String> getUser(@RequestParam("ownerId") long ownerId,@RequestParam("restId") long restId) {
		
		return new ResponseEntity<String>(String.valueOf(restId), HttpStatus.OK);
	}
}
