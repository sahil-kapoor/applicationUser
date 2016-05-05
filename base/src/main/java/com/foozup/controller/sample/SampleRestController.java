package com.foozup.controller.sample;

import java.util.ArrayList;
import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.bind.annotation.RestController;

import com.foozup.admin.model.User;
import com.foozup.restaurant.dao.IRestaurantDao;
import com.foozup.staticData.model.City;

@RestController
@RequestMapping(value="/user/")
public class SampleRestController {

	
	
	@Autowired
	IRestaurantDao restaurantDaoImpl;
	
	@RequestMapping(method = RequestMethod.GET)
	public ResponseEntity<Void> listAllUsers() {
		
		
		ArrayList<Integer> list=new ArrayList<>();
		list.add(1);
		list.add(2);
		
		restaurantDaoImpl.findRestrauantByLocation(list);
		return new ResponseEntity<Void>(HttpStatus.OK);
	}
	
	
	/*
	@Autowired
	UserService userService;  //Service which will do all data retrieval/manipulation work

	@Autowired
	CityDao cityDaoImpl;
	
	@Autow
	IStaticDataDao staticDataDaoImpl;
	//-------------------Retrieve All Users--------------------------------------------------------
	
	@RequestMapping(method = RequestMethod.GET)
	public ResponseEntity<City> listAllUsers() {
		List<User> users = userService.findAllUsers();
		if(users.isEmpty()){
			return new ResponseEntity<City>(HttpStatus.NO_CONTENT);//You many decide to return HttpStatus.NOT_FOUND
		}
		 
		staticDataDaoImpl.getAllAreaLocation("1");
		return new ResponseEntity<City>(staticDataDaoImpl.getAllAreaLocation("1"), HttpStatus.OK);
	}


	//-------------------Retrieve Single User--------------------------------------------------------
	
	@RequestMapping(method = RequestMethod.GET, produces = MediaType.APPLICATION_JSON_VALUE)
	public ResponseEntity<User> getUser(@PathVariable("id") long id) {
		System.out.println("Fetching User with id " + id);
		User user = userService.findById(id);
		if (user == null) {
			System.out.println("User with id " + id + " not found");
			return new ResponseEntity<User>(HttpStatus.NOT_FOUND);
		}
		return new ResponseEntity<User>(user, HttpStatus.OK);
	}

	
	
	//-------------------Create a User--------------------------------------------------------
	
	@RequestMapping( method = RequestMethod.POST)
	public ResponseEntity<Void> createUser(@RequestBody User user, 	UriComponentsBuilder ucBuilder) {
		System.out.println("Creating User " + user.getName());

		if (userService.isUserExist(user)) {
			System.out.println("A User with name " + user.getName() + " already exist");
			return new ResponseEntity<Void>(HttpStatus.CONFLICT);
		}

		userService.saveUser(user);

		HttpHeaders headers = new HttpHeaders();
		headers.setLocation(ucBuilder.path("/user/{id}").buildAndExpand(user.getId()).toUri());
		return new ResponseEntity<Void>(headers, HttpStatus.CREATED);
	}

	
	//------------------- Update a User --------------------------------------------------------
	
	@RequestMapping(value = "{id}", method = RequestMethod.PUT)
	public ResponseEntity<User> updateUser(@PathVariable("id") long id, @RequestBody User user) {
		System.out.println("Updating User " + id);
		
		User currentUser = userService.findById(id);
		
		if (currentUser==null) {
			System.out.println("User with id " + id + " not found");
			return new ResponseEntity<User>(HttpStatus.NOT_FOUND);
		}

		currentUser.setName(user.getName());
		currentUser.setAge(user.getAge());
		currentUser.setSalary(user.getSalary());
		
		userService.updateUser(currentUser);
		return new ResponseEntity<User>(currentUser, HttpStatus.OK);
	}

	//------------------- Delete a User --------------------------------------------------------
	
	@RequestMapping(value = "{id}", method = RequestMethod.DELETE)
	public ResponseEntity<User> deleteUser(@PathVariable("id") long id) {
		System.out.println("Fetching & Deleting User with id " + id);

		User user = userService.findById(id);
		if (user == null) {
			System.out.println("Unable to delete. User with id " + id + " not found");
			return new ResponseEntity<User>(HttpStatus.NOT_FOUND);
		}

		userService.deleteUserById(id);
		return new ResponseEntity<User>(HttpStatus.NO_CONTENT);
	}

	
	//------------------- Delete All User --------------------------------------------------------
	
	@RequestMapping( method = RequestMethod.DELETE)
	public ResponseEntity<User> deleteAllUsers() {
		System.out.println("Deleting All Users");

		userService.deleteAllUsers();
		return new ResponseEntity<User>(HttpStatus.NO_CONTENT);
	}
*/
}