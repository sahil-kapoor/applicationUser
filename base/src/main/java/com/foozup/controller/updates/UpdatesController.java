package com.foozup.controller.updates;

import java.util.ArrayList;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.model.User;
import com.foozup.service.updates.UpdatesService;

@RestController
@RequestMapping(value="/update/")
public class UpdatesController {

	@Autowired
	UpdatesService ownerService;
	
	@RequestMapping(value = "{emailId}", method = RequestMethod.GET, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<List<User>> getUserBymailId(@PathVariable("emailId") long String) {
		List<User> user=new ArrayList<>();
		return new ResponseEntity<List<User>>(user, HttpStatus.OK);
	}
	
	@RequestMapping(value = "{name}", method = RequestMethod.GET, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<List<User>> getUserByName(@PathVariable("name") String name) {
		System.out.println("Fetching User with name " + name);
		
		return new ResponseEntity<List<User>>(new ArrayList<>(), HttpStatus.OK);
	}
}
