<?php
class ServiceController extends BaseController {
	
	public function showHome() {
		echo "Restaurant App - RESTful Web Services";
		
		$hashids	= new Hashids\Hashids();
		$id			= $hashids->encode(12);
		echo "<pre>"; print_r($id); echo "</pre>";
		$number		= $hashids->decode($id);
		echo "<pre>"; print_r($number); echo "</pre>";
	}
	
	
	public function listUpdatesFromCity() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		$rules = array(			
			'city_id'	=> 'required|exists:cities,id'
			);				
		$validator	= Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$error					= $validator->errors()->all(':message');
			$error					= implode($error, ' ');
			$response->code			= 1;
			$response->message		= $error;
		}else{
			
			$id= Input::get('city_id');
			$userId = Input::get('user_id');
			
			$logs = new Logs();
				$logs->user_id = $userId;			
				$logs->type = 'List Updates From City';				
				$logs->ip_address = Request::getClientIp();
				$logs->save();
		
		$restaurantArray	= Restaurants::where('city_id', '=', $id)->lists('id');
		$restaurantIdArray	= $restaurantArray;
		
		$updateArray	= array();
		$updateIdArray	= array();
		
		if(count($restaurantIdArray) > 0) {
			$areas					= Areas::lists('name', 'id');
			$cities					= Cities::lists('name', 'id');
			$locations				= Locations::lists('name', 'id');
			
			foreach($restaurantIdArray as $restaurant_id) {
				$restaurantUpdateArray	= array();
				$updatesCount			= 0;
				
				//Franchisee
				$franchiseefavorites	= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
												`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`, `franchisee_updates`.`end_date` from `restaurants` 
													left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
													left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
													where `restaurants`.`id` = '.$restaurant_id.' and 
														`franchisee_updates`.`status` = 1 and 
														`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
														`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
													((`franchisee_updates`.`all_time` = 1 and 
														
														(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
														 or 
													(`franchisee_updates`.`all_time` = 0 and 
														
														`franchisee_updates`.`end_time` >= "'.date('H:i:s').'"))
													order by `franchisee_updates`.`updated_at` desc');
				
				if($franchiseefavorites) {
					foreach($franchiseefavorites as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= 0;
							$tempfavoritesUpdatesArray['is_franchisee']= 1;
							
							if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
						}
					}
				}
				
				//Primary
				$favorites			= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
										`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																	left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																	where `restaurants`.`id` = '.$restaurant_id.' and 
																			`restaurant_updates`.`status` = 1 and 
																			`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
																			`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
																			`restaurant_updates`.`is_primary` = 1 and
																			((`restaurant_updates`.`all_time` = 1 and 
																				
																				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																				 or 
																			(`restaurant_updates`.`all_time` = 0 and 
																				
																				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																			order by `restaurant_updates`.`updated_at` desc');
				if($favorites) {
					foreach($favorites as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							
							if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
							
						}
					}
				}
				
				//Today only
				$favoritesToday		= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
										`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																	left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																	where `restaurants`.`id` = '.$restaurant_id.' and 
																			`restaurant_updates`.`status` = 1 and 
																			`restaurant_updates`.`start_date` = "'.date('Y-m-d'). ' 00:00:00" and 
																			`restaurant_updates`.`end_date` = "'.date('Y-m-d'). '" and 
																			`restaurant_updates`.`is_primary` = 0 and
																			((`restaurant_updates`.`all_time` = 1 and 
																				
																				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																				 or 
																			(`restaurant_updates`.`all_time` = 0 and 
																				
																				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																			order by `restaurant_updates`.`updated_at` desc');
				
				if($favoritesToday) {
					foreach($favoritesToday as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							
							if(!isset($updateIdArray) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
						
						}
					}
				}
				//Normal
				if(count($updateIdArray) > 0) {
					$favorites			= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
											`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																		left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																		where `restaurants`.`id` = '.$restaurant_id.' and 
																				`restaurant_updates`.`status` = 1 and 
																				`restaurant_updates`.`id` not in ('.implode(',', $updateIdArray).') and 
																				`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
																				`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
																				`restaurant_updates`.`is_primary` = 0 and
																				((`restaurant_updates`.`all_time` = 1 and 
																					
																					(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																					 or 
																				(`restaurant_updates`.`all_time` = 0 and 
																					
																					`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																				order by `restaurant_updates`.`updated_at` desc');
				} else {
					$favorites			= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
										`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																	left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																	where `restaurants`.`id` = '.$restaurant_id.' and 
																			`restaurant_updates`.`status` = 1 and 
																			`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
																			`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
																			`restaurant_updates`.`is_primary` = 0 and
																			((`restaurant_updates`.`all_time` = 1 and 
																				
																				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																				 or 
																			(`restaurant_updates`.`all_time` = 0 and 
																				
																				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																			order by `restaurant_updates`.`updated_at` desc');
				}
				if($favorites) {
					foreach($favorites as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							
							if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
							
						}
					}
				}
				if(count($restaurantUpdateArray) > 0) {
					$updateArray[]	= array('updates' => $restaurantUpdateArray, 'count' => ($updatesCount-1));
				}
			}
			
			if($updateArray > 0) {
				$response->message	= 'Success';
				$response->code		= 0;
				$response->results	= $updateArray;
			} else {
				$response->message	= 'No Records';
				$response->code		= 0;
			}
		
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'City Id'=>$id,'User ID'=>$userId,'Name' => 'List City Updates Response');
		Log::info('List City Updates Response',  $logData);
		//	End: Log
		
		//echo "<pre>"; print_r($response); echo "</pre>";
		return Response::json($response);
		}
	}

	public function doLogin() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Login Request', 'Inputs' => Input::all());
		Log::info('Login Request',  $logData);
		//	End: Log
		
		// validate the info, create rules for the inputs
		$rules = array(
			'email'		=> 'required|email', // make sure the email is an actual email
			'password'	=> 'required|alphaNum|min:6|max:30' // password can only be alphanumeric and has to be greater than 6 characters
		);
		// run the validation rules on the inputs from the form
		$validator	= Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error	= $validator->errors()->all(':message');
			$error	= implode($error, ' ');
			
			$response->code		= 1;
			$response->message	= $error;
		} else {
			// create our user data for the authentication
			$adminuserdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=> Input::get('password'),
				'status'	=> 1,
				'type'		=> 0,
			);
			$franchiseeuserdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=> Input::get('password'),
				'status'	=> 1,
				'type'		=> 2,
			);
			$owneruserdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=> Input::get('password'),
				'status'	=> 1,
				'type'		=> 3,
			);
			$manageruserdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=> Input::get('password'),
				'status'	=> 1,
				'type'		=> 4,
			);
			$traineeuserdata = array(
				'email' 	=> Input::get('email'),
				'password' 	=> Input::get('password'),
				'status'	=> 1,
				'type'		=> 5,
			);
			
			$rememberMe	= (Input::get('persist') == 1) ? 'true' : 'false';
			// attempt to do the login
			if (Auth::attempt($adminuserdata, $rememberMe) || Auth::attempt($franchiseeuserdata, $rememberMe) || Auth::attempt($owneruserdata, $rememberMe) || Auth::attempt($manageruserdata, $rememberMe) || Auth::attempt($traineeuserdata, $rememberMe)) {
				Auth::login(User::find(Auth::user()->id));
				$user	= User::find(Auth::user()->id);
				$userId	= $user->id;
				
				$response->code		= 0;
				$response->message	= 'Success';
				$response->user_id	= $userId;
				$response->name		= $user->firstname;
				
				
				$logs = new Logs();									
					$logs->type = 'Login Success';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
			} else {
				$response->message	= 'Invalid Email or Password';
				$response->code		= 1;
				$logs = new Logs();										
					$logs->type = 'Login Failed';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
			}
		}
		
		//	Start: Log
		$logData 	= array('Name'=>'Login');
		Log::info('Login Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	public function doFBRegister() {
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Register Request', 'Inputs' => Input::all());
		Log::info('Register Request',  $logData);
		//	End: Log*/
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		// validate the info, create rules for the inputs
		$rules = array(
			'uid'		=> 'required',
			'name'		=> 'required|min:3|max:30'
		);
		$messages	= array('alpha_spaces' => 'Name must be alphanumeric');
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules, $messages);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			
			$error	= $validator->errors()->all(':message');
			$error	= implode($error, ' ');
			
			$response->message	= $error;
			$response->code		= 1;
			
		} else {
			
			$user	= User::where('uid', '=', Input::get('uid'))->count();
			
			if($user <= 0) {
				$user			= new User;
				$user->uid		= Input::get('uid');
				$user->firstname= Input::get('name');
				
				if(Input::has('email')) {
					$user->email	= Input::get('email');
				}
				
				$user->password	= Hash::make('passfbpass');
				$user->status	= 1;
				$user->type		= 0;
				$user->save();
				$userId			= $user->id;
				
								
			} else {
				$user	= User::where('uid', '=', Input::get('uid'))->first();
				$userId			= $user->id;
			}
			
			$response->code			= 0;
			$response->message		= 'Success';
			$response->user_id		= $userId;
			$logs =new Logs();
			$logs->user_id = $userId;
			$logs->type = 'Fb Login';
			$logs->ip_address = Request::getClientIp();
			$logs->save();
			
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'FB Register Response');
		Log::info('Register Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function doRegister() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Register Request', 'Inputs' => Input::all());
		Log::info('Register Request',  $logData);
		//	End: Log*/
		
		// validate the info, create rules for the inputs
		$rules = array(
			'name'		=> 'required|alpha_spaces|min:3|max:30',
			'mobile'	=> 'digits:10',
			'email'		=> 'required|email|unique:users,email', // make sure the email is an actual email
			'password'	=> 'required|alphaNum|min:6|max:30' // password can only be alphanumeric and has to be greater than 6 characters
		);
		$messages	= array('alpha_spaces' => 'Name must be alphanumeric');
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules, $messages);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error	= $validator->errors()->all(':message');
			$error	= implode($error, ' ');
			
			$response->message	= $error;
			$response->code		= 1;
		} else {
			$user			= new User;
			$user->firstname= Input::get('name');
			$user->email	= Input::get('email');
			$user->password	= Hash::make(Input::get('password'));
			$user->status	= 1;
			$user->type		= 0;
			if(Input::has('mobile')) {
				$user->mobile	= Input::get('mobile');
			}
			$user->save();
			
			
			//	encode
			$hashids	= new Hashids\Hashids();
			$encodedid	= $hashids->encode($user->id);
			$data		= array('name' => $user->email, 'password' => Input::get('password'), 'id' => $encodedid);
			Mail::send('emails.register', $data, function($message) use ($user)
			{
				$message->to($user->email, $user->username)->subject('Confirmation');
			});
			
			$response->code			= 0;
			$response->message		= 'Success';
			
		}
		
		$logs = new Logs();					
					$logs->type = 'Login Failed';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Register Response');
		Log::info('Register Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function doForgotPassword() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Forgot Password Request', 'Inputs' => Input::all());
		Log::info('Forgot Password Request',  $logData);
		//	End: Log
		
		// validate the info, create rules for the inputs
		$rules = array(
			'email'    => 'required|email', // make sure the email is an actual email
		);
		
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error	= $validator->errors()->all(':message');
			$error	= implode($error, ' ');
			
			$response->message	= $error;
			$response->code		= 1;
			
				$logs = new Logs();
					$logs->type = $error;
					$logs->ip_address = Request::getClientIp();
					$logs->save();
			
			//	Start: Log
			$logData 	= array('IP Address' => Request::getClientIp(), 'Error' => $error);
			Log::info('Forgot Password Response',  $logData);
			//	End: Log
			
			return Response::json($response);
			
		} else {
			$user	= User::where('email', '=', Input::get('email'))->first();
			Config::set('auth.reminder.email', 'emails.auth.front-end-reminder');
			switch ( $response2 = Password::remind(Input::only('email'), function($message) {$message->subject('Password reset for your account - action needed now');}) )
			{
				case Password::INVALID_USER: {
					//return Redirect::back()->with('message', 'Invalid Email');
					$response->code		= 1;
					$response->message	= 'The email address is not a Registered address.';
					$logs = new Logs();
					$logs->type = 'Invalid Email Address';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
					//	Start: Log
					$logData 	= array('IP Address' => Request::getClientIp(), 'email'=>Input::get('email'),'Name' => 'Invalid Email Address');
					Log::info('Forgot Password Response',  $logData);
					//	End: Log
					
					return Response::json($response);
				}
				case Password::REMINDER_SENT: {
					//return Redirect::back()->with('message', 'We sent you a mail to reset password. Please check your inbox.');
					$response->code		= 0;
					$response->message	= 'Password Reset email has been sent to your registered email address.';
					$logs = new Logs();
					$logs->type = 'Forgot Password Request';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
					//	Start: Log
					$logData 	= array('IP Address' => Request::getClientIp(), 'email'=>Input::get('email'),'Name' => 'Forgot Password Email Sent');
					Log::info('Forgot Password Response',  $logData);
					//	End: Log
					
					
					return Response::json($response);
				}
			}
		}
	}
	
	public function doChangePassword() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Change Password Request', 'Inputs' => Input::all());
		Log::info('Change Password Request',  $logData);
		//	End: Log
		
		// validate the info, create rules for the inputs
		$rules = array(
			'user_id'			=> 'required|integer',
			'current_password'	=> 'required|alphaNum|min:6|max:30',
			'confirm_password'	=> 'required|alphaNum|min:6|max:30||different:current_password'
		);
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error	= $validator->errors()->all(':message');
			$error	= implode($error, ' ');
			
			$response->message	= $error;
			$response->code		= 1;
			$logs = new Logs();
					$logs->user_id = Input::get('user_id');
					$logs->type = 'Change Password Error';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
		} else {
			$user	= User::find(Input::get('user_id'));
			if($user) {
				if (Hash::check(Input::get('current_password'), $user->password)) {
					$user->password	= Hash::make(Input::get('confirm_password'));
					$user->save();
					$response->message	= 'Success';
					$response->code		= 0;
					
					$logs = new Logs();
					$logs->user_id = Input::get('user_id');
					$logs->type = 'Change Password';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
				} else {
				    $response->message	= 'Current Password does not match';
					$response->code		= 1;
					$logs = new Logs();
					$logs->user_id = Input::get('user_id');
					$logs->type = 'Current Password does not match';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
				}
			} else {
				$response->message	= 'Invalid UserID';
				$response->code		= 1;
				$logs = new Logs();
					$logs->user_id = Input::get('user_id');
					$logs->type = 'Invalid User ID';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
			}
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'User ID'=>Input::get('user_id'),'Name' => 'Change Password Response');
		Log::info('Change Password Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function showProfile($id) {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'View Profile Request', 'Inputs' => Input::all());
		Log::info('View Profile Request',  $logData);
		//	End: Log*/
		
		if(isset($id) && trim($id) != '') {
			$user	= User::find($id);
			
			if($user) {
				$response->message	= 'Success';
				$response->code		= 0;
				
				$profileObj	= new stdClass();
				$profileObj->name		= $user->firstname;
				$profileObj->email		= $user->email;
				$profileObj->user_type	= $user->user_type;
				$profileObj->updated_at	= $user->updated_at;
				$profileObj->created_at	= $user->created_at;
				$response->profile		= $profileObj;
			} else {
				$response->message	= 'Invalid UserID';
				$response->code		= 1;
				$logs = new Logs();
					$logs->user_id = $user;
					$logs->type = 'Invalid UserID';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
			}
		} else {
			$response->message	= 'Invalid UserID';
			$response->code		= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'User ID'=>$user,'Name' => 'View Profile Response');
		Log::info('View Profile Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function updateProfile() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Update Profile Request', 'Inputs' => Input::all());
		Log::info('Update Profile Request',  $logData);
		//	End: Log*/
		
		if (Input::has('user_id')) {
			
			$id			= Input::get('user_id');
			// validate the info, create rules for the inputs
			$rules = array(
				'name'		=> 'required|alpha_spaces|min:3|max:30',
				'email'		=> 'required|email|unique:users,email,'.$id,
				'mobile'	=> 'digits:10',
			);
			$messages	= array('alpha_spaces' => 'Name must be alphanumeric');
			// run the validation rules on the inputs from the form
			$validator = Validator::make(Input::all(), $rules, $messages);
			
			// if the validator fails, redirect back to the form
			if ($validator->fails()) {
				$error	= $validator->errors()->all(':message');
				$error	= implode($error, ' ');
				
				$response->message	= $error;
				$response->code		= 1;
					$logs = new Logs();
					$logs->user_id = $id;
					$logs->type = 'Update Profile Error';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
			} else {
				$user	= User::find($id);
				
				if($user) {
					$user->firstname	= Input::get('name');
					$user->email		= Input::get('email');
					$result				= $user->save();
					if(Input::has('mobile')) {
						$user->mobile	= Input::get('mobile');
					}
					if($result) {
						$response->message	= 'Success';
						$response->code		= 0;
						$logs = new Logs();
					$logs->user_id = $user;
					$logs->type = 'Update Profile Success';
					$logs->ip_address = Request::getClientIp();
					$logs->save();
					} else {
						$response->message	= 'General System Failure';
						$response->code		= 1;
						$logs = new Logs();
						$logs->user_id = $user;
						$logs->type = 'General System Failure Update Profile';
						$logs->ip_address = Request::getClientIp();
						$logs->save();
					}
				} else {
					$response->message	= 'Invalid UserID';
					$response->code		= 1;
					$logs = new Logs();
					$logs->user_id = $user;
						$logs->type = 'Invalid User ID';
						$logs->ip_address = Request::getClientIp();
						$logs->save();
				}
			}
		} else {
			$response->message	= 'Invalid UserID';
			$response->code		= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'User ID'=>$id ,'Name' => 'Update Profile Response');
		Log::info('Update Profile Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function updateOwnerUser() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		// validate the info, create rules for the inputs
		$rules = array(
			'user_id'	=> 'required|integer',
			'user_type'	=> 'required|integer'
		);
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error	= $validator->errors()->all(':message');
			$error	= implode($error, ' ');
			
			$response->message	= $error;
			$response->code		= 1;
		} else {
			$user	= User::find(Input::get('user_id'));
			
			if($user) {
				$user->user_type	= Input::get('user_type');
				$result				= $user->save();
				
				if($result) {
					$response->message	= 'Success';
					$response->code		= 0;
				} else {
					$response->message	= 'General System Failure';
					$response->code		= 1;
				}
			} else {
				$response->message	= 'Invalid UserID';
				$response->code		= 1;
			}
		}
		return Response::json($response);
	}
	
	
	
	public function listCities() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Cities Request', 'Inputs' => Input::all());
		Log::info('List Cities Request',  $logData);
		//	End: Log*/
		
		$cities	= Cities::all();
		if($cities->count() > 0) {
			$citiesArray	= array();
			foreach($cities as $key => $value) {
				$citiesArray[$value->id]['id']	= $value->id;
				$citiesArray[$value->id]['name']= $value->name;
				$citiesArray[$value->id]['lat']	= $value->lat;
				$citiesArray[$value->id]['lon']	= $value->lon;
			}
			$response->message	= 'Success';
			$response->code		= 0;
			$response->cities	= $citiesArray;
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Cities Response');
		Log::info('List Cities Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function listAreas() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Areas Request', 'Inputs' => Input::all());
		Log::info('List Areas Request',  $logData);
		//	End: Log
		
		$query	= DB::table('areas');
		$query	= $query->leftJoin('cities', 'cities.id', '=', 'areas.city_id');
		$query	= $query->select('areas.id', 'areas.name', 'cities.name as city_name', 'areas.lat', 'areas.lon');
		$areas	= $query->get();
		
		if($areas) {
			$areasArray	= array();
			foreach($areas as $key => $value) {
				$areasArray[$value->id]['id']	= $value->id;
				$areasArray[$value->id]['name']	= $value->name;
				$areasArray[$value->id]['city_name']	= $value->city_name;
				$areasArray[$value->id]['lat']	= $value->lat;
				$areasArray[$value->id]['lon']	= $value->lon;
			}
			$response->message	= 'Success';
			$response->code		= 0;
			$response->areas	= $areasArray;
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Areas Response');
		Log::info('List Areas Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	public function listAreasByCityId($id) {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		$areas				= Areas::where('city_id', '=', $id)->orderBy('name')->get();
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Areas By CityID Request', 'Inputs' => Input::all());
		Log::info('List Areas By CityID Request',  $logData);
		//	End: Log
		
		if($areas) {
			$areasArray	= array();
			$resIdArray	= array();
			foreach($areas as $key => $value) {
				$tempArray	= array();
				$tempArray['id']	= $value->id;
				$tempArray['name']	= $value->name;
				$tempArray['lat']	= $value->lat;
				$tempArray['lon']	= $value->lon;
				if(!isset($resIdArray[$value->id])) {
					$areasArray[]			= $tempArray;
					$resIdArray[$value->id]	= $value->id;
				}
			}
			$response->message	= 'Success';
			$response->code		= 0;
			$response->areas	= $areasArray;
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Areas By CityID Response', 'Output' => $response);
		Log::info('List Areas By CityID Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	
	public function viewRestaurant($id) {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'View Restaurant Request', 'Inputs' => Input::all());
		Log::info('View Restaurant Request',  $logData);
		//	End: Log
		
		if(isset($id) && trim($id) != '') {
			$restaurant	= Restaurants::find($id);
			
			if($restaurant) {
				$response->message		= 'Success';
				$response->code			= 0;
				
				$resObj					= new stdClass();
				$resObj->name			= $restaurant->name;
				$resObj->phone			= $restaurant->phone;
				$resObj->phone2         = $restaurant->phone2;
				$resObj->address		= $restaurant->address;
				$city					= Cities::find($restaurant->city_id);
				$resObj->city			= (isset($city->name)) ? $city->name : '';
				$area					= Areas::find($restaurant->area_id);
				$resObj->area			= (isset($area->name)) ? $area->name : '';
				
				$location				= Locations::find($restaurant->location_id);
				$resObj->location		= (isset($location->name)) ? $location->name : '';
				$resObj->location_id	= (isset($location->id)) ? $location->id : '';
				
				$resObj->cost_for_2	= $restaurant->cost_for_2;
				$resObj->min_delivery_cost	= $restaurant->min_delivery_cost;
				$resObj->delivery_info  = $restaurant->delivery_info;
				$resObj->start_time		= $restaurant->start_time;
				$resObj->end_time		= $restaurant->end_time;
				$resObj->start_time_2	= $restaurant->start_time_2;
				$resObj->end_time_2		= $restaurant->end_time_2;
				$resObj->speciality		= $restaurant->speciality;
				if($restaurant->photo != '') {
					$resObj->photo		= SITE_PATH.'data/restaurant/'.$restaurant->photo;
				} else {
					$resObj->photo		= SITE_PATH.'img/res-bg.jpg';
				}
				$restaurantCuisines		= RestaurantCuisines::where('restaurant_id', '=', $restaurant->id)->lists('cuisine_id');
				if($restaurantCuisines) {
					$cuisines			= Cuisines::whereIn('id', $restaurantCuisines)->orderBy('name')->lists('name');
					$cuisines			= implode(', ', $cuisines);
					$resObj->cuisines	= $cuisines;
				}
				
				$restaurantFeatures		= RestaurantFeatures::where('restaurant_id', '=', $restaurant->id)->lists('feature_id');
				if($restaurantFeatures) {
					$features			= Features::whereIn('id', $restaurantFeatures)->orderBy('name')->lists('name');
					$features			= implode(', ', $features);
					$resObj->features	= $features;
				}
				
				$restaurantPayments		= RestaurantPaymentMethods::where('restaurant_id', '=', $restaurant->id)->lists('payment_id');
				if($restaurantPayments) {
					$payments			= Payments::whereIn('id', $restaurantPayments)->orderBy('name')->lists('name');
					$payments			= implode(', ', $payments);
					$resObj->payments	= $payments;
				}
				
				$restaurantAreas		= RestaurantAreas::where('restaurant_id', '=', $restaurant->id)->lists('area_id');
				if($restaurantAreas) {
					$areas				= Areas::whereIn('id', $restaurantAreas)->orderBy('name')->lists('name');
					$areas				= implode(', ', $areas);
					$resObj->area_served= $areas;
				}
				
				$resObj->is_franchisee	= $restaurant->is_franchisee;
				if($restaurant->is_franchisee == 1) {
					$franchisee					= User::find($restaurant->franchisee_id);
					$resObj->franchisee_name	= $franchisee->franchisee_name;
					if($franchisee->franchisee_logo != '') {
						$resObj->franchisee_logo	= SITE_PATH.'data/franchisee/'.$franchisee->franchisee_logo;
					}
				}
				$response->restuarant	= $resObj;
			} else {
				$response->message		= 'Invalid RestaurantID';
				$response->code			= 1;
			}
		} else {
			$response->message			= 'Invalid RestaurantID';
			$response->code				= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'RestaurantID'=>$id,'Name' => 'View Restaurant Response', 'Output' => $response);
		Log::info('View Restaurant Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function addFavorites() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Add Favorites Request', 'Inputs' => Input::all());
		Log::info('Add Favorites Request',  $logData);
		//	End: Log*/
		
		// validate the info, create rules for the inputs
		$rules = array(
			'user_id'		=> 'required|exists:users,id', // make sure the email is an actual email
			'restaurant_id'	=> 'required|exists:restaurants,id' // password can only be alphanumeric and has to be greater than 6 characters
		);
		// run the validation rules on the inputs from the form
		$validator	= Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error					= $validator->errors()->all(':message');
			$error					= implode($error, ' ');
			$response->code			= 1;
			$response->message		= $error;
		} else {
			$favorite				= UserFavorites::where('user_id', '=', Input::get('user_id'))->where('restaurant_id', '=', Input::get('restaurant_id'))->count();
			if($favorite == 0) {
				$favorite				= new UserFavorites;
				$favorite->user_id		= Input::get('user_id');
				$favorite->restaurant_id= Input::get('restaurant_id');
				$favorite->save();
			}
			
			$logs = new Logs();
			$logs->user_id = Input::get('user_id');
			$logs->restaurant_id = Input::get('restaurant_id');
			$logs->type = 'Add To Favourites';
			$logs->save();
			$response->code			= 0;
			$response->message		= 'Success';
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'User ID'=>Input::get('user_id'),'Restaurant ID'=>Input::get('restaurant_id'),'Name' => 'Add Favorites Response');
		Log::info('Add Favourites Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function removeFavorites() {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Remove Favorites Request', 'Inputs' => Input::all());
		Log::info('Remove Favorites Request',  $logData);
		//	End: Log*/
		
		// validate the info, create rules for the inputs
		$rules = array(
			'user_id'		=> 'required|exists:users,id', // make sure the email is an actual email
			'restaurant_id'	=> 'required|exists:restaurants,id' // password can only be alphanumeric and has to be greater than 6 characters
		);
		// run the validation rules on the inputs from the form
		$validator	= Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			$error					= $validator->errors()->all(':message');
			$error					= implode($error, ' ');
			$response->code			= 1;
			$response->message		= $error;
		} else {
			$favorite				= UserFavorites::where('user_id', '=', Input::get('user_id'))->where('restaurant_id', '=', Input::get('restaurant_id'))->delete();
			$response->code			= 0;
			$response->message		= 'Success';
		}
		$logs = new Logs();
			$logs->user_id = Input::get('user_id');
			$logs->restaurant_id = Input::get('restaurant_id');
			$logs->type = 'Remove From Favourites';
			$logs->save();
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'User ID'=>Input::get('user_id'),'Restaurant ID'=>Input::get('restaurant_id'),'Name' => 'Remove Favorites Response');
		Log::info('Remove Favorites Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function listFavorites($id) {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Favorites Request', 'Inputs' => Input::all());
		Log::info('List Favorites Request',  $logData);
		//	End: Log
		
		$query				= DB::table('user_favorites');
		$query				= $query->leftJoin('restaurants', 'restaurants.id', '=', 'user_favorites.restaurant_id');
		$query				= $query->leftJoin('users', 'users.id', '=', 'user_favorites.user_id');
		$query				= $query->select('restaurants.id', 'restaurants.name', 'restaurants.min_delivery_cost','restaurants.cost_for_2','restaurants.photo', 'restaurants.address', 'restaurants.city_id', 'restaurants.area_id', 'restaurants.location_id', 'restaurants.phone');
		$query				= $query->where('users.id', '=', $id);
		$favorites			= $query->get();
		
		$locations			= Locations::lists('name', 'id');
		
		if($favorites) {
			$areas	= Areas::lists('name', 'id');
			$cities	= Cities::lists('name', 'id');
			$favoritesArray	= array();
			$resIdArray		= array();
			
			foreach($favorites as $key => $value) {
				$tempfavoritesUpdatesArray				= array();
				$tempfavoritesUpdatesArray['id']		= $value->id;
				$tempfavoritesUpdatesArray['name']		= $value->name;
				if($value->photo != '') {
					$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
				} else {
					$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
				}
				$tempfavoritesUpdatesArray['address']	= $value->address;
				$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
				$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
				$tempfavoritesUpdatesArray['location']	= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
				$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
				
				$tempfavoritesUpdatesArray['cost_for_2']	= $value->cost_for_2;
				$tempfavoritesUpdatesArray['min_delivery_cost']	= $value->min_delivery_cost;
				
				$tempfavoritesUpdatesArray['phone']	= $value->phone;
				
				if(!isset($resIdArray[$value->id])) {
					$favoritesArray[]			= $tempfavoritesUpdatesArray;
					$resIdArray[$value->id]		= $value->id;
				}
			}
			$response->message	= 'Success';
			$response->code		= 0;
			$response->favorites= $favoritesArray;
			
			$logs = new Logs();
			$logs->user_id = $id;
			$logs->type = 'List Favourites';			
			$logs->save();
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'User ID'=>$id, 'Name' => 'List Favorites Response');
		Log::info('List Favorites Response',  $logData);
		//	End: Log
		return Response::json($response);
	}
	
	public function listUpdatesFromFavorites($id) {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Updates From Favorites Request', 'Inputs' => Input::all());
		Log::info('List Updates From Favorites Request',  $logData);
		//	End: Log*/
		
		$favorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
		`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, `restaurants`.`name`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`photo`, `restaurants`.`address`, restaurants.location_id,`restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `user_favorites` left join `restaurants` on `restaurants`.`id` = `user_favorites`.`restaurant_id` 
left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
	where `user_favorites`.`user_id` = '.$id.' and 
		`restaurant_updates`.`status` = 1 and 
		`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
		`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
		(
			(`restaurant_updates`.`all_time` = 1 and 
			
			(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
		 or 
			(`restaurant_updates`.`all_time` = 0 and 
			`restaurant_updates`.`end_time` >= "'.date('H:i:s').'")
		) 
		order by `restaurants`.`id` asc' );
		
		$franchiseefavorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
		`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`, `restaurants`.`name`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`photo`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, restaurants.location_id,`restaurants`.`phone`, `restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`, `franchisee_updates`.`end_date` from `user_favorites` 
	left join `restaurants` on `restaurants`.`id` = `user_favorites`.`restaurant_id` 
	left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
	left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
	where `user_favorites`.`user_id` = '.$id.' and 
			`franchisee_updates`.`status` = 1 and 
			`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
			`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
			((`franchisee_updates`.`all_time` = 1 and 
				
				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'")) 
			or 
			(`franchisee_updates`.`all_time` = 0 and 
				
				`franchisee_updates`.`end_time` >= "'.date('H:i:s').'") 
			)
			group by `franchisee_updates`.`id` 
			order by `restaurants`.`id` asc');
		
		if($favorites || $franchiseefavorites) {
			$areas					= Areas::lists('name', 'id');
			$cities					= Cities::lists('name', 'id');
			$locations				= Locations::lists('name', 'id');
			
			$primaryCount			= 0;
			$tempRestaurantId		= 0;
			$favoritesUpdatesArray	= array();
			$recordsCount			= 0;
			$resIdArray				= array();
			
			if($favorites) {
				foreach($favorites as $key => $value) {
					if($value->activation_type == 1) {
						$day		= date('w')+1;
						$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
					} else {
						$updateDays	= 1;
					}
					
					if($updateDays == 1) {
						if($tempRestaurantId != $value->id) {
							$primaryCount		= 0;
							$tempRestaurantId	= $value->id;
						}
						if($value->is_primary == 1) {
							$primaryCount++;
						}
						if($primaryCount <= 2) {
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']	= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']	= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							//$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
							
							if(!isset($resIdArray[$value->id])) {
								$favoritesUpdatesArray[]			= $tempfavoritesUpdatesArray;
								$resIdArray[$value->id]				= $value->id;
							}
							$recordsCount++;
						}
					}
				}
			}
			if($franchiseefavorites) {
				foreach($franchiseefavorites as $key => $value) {
					if($value->activation_type == 1) {
						$day		= date('w')+1;
						$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
					} else {
						$updateDays	= 1;
					}
					if($updateDays == 1) {
						$tempfavoritesUpdatesArray				= array();
						$tempfavoritesUpdatesArray['id']		= $value->id;
						$tempfavoritesUpdatesArray['name']		= $value->name;
						if($value->photo != '') {
							$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
						} else {
							$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
						}
						$tempfavoritesUpdatesArray['address']	= $value->address;
						$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
						$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
						
						$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
						$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
						
						$tempfavoritesUpdatesArray['cost_for_2']	= $value->cost_for_2;
						$tempfavoritesUpdatesArray['min_delivery_cost']	= $value->min_delivery_cost;
						
						$tempfavoritesUpdatesArray['phone']		= $value->phone;
						$tempfavoritesUpdatesArray['speciality']= $value->speciality;
						$tempfavoritesUpdatesArray['updates']	= $value->updates;
						$starteAt								= explode('-', $value->start_date);
						$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
						$endedAt								= explode('-', $value->end_date);
						$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
						
						if($value->all_time == 1) {
							$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
							if($value->res_end_time_2 != '') {
								$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
							} else {
								$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
							}
						} else {
							$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
							$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
						}
						
						$tempfavoritesUpdatesArray['is_primary']= 0;
						$tempfavoritesUpdatesArray['is_franchisee']= 1;
						//$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
						
						if(!isset($resIdArray[$value->id])) {
							$favoritesUpdatesArray[]			= $tempfavoritesUpdatesArray;
							$resIdArray[$value->id]				= $value->id;
						}
						$recordsCount++;
					}
				}
			}
			if($recordsCount > 0) {
				if(count($favoritesUpdatesArray) > 0) {
					shuffle($favoritesUpdatesArray);
				}
				$response->message	= 'Success';
				$response->code		= 0;
				$response->updates	= $favoritesUpdatesArray;
				$logs = new logs();
				$logs->user_id = $id;
				$logs->type  = 'List Updates From Favorites';
				$logs->ip_address =Request::getClientIp();
				$logs->save();
			} else {
				$response->message	= 'No Records';
				$response->code		= 0;
			}
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'Name' => 'List Updates From Favorites Response', 'User ID' => $id);
		Log::info('List Updates From Favorites Response',  $logData);
		//	End: Log
		
		
		//echo "<pre>"; print_r($response); echo "</pre>";
		return Response::json($response);
	}
	
	public function showUpdatesById($id) {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Show Updates By ID Request', 'Inputs' => Input::all());
		Log::info('Show Updates By ID Request',  $logData);
		//	End: Log*/
		
		$query	= DB::table('restaurant_updates');
		$query	= $query->leftJoin('restaurants', 'restaurants.id', '=', 'restaurant_updates.restaurant_id');
		$query	= $query->select('restaurant_updates.id as update_id', 'restaurants.id', 'restaurants.name', 'restaurants.photo', 'restaurants.min_delivery_cost','restaurants.cost_for_2','restaurants.address', 'restaurants.city_id', 'restaurants.area_id', 'restaurants.location_id', 'restaurants.phone', 'restaurants.speciality', 'restaurant_updates.updates', 'restaurant_updates.activation_type', 'restaurant_updates.start_date', 'restaurant_updates.end_date');
		$query	= $query->where('restaurant_updates.id', '=', $id);
		$update	= $query->first();
		
		if($update) {
			$areas						= Areas::lists('name', 'id');
			$cities						= Cities::lists('name', 'id');
			$locations					= Locations::lists('name', 'id');
			
			$tempArray					= new stdClass();
			$tempArray->update_id		= $update->update_id;
			$tempArray->restaurant_id	= $update->id;
			$tempArray->name			= $update->name;
			if($update->photo != '') {
				$tempArray->photo		= SITE_PATH.'data/restaurant/'.$update->photo;
			} else {
				$tempArray->photo		= SITE_PATH.'img/res-bg.jpg';
			}
			$tempArray->address			= $update->address;
			$tempArray->city			= (isset($cities[$update->city_id])) ? $cities[$update->city_id] : '';
			$tempArray->area			= (isset($areas[$update->area_id])) ? $areas[$update->area_id] : '';
			
			$tempArray->location		= $update->location_id;
			$tempArray->location_id		= (isset($locations[$update->location_id])) ? $locations[$update->location_id] : '';
			
			$tempArray->phone			= $update->phone;
			$tempArray->speciality		= $update->speciality;
			
			$tempArray->cost_for_2	= $update->cost_for_2;
			$tempArray->min_delivery_cost	= $update->min_delivery_cost;
			
			$tempArray->updates			= $update->updates;
			
			$startedAt					= explode('-', $update->start_date);
			$tempArray->start_date		= $startedAt[2].'/'.$startedAt[1].'/'.$startedAt[0];
			$endedAt					= explode('-', $update->end_date);
			$tempArray->end_date		= $endedAt[2].'/'.$endedAt[1].'/'.$endedAt[0];
			if($update->activation_type == 1) {
				$updateDays				= UpdateDays::where('update_id', '=', $update->update_id)->orderBy('days')->lists('days');
				$daysArray				= array(1 => 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
				$daysTextArray			= array();
				if($updateDays) {
					foreach($updateDays as $key => $value) {
						$daysTextArray[]	= $daysArray[$value];
					}
					$tempArray->days	= implode(',', $daysTextArray);
				}
			}
			$response->update			= $tempArray;
			$response->message			= 'Success';
			$response->code				= 0;
		} else {
			$response->message			= 'Invalid Update ID';
			$response->code				= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'Update ID'=>$id, 'Name' => 'Show Updates By ID Response', 'Output' => $response);
		Log::info('Show Updates By ID Response',  $logData);
		//	End: Log
		
		//echo "<pre>"; print_r($response); echo "</pre>";
		return Response::json($response);
	}
	
	public function showFranchiseeUpdatesById($id) {
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'Show Franchisee Updates By ID Request', 'Inputs' => Input::all());
		Log::info('Show Franchisee Updates Request',  $logData);
		//	End: Log*/
		
		$query	= DB::table('franchisee_updates');
		$query	= $query->leftJoin('restaurants', 'restaurants.franchisee_id', '=', 'franchisee_updates.franchisee_id');
		$query	= $query->select('franchisee_updates.id as update_id', 'restaurants.id', 'restaurants.name', 'restaurants.photo', 'restaurants.min_delivery_cost','restaurants.cost_for_2','restaurants.address', 'restaurants.city_id', 'restaurants.area_id', 'restaurants.phone', 'restaurants.location_id', 'restaurants.speciality', 'franchisee_updates.updates', 'franchisee_updates.activation_type', 'franchisee_updates.start_date', 'franchisee_updates.end_date');
		$query	= $query->where('franchisee_updates.id', '=', $id);
		$update	= $query->first();
		
		if($update) {
			$areas						= Areas::lists('name', 'id');
			$cities						= Cities::lists('name', 'id');
			$locations					= Locations::lists('name', 'id');
			
			$tempArray					= new stdClass();
			$tempArray->update_id		= $update->update_id;
			$tempArray->restaurant_id	= $update->id;
			$tempArray->name			= $update->name;
			if($update->photo != '') {
				$tempArray->photo		= SITE_PATH.'data/restaurant/'.$update->photo;
			} else {
				$tempArray->photo		= SITE_PATH.'img/res-bg.jpg';
			}
			$tempArray->address			= $update->address;
			$tempArray->city			= (isset($cities[$update->city_id])) ? $cities[$update->city_id] : '';
			$tempArray->area			= (isset($areas[$update->area_id])) ? $areas[$update->area_id] : '';
			
			$tempArray->location		= $update->location_id;
			$tempArray->location_id		= (isset($locations[$update->location_id])) ? $locations[$update->location_id] : '';
			
			$tempArray->cost_for_2	= $update->cost_for_2;
			$tempArray->min_delivery_cost	= $update->min_delivery_cost;
			
			$tempArray->phone			= $update->phone;
			$tempArray->speciality		= $update->speciality;
			$tempArray->updates			= $update->updates;
			
			$startedAt					= explode('-', $update->start_date);
			$tempArray->start_date		= $startedAt[2].'/'.$startedAt[1].'/'.$startedAt[0];
			$endedAt					= explode('-', $update->end_date);
			$tempArray->end_date		= $endedAt[2].'/'.$endedAt[1].'/'.$endedAt[0];
			if($update->activation_type == 1) {
				$updateDays				= FranchiseeUpdateDays::where('update_id', '=', $update->update_id)->orderBy('days')->lists('days');
				$daysArray				= array(1 => 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
				$daysTextArray			= array();
				if($updateDays) {
					foreach($updateDays as $key => $value) {
						$daysTextArray[]	= $daysArray[$value];
					}
					$tempArray->days	= implode(',', $daysTextArray);
				}
			}
			$response->update			= $tempArray;
			$response->message			= 'Success';
			$response->code				= 0;
		} else {
			$response->message			= 'Invalid Update ID';
			$response->code				= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'User ID'=> $id ,'Name' => 'Show Franchisee Updates Response');
		Log::info('Show Franchisee Updates Response',  $logData);
		//	End: Log
		
		//echo "<pre>"; print_r($response); echo "</pre>";
		return Response::json($response);
	}
	
	public function listRestaurantUpdates() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		$rules = array(			
			'restaurant_id'	=> 'required|exists:restaurants,id'
			);				
		$validator	= Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$error					= $validator->errors()->all(':message');
			$error					= implode($error, ' ');
			$response->code			= 1;
			$response->message		= $error;
		}else{
			
			$id= Input::get('restaurant_id');
			$userId = Input::get('user_id');
		
		/*
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Restaurant Updates Request', 'Inputs' => Input::all());
		Log::info('List Restaurant Updates Request',  $logData);
		//	End: Log
		*/
		$favorites	= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
`restaurant_updates`.`id`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurant_updates` 
		left join `restaurants` on `restaurants`.`id` = `restaurant_updates`.`restaurant_id` 
		where `restaurant_updates`.`restaurant_id` = '.$id.' and 
			`restaurant_updates`.`status` = 1 and 
			`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
			`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
			((`restaurant_updates`.`all_time` = 1 and 
				
				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
				 or 
			(`restaurant_updates`.`all_time` = 0 and 
				
				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
		order by `restaurant_updates`.`is_primary`, `restaurant_updates`.`updated_at` desc');
		$franchiseefavorites	= array();
		
		$franchiseefavorites	= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
		franchisee_updates.id, franchisee_updates.updates, franchisee_updates.activation_type, franchisee_updates.start_date, franchisee_updates.end_date from `franchisee_updates` 
		left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`update_id` = `franchisee_updates`.`id` 
		left join `restaurants` on `restaurants`.`id` = `franchisee_updates_restaurant`.`restaurant_id` 
		where `restaurants`.`id` = '.$id.' and 
			franchisee_updates_restaurant.restaurant_id = '.$id.' and 
			`franchisee_updates`.`status` = 1 and 
			`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
			`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
			((`franchisee_updates`.`all_time` = 1 and 
				
				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
				 or 
			(`franchisee_updates`.`all_time` = 0 and 
				
				`franchisee_updates`.`end_time` >= "'.date('H:i:s').'"))
				order by `franchisee_updates`.`updated_at` desc');
		
		if($favorites || $franchiseefavorites) {
			$areas					= Areas::lists('name', 'id');
			$cities					= Cities::lists('name', 'id');
			$primaryCount			= 0;
			$tempRestaurantId		= 0;
			$favoritesUpdatesArray	= array();
			$recordsCount			= 0;
			
			if($franchiseefavorites) {
				foreach($franchiseefavorites as $key => $value) {
					if($value->activation_type == 1) {
						$day		= date('w')+1;
						$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->id)->where('days', '=', $day)->count();
					} else {
						$updateDays	= 1;
					}
					if($updateDays == 1) {
						$tempfavoritesUpdatesArray				= array();
						$tempfavoritesUpdatesArray['id']		= $value->id;
						$tempfavoritesUpdatesArray['updates']	= $value->updates;
						$starteAt								= explode('-', $value->start_date);
						$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
						$endedAt								= explode('-', $value->end_date);
						$tempfavoritesUpdatesArray['end_date']	= $endedAt[2].'/'.$endedAt[1].'/'.$endedAt[0];
						
						if($value->all_time == 1) {
							$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
							if($value->res_end_time_2 != '') {
								$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
							} else {
								$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
							}
						} else {
							$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
							$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
						}
						
						$tempfavoritesUpdatesArray['is_primary']= 0;
						$tempfavoritesUpdatesArray['is_franchisee']= 1;
						$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
						$recordsCount++;
					}
				}
			}
			
			if($favorites) {
				foreach($favorites as $key => $value) {
					if($value->activation_type == 1) {
						$day		= date('w')+1;
						$updateDays	= UpdateDays::where('update_id', '=', $value->id)->where('days', '=', $day)->count();
					} else {
						$updateDays	= 1;
					}
					if($updateDays == 1) {
						if($tempRestaurantId != $value->id) {
							$tempRestaurantId	= $value->id;
						}
						$tempfavoritesUpdatesArray				= array();
						$tempfavoritesUpdatesArray['id']		= $value->id;
						$tempfavoritesUpdatesArray['updates']	= $value->updates;
						$starteAt								= explode('-', $value->start_date);
						$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
						$endedAt								= explode('-', $value->end_date);
						$tempfavoritesUpdatesArray['end_date']	= $endedAt[2].'/'.$endedAt[1].'/'.$endedAt[0];
						
						if($value->all_time == 1) {
							$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
							if($value->res_end_time_2 != '') {
								$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
							} else {
								$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
							}
						} else {
							$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
							$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
						}
						
						$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
						$tempfavoritesUpdatesArray['is_franchisee']= 0;
						$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
						$recordsCount++;
					}
				}
			}
			
			if($recordsCount > 0) {
				if(count($favoritesUpdatesArray) > 0) {
					shuffle($favoritesUpdatesArray);
				}
				$response->message	= 'Success';
				$response->code		= 0;
				$response->updates	= $favoritesUpdatesArray;
				$logs = new logs();
				$logs->user_id = Input::get('user_id');
				$logs->restaurant_id = Input::get('restaurant_id');
				$logs->type = 'List Restaurant Update';
				$logs->ip_address = Request::getClientIp();
				$logs->save();
			} else {
				$response->message	= 'No Records';
				$response->code		= 0;
			}
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Restaurant ID'=>$id,'Name' => 'List Restaurant Updates Response');
		Log::info('List Restaurant Updates Response',  $logData);
		//	End: Log
		
		//echo "<pre>"; print_r($response); echo "</pre>";
		return Response::json($response);
		}
		
	}
	
	public function listUpdatesFromArea() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		$rules = array(			
			'area_id'	=> 'required|exists:areas,id'
			);	
			$validator	= Validator::make(Input::all(), $rules);
		if ($validator->fails()) {
			$error					= $validator->errors()->all(':message');
			$error					= implode($error, ' ');
			$response->code			= 1;
			$response->message		= $error;
		}else{
			
			$id= Input::get('area_id');
			$userId = Input::get('user_id');
		
		
		$restaurantArray	= Restaurants::where('area_id', '=', $id)->lists('id');
		$restautantServedArray	= RestaurantAreas::where('area_id', '=', $id)->lists('restaurant_id');
		$restaurantIdArray	= array_merge($restaurantArray, $restaurantArray);
		
		$updateArray	= array();
		$updateIdArray	= array();
		
		if(count($restaurantIdArray) > 0) {
			$areas					= Areas::lists('name', 'id');
			$cities					= Cities::lists('name', 'id');
			$locations				= Locations::lists('name', 'id');
			
			foreach($restaurantIdArray as $restaurant_id) {
				$restaurantUpdateArray	= array();
				$updatesCount			= 0;
				
				//Franchisee
				$franchiseefavorites	= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
												`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`, `franchisee_updates`.`end_date` from `restaurants` 
													left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
													left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
													where `restaurants`.`id` = '.$restaurant_id.' and 
														`franchisee_updates`.`status` = 1 and 
														`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
														`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
													((`franchisee_updates`.`all_time` = 1 and 
														
														(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
														 or 
													(`franchisee_updates`.`all_time` = 0 and 
														
														`franchisee_updates`.`end_time` >= "'.date('H:i:s').'"))
													order by `franchisee_updates`.`updated_at` desc');
				
				if($franchiseefavorites) {
					foreach($franchiseefavorites as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= 0;
							$tempfavoritesUpdatesArray['is_franchisee']= 1;
							
							if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
						}
					}
				}
				
				//Primary
				$favorites			= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
										`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																	left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																	where `restaurants`.`id` = '.$restaurant_id.' and 
																			`restaurant_updates`.`status` = 1 and 
																			`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
																			`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
																			`restaurant_updates`.`is_primary` = 1 and
																			((`restaurant_updates`.`all_time` = 1 and 
																				
																				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																				 or 
																			(`restaurant_updates`.`all_time` = 0 and 
																				
																				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																			order by `restaurant_updates`.`updated_at` desc');
				if($favorites) {
					foreach($favorites as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							
							if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
							
						}
					}
				}
				
				//Today only
				$favoritesToday		= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
										`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																	left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																	where `restaurants`.`id` = '.$restaurant_id.' and 
																			`restaurant_updates`.`status` = 1 and 
																			`restaurant_updates`.`start_date` = "'.date('Y-m-d'). ' 00:00:00" and 
																			`restaurant_updates`.`end_date` = "'.date('Y-m-d'). '" and 
																			`restaurant_updates`.`is_primary` = 0 and
																			((`restaurant_updates`.`all_time` = 1 and 
																				
																				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																				 or 
																			(`restaurant_updates`.`all_time` = 0 and 
																				
																				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																			order by `restaurant_updates`.`updated_at` desc');
				if($favoritesToday) {
					foreach($favoritesToday as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							
							if(!isset($updateIdArray) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
						
						}
					}
				}
				//Normal
				if(count($updateIdArray) > 0) {
					$favorites			= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
											`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																		left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																		where `restaurants`.`id` = '.$restaurant_id.' and 
																				`restaurant_updates`.`status` = 1 and 
																				`restaurant_updates`.`id` not in ('.implode(',', $updateIdArray).') and 
																				`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
																				`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
																				`restaurant_updates`.`is_primary` = 0 and
																				((`restaurant_updates`.`all_time` = 1 and 
																					
																					(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																					 or 
																				(`restaurant_updates`.`all_time` = 0 and 
																					
																					`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																				order by `restaurant_updates`.`updated_at` desc');
				} else {
					$favorites			= DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
										`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
																	left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
																	where `restaurants`.`id` = '.$restaurant_id.' and 
																			`restaurant_updates`.`status` = 1 and 
																			`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
																			`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
																			`restaurant_updates`.`is_primary` = 0 and
																			((`restaurant_updates`.`all_time` = 1 and 
																				
																				(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
																				 or 
																			(`restaurant_updates`.`all_time` = 0 and 
																				
																				`restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
																			order by `restaurant_updates`.`updated_at` desc');
				}
				if($favorites) {
					foreach($favorites as $key => $value) {
						if($value->activation_type == 1) {
							$day		= date('w')+1;
							$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
						} else {
							$updateDays	= 1;
						}
						if($updateDays == 1) {
							//if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
								$updatesCount++;
							//}
							$tempfavoritesUpdatesArray				= array();
							$tempfavoritesUpdatesArray['id']		= $value->id;
							$tempfavoritesUpdatesArray['name']		= $value->name;
							if($value->photo != '') {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
							} else {
								$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'img/res-bg.jpg';
							}
							$tempfavoritesUpdatesArray['address']	= $value->address;
							$tempfavoritesUpdatesArray['city_id']	= (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
							$tempfavoritesUpdatesArray['area_id']	= (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
							
							$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
							$tempfavoritesUpdatesArray['location']		= (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
							
							$tempfavoritesUpdatesArray['cost_for_2']		= $value->cost_for_2;
							$tempfavoritesUpdatesArray['min_delivery_cost']		= $value->min_delivery_cost;
							
							$tempfavoritesUpdatesArray['phone']		= $value->phone;
							$tempfavoritesUpdatesArray['speciality']= $value->speciality;
							$tempfavoritesUpdatesArray['updates']	= $value->updates;
							$starteAt								= explode('-', $value->start_date);
							$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
							$endedAt								= explode('-', $value->end_date);
							$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
							
							if($value->all_time == 1) {
								$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
								if($value->res_end_time_2 != '') {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time_2;
								} else {
									$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
								}
							} else {
								$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
								$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
							}
							
							$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
							$tempfavoritesUpdatesArray['is_franchisee']= 0;
							
							if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
								$restaurantUpdateArray[]	= $tempfavoritesUpdatesArray;
								$resIdArray[$restaurant_id]	= $restaurant_id;
								$updateIdArray[$value->update_id]		= $value->update_id;
							}
							
						}
					}
				}
				if(count($restaurantUpdateArray) > 0) {
					$updateArray[]	= array('updates' => $restaurantUpdateArray, 'count' => ($updatesCount-1));
				}
			}
			if($updateArray > 0) {
				$response->message	= 'Success';
				$response->code		= 0;
				$response->results	= $updateArray;
			} else {
				$response->message	= 'No Records';
				$response->code		= 0;
			}
		
		} else {
			$response->message	= 'No Records';
			$response->code		= 0;
		}
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Area ID'=>$id,'User ID'=>$userId,'Name' => 'List Area Updates Response');
		Log::info('List Area Updates Response',  $logData);
		//	End: Log
		
		//echo "<pre>"; print_r($response); echo "</pre>";
		return Response::json($response);
		
		}
	}
	
	
	public function listRestaurantByLatLong() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		/*//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(), 'Name' => 'List Restaurant By Latlon Request', 'Inputs' => Input::all());
		Log::info('List Restaurant By Latlon Request',  $logData);
		//	End: Log*/
		
		$lat	= 0;
		$lon	= 0;
		if(Input::has('lat')) {
			$lat	= Input::get('lat');
		}
		if(Input::has('lon')) {
			$lon	= Input::get('lon');
		}
		if(isset($lat) && trim($lat) != '' && isset($lon) && trim($lon) != '') {
			
			$origLat	= $lat;
			$origLon	= $lon;
			$dist		= 3;
			$query		= "SELECT id, 3956 * 2 * ASIN(SQRT( POWER(SIN(($origLat - abs(lat))*pi()/180/2),2)+COS($origLat*pi()/180)*COS(abs(lat)*pi()/180)
			          *POWER(SIN(($origLon-lon)*pi()/180/2),2))) 
			          as distance FROM areas WHERE 
			          lon between ($origLon-$dist/abs(cos(radians($origLat))*69)) 
			          and ($origLon+$dist/abs(cos(radians($origLat))*69)) 
			          and lat between ($origLat-($dist/69)) 
			          and ($origLat+($dist/69)) 
			          having distance < $dist ORDER BY distance;"; 
			$results = DB::select($query);
			$areas	= array();
			if($results) {
				foreach($results as $key => $value) {
					$areas[]	= $value->id;
				}
			}
			
			$locations	= Locations::lists('name', 'id');
			
			if(count($areas) > 0) {
				$restaurants	= Restaurants::whereIn('area_id', $areas)->get();
				if($restaurants) {
					$response->message	= 'Success';
					$response->code		= 0;
					$resIdArray	= array();
					$newresIdArray	= array();
					$resArray	= array();
					foreach($restaurants as $key => $value) {
						$tempArray				= new stdClass();
						$tempArray->id			= $value->id;
						$tempArray->name		= $value->name;
						$tempArray->phone		= $value->phone;
						$tempArray->address		= $value->address;
						$tempArray->cost_for_2		= $value->cost_for_2;
						$tempArray->min_delivery_cost		= $value->min_delivery_cost;
						
						if($value->photo != '') {
							$tempArray->photo	= SITE_PATH.'data/restaurant/'.$value->photo;
						} else {
							$tempArray->photo	= SITE_PATH.'img/res-bg.jpg';
						}
						$resIdArray[$value->id]	= 1;
						
						$favorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
						`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`,restaurants.location_id, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`,
						`restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
						left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
						where `restaurants`.`id` = '.$value->id.' and 
						`restaurant_updates`.`status` = 1 and 
						`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
						`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
						(
							(`restaurant_updates`.`all_time` = 1 and 
							time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
							(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
						 or 
							(`restaurant_updates`.`all_time` = 0 and `restaurant_updates`.`start_time` <= "'.date('H:i:s').'" and 
							`restaurant_updates`.`end_time` >= "'.date('H:i:s').'")
						) 
						order by `restaurant_updates`.`id` asc' );
						
						$franchiseefavorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
						`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`,
						`franchisee_updates`.`end_date` from `restaurants` 
						left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
						left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
						where `restaurants`.`id` = '.$value->id.' and 
							`franchisee_updates`.`status` = 1 and 
							`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
							`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
							((`franchisee_updates`.`all_time` = 1 and 
								time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
								(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'")) 
							or 
							(`franchisee_updates`.`all_time` = 0 and 
								`franchisee_updates`.`start_time` <= "'.date('H:i:s').'" and 
								`franchisee_updates`.`end_time` >= "'.date('H:i:s').'") 
							)
							group by `franchisee_updates`.`id` 
							order by `franchisee_updates`.`id` asc');
						
						if($favorites || $franchiseefavorites) {
							$areas					= Areas::lists('name', 'id');
							$cities					= Cities::lists('name', 'id');
							$primaryCount			= 0;
							$tempRestaurantId		= 0;
							//$favoritesUpdatesArray	= array();
							$favoritesUpdatesArray	= '';
							$recordsCount			= 0;
							
							if($favorites) {
								foreach($favorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									
									if($updateDays == 1) {
										if($tempRestaurantId != $value->id) {
											$primaryCount		= 0;
											$tempRestaurantId	= $value->id;
										}
										if($value->is_primary == 1) {
											$primaryCount++;
										}
										if($primaryCount <= 2) {
											//$tempfavoritesUpdatesArray				= array();
											$tempfavoritesUpdatesArray				= '';
											//$tempfavoritesUpdatesArray['id']		= $value->id;
											/*	$tempfavoritesUpdatesArray['name']		= $value->name;
											if($value->photo != '') {
												$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
											}
											$tempfavoritesUpdatesArray['address']	= $value->address;
											$tempfavoritesUpdatesArray['city_id']	= $cities[$value->city_id];
											$tempfavoritesUpdatesArray['area_id']	= $areas[$value->area_id];
											
											$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
											$tempfavoritesUpdatesArray['location']		= $locations[$value->location_id];
											
											$tempfavoritesUpdatesArray['phone']		= $value->phone;
											$tempfavoritesUpdatesArray['speciality']= $value->speciality;	*/
											//$tempfavoritesUpdatesArray['text']	= $value->updates;
											$tempfavoritesUpdatesArray	= $value->updates;
											/*	$starteAt								= explode('-', $value->start_date);
											$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
											$endedAt								= explode('-', $value->end_date);
											$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
											
											if($value->all_time == 1) {
												$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
												$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
											} else {
												$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
												$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
											}
											
											$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
											$tempfavoritesUpdatesArray['is_franchisee']= 0;	*/
											//$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
											
											if(!isset($newresIdArray[$value->id])) {
												//$favoritesUpdatesArray[]			= $tempfavoritesUpdatesArray;
												$favoritesUpdatesArray			= $tempfavoritesUpdatesArray;
												$newresIdArray[$value->id]			= $value->id;
											}
											$recordsCount++;
										}
									}
								}
							}
							if($franchiseefavorites) {
								foreach($franchiseefavorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									if($updateDays == 1) {
										//$tempfavoritesUpdatesArray				= array();
										$tempfavoritesUpdatesArray				= '';
										//$tempfavoritesUpdatesArray['id']		= $value->id;
										/*	$tempfavoritesUpdatesArray['name']		= $value->name;
										if($value->photo != '') {
											$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
										}
										$tempfavoritesUpdatesArray['address']	= $value->address;
										$tempfavoritesUpdatesArray['city_id']	= $cities[$value->city_id];
										$tempfavoritesUpdatesArray['area_id']	= $areas[$value->area_id];
										
										$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
										$tempfavoritesUpdatesArray['location']		= $locations[$value->location_id];
										
										$tempfavoritesUpdatesArray['phone']		= $value->phone;
										$tempfavoritesUpdatesArray['speciality']= $value->speciality;	*/
										//$tempfavoritesUpdatesArray['text']	= $value->updates;
										$tempfavoritesUpdatesArray	= $value->updates;
										/*	$starteAt								= explode('-', $value->start_date);
										$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
										$endedAt								= explode('-', $value->end_date);
										$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
										
										if($value->all_time == 1) {
											$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
											$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
										} else {
											$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
											$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
										}
										
										$tempfavoritesUpdatesArray['is_primary']= 0;
										$tempfavoritesUpdatesArray['is_franchisee']= 1;	*/
										//$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
										if(!isset($newresIdArray[$value->id])) {
											//$favoritesUpdatesArray[]			= $tempfavoritesUpdatesArray;
											$favoritesUpdatesArray			= $tempfavoritesUpdatesArray;
											$newresIdArray[$value->id]			= $value->id;
										}
										$recordsCount++;
									}
								}
							}
							if($recordsCount > 0) {
								$tempArray->updates	= $favoritesUpdatesArray;
							} else {
								$tempArray->updates	= '';
							}
						} else {
							$tempArray->updates	= '';
						}
						
						$resArray[]				= $tempArray;
					}
					$restaurantsIDs	= RestaurantAreas::whereIn('area_id', $areas)->lists('restaurant_id');
					if($restaurantsIDs) {
						$restaurants	= Restaurants::whereIn('id', $restaurantsIDs)->get();
					}
					foreach($restaurants as $key => $value) {
						$tempArray				= new stdClass();
						$tempArray->id			= $value->id;
						$tempArray->name		= $value->name;
						$tempArray->phone		= $value->phone;
						$tempArray->address		= $value->address;
						if($value->photo != '') {
							$tempArray->photo	= SITE_PATH.'data/restaurant/'.$value->photo;
						} else {
							$tempArray->photo	= SITE_PATH.'img/res-bg.jpg';
						}
						$favorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
						`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`, restaurants.location_id, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`,
						`restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
						left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
						where `restaurants`.`id` = '.$value->id.' and 
						`restaurant_updates`.`status` = 1 and 
						`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
						`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
						(
							(`restaurant_updates`.`all_time` = 1 and 
							time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
							(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
						 or 
							(`restaurant_updates`.`all_time` = 0 and `restaurant_updates`.`start_time` <= "'.date('H:i:s').'" and 
							`restaurant_updates`.`end_time` >= "'.date('H:i:s').'")
						) 
						order by `restaurants`.`id` asc' );
						
						$franchiseefavorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
						`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`,restaurants.location_id, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`,
						`franchisee_updates`.`end_date` from `restaurants` 
						left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
						left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
						where `restaurants`.`id` = '.$value->id.' and 
							`franchisee_updates`.`status` = 1 and 
							`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
							`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
							((`franchisee_updates`.`all_time` = 1 and 
								time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
								(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'")) 
							or 
							(`franchisee_updates`.`all_time` = 0 and 
								`franchisee_updates`.`start_time` <= "'.date('H:i:s').'" and 
								`franchisee_updates`.`end_time` >= "'.date('H:i:s').'") 
							)
							group by `franchisee_updates`.`id` 
							order by `restaurants`.`id` asc');
						
						if($favorites || $franchiseefavorites) {
							$areas					= Areas::lists('name', 'id');
							$cities					= Cities::lists('name', 'id');
							$primaryCount			= 0;
							$tempRestaurantId		= 0;
							//$favoritesUpdatesArray	= array();
							$favoritesUpdatesArray	= '';
							$recordsCount			= 0;
							
							if($favorites) {
								foreach($favorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									
									if($updateDays == 1) {
										if($tempRestaurantId != $value->id) {
											$primaryCount		= 0;
											$tempRestaurantId	= $value->id;
										}
										if($value->is_primary == 1) {
											$primaryCount++;
										}
										if($primaryCount <= 2) {
											//$tempfavoritesUpdatesArray				= array();
											$tempfavoritesUpdatesArray				= '';
											//$tempfavoritesUpdatesArray['id']		= $value->id;
											/*	$tempfavoritesUpdatesArray['name']		= $value->name;
											if($value->photo != '') {
												$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
											}
											$tempfavoritesUpdatesArray['address']	= $value->address;
											$tempfavoritesUpdatesArray['city_id']	= $cities[$value->city_id];
											$tempfavoritesUpdatesArray['area_id']	= $areas[$value->area_id];
											
											$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
											$tempfavoritesUpdatesArray['location']		= $locations[$value->location_id];
											
											$tempfavoritesUpdatesArray['phone']		= $value->phone;
											$tempfavoritesUpdatesArray['speciality']= $value->speciality;	*/
											//$tempfavoritesUpdatesArray['text']	= $value->updates;
											$tempfavoritesUpdatesArray	= $value->updates;
											/*	$starteAt								= explode('-', $value->start_date);
											$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
											$endedAt								= explode('-', $value->end_date);
											$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
											
											if($value->all_time == 1) {
												$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
												$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
											} else {
												$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
												$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
											}
											
											$tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
											$tempfavoritesUpdatesArray['is_franchisee']= 0;	*/
											$favoritesUpdatesArray				= $tempfavoritesUpdatesArray;
											$recordsCount++;
										}
									}
								}
							}
							if($franchiseefavorites) {
								foreach($franchiseefavorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									if($updateDays == 1) {
										//$tempfavoritesUpdatesArray				= array();
										$tempfavoritesUpdatesArray				= '';
										//$tempfavoritesUpdatesArray['id']		= $value->id;
										/*	$tempfavoritesUpdatesArray['name']		= $value->name;
										if($value->photo != '') {
											$tempfavoritesUpdatesArray['photo']	= SITE_PATH.'data/restaurant/'.$value->photo;
										}
										$tempfavoritesUpdatesArray['address']	= $value->address;
										$tempfavoritesUpdatesArray['city_id']	= $cities[$value->city_id];
										$tempfavoritesUpdatesArray['area_id']	= $areas[$value->area_id];
										
										$tempfavoritesUpdatesArray['location_id']	= $value->location_id;
										$tempfavoritesUpdatesArray['location']		= $locations[$value->location_id];
										
										$tempfavoritesUpdatesArray['phone']		= $value->phone;
										$tempfavoritesUpdatesArray['speciality']= $value->speciality;	*/
										//$tempfavoritesUpdatesArray['text']	= $value->updates;
										$tempfavoritesUpdatesArray	= $value->updates;
										/*	$starteAt								= explode('-', $value->start_date);
										$tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
										$endedAt								= explode('-', $value->end_date);
										$tempfavoritesUpdatesArray['end_date']	= $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
										
										if($value->all_time == 1) {
											$tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
											$tempfavoritesUpdatesArray['end_time']	= $value->res_end_time;
										} else {
											$tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
											$tempfavoritesUpdatesArray['end_time']	= $value->update_end_time;
										}
										
										$tempfavoritesUpdatesArray['is_primary']= 0;
										$tempfavoritesUpdatesArray['is_franchisee']= 1;	*/
										
										//$favoritesUpdatesArray[]				= $tempfavoritesUpdatesArray;
										$favoritesUpdatesArray				= $tempfavoritesUpdatesArray;
										$recordsCount++;
									}
								}
							}
							if($recordsCount > 0) {
								$tempArray->updates	= $favoritesUpdatesArray;
							} else {
								$tempArray->updates	= '';
							}
						} else {
							$tempArray->updates	= '';
						}
						if(!isset($resIdArray[$value->id])) {
							$resArray[]				= $tempArray;
						}
					}
					$response->restaurants	= $resArray;
				} else {
					$response->message	= 'no records';
					$response->code		= 0;
				}
			} else {
				$response->message	= 'no records';
				$response->code		= 0;
			}
		} else {
			$response->message	= 'Invalid Latlong';
			$response->code		= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'Lat'=>Input::get('lat'),'Lon'=>Input::get('lon'), 'Name' => 'List Restaurant By Latlon Response', 'Output' => $response);
		Log::info('List Restaurant By Latlon Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	
	public function listUpdatesByLatLong() {
		date_default_timezone_set("Asia/Kolkata");
		$conn		= mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
		$db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
		$result		= mysql_query("SET @@session.time_zone='+05:30';", $conn);
		mysql_query("SET time_zone = '".date('P')."'");
		
		$response			= new stdClass();
		$response->code		= '';
		$response->message	= '';
		
		$lat	= 0;
		$lon	= 0;
		if(Input::has('lat')) {
			$lat	= Input::get('lat');
		}
		if(Input::has('lon')) {
			$lon	= Input::get('lon');
		}
		if(isset($lat) && trim($lat) != '' && isset($lon) && trim($lon) != '') {
			
			$origLat	= $lat;
			$origLon	= $lon;
			$dist		= 4;
			$query		= "SELECT id, 3956 * 2 * ASIN(SQRT( POWER(SIN(($origLat - abs(lat))*pi()/180/2),2)+COS($origLat*pi()/180)*COS(abs(lat)*pi()/180)
			          *POWER(SIN(($origLon-lon)*pi()/180/2),2))) 
			          as distance FROM areas WHERE 
			          lon between ($origLon-$dist/abs(cos(radians($origLat))*69)) 
			          and ($origLon+$dist/abs(cos(radians($origLat))*69)) 
			          and lat between ($origLat-($dist/69)) 
			          and ($origLat+($dist/69)) 
			          having distance < $dist ORDER BY distance;"; 
			$results = DB::select($query);
			$areas	= array();
			if($results) {
				foreach($results as $key => $value) {
					$areas[]	= $value->id;
				}
			}
			
			$locations	= Locations::lists('name', 'id');
			
			if(count($areas) > 0) {
				$restaurants	= Restaurants::whereIn('area_id', $areas)->get();
				if($restaurants) {
					$response->message	= 'Success';
					$response->code		= 0;
					$resIdArray	= array();
					$newresIdArray	= array();
					$resArray	= array();
					foreach($restaurants as $key => $value) {
						$tempArray				= '';
						$resIdArray[$value->id]	= 1;
						
						$favorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
						`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`,restaurants.location_id, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`,
						`restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
						left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
						where `restaurants`.`id` = '.$value->id.' and 
						`restaurant_updates`.`status` = 1 and 
						`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
						`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
						(
							(`restaurant_updates`.`all_time` = 1 and 
							time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
							(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
						 or 
							(`restaurant_updates`.`all_time` = 0 and `restaurant_updates`.`start_time` <= "'.date('H:i:s').'" and 
							`restaurant_updates`.`end_time` >= "'.date('H:i:s').'")
						) 
						order by `restaurant_updates`.`id` asc' );
						
						$franchiseefavorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
						`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`,
						`franchisee_updates`.`end_date` from `restaurants` 
						left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
						left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
						where `restaurants`.`id` = '.$value->id.' and 
							`franchisee_updates`.`status` = 1 and 
							`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
							`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
							((`franchisee_updates`.`all_time` = 1 and 
								time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
								(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'")) 
							or 
							(`franchisee_updates`.`all_time` = 0 and 
								`franchisee_updates`.`start_time` <= "'.date('H:i:s').'" and 
								`franchisee_updates`.`end_time` >= "'.date('H:i:s').'") 
							)
							group by `franchisee_updates`.`id` 
							order by `franchisee_updates`.`id` asc');
						
						if($favorites || $franchiseefavorites) {
							$areas					= Areas::lists('name', 'id');
							$cities					= Cities::lists('name', 'id');
							$primaryCount			= 0;
							$tempRestaurantId		= 0;
							$favoritesUpdatesArray	= '';
							$recordsCount			= 0;
							
							if($favorites) {
								foreach($favorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									
									if($updateDays == 1) {
										if($tempRestaurantId != $value->id) {
											$primaryCount		= 0;
											$tempRestaurantId	= $value->id;
										}
										if($value->is_primary == 1) {
											$primaryCount++;
										}
										if($primaryCount <= 2) {
											$tempfavoritesUpdatesArray	= '';
											$tempfavoritesUpdatesArray	= $value->updates;
											
											if(!isset($newresIdArray[$value->id])) {
												$favoritesUpdatesArray		= $tempfavoritesUpdatesArray;
												$newresIdArray[$value->id]	= $value->id;
											}
											$recordsCount++;
										}
									}
								}
							}
							if($franchiseefavorites) {
								foreach($franchiseefavorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									if($updateDays == 1) {
										$tempfavoritesUpdatesArray				= '';
										$tempfavoritesUpdatesArray	= $value->updates;
										if(!isset($newresIdArray[$value->id])) {
											$favoritesUpdatesArray			= $tempfavoritesUpdatesArray;
											$newresIdArray[$value->id]			= $value->id;
										}
										$recordsCount++;
									}
								}
							}
							if($recordsCount > 0) {
								$tempArray	= $favoritesUpdatesArray;
							} else {
								$tempArray	= '';
							}
						} else {
							$tempArray	= '';
						}
						
						if(trim($tempArray) != '') {
							$resArray[]		= $tempArray;
						}
					}
					$restaurantsIDs	= RestaurantAreas::whereIn('area_id', $areas)->lists('restaurant_id');
					if($restaurantsIDs) {
						$restaurants	= Restaurants::whereIn('id', $restaurantsIDs)->get();
					}
					foreach($restaurants as $key => $value) {
						$tempArray				= '';
						$favorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
						`restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`, restaurants.location_id, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`,
						`restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
						left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
						where `restaurants`.`id` = '.$value->id.' and 
						`restaurant_updates`.`status` = 1 and 
						`restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
						`restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
						(
							(`restaurant_updates`.`all_time` = 1 and 
							time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
							(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
						 or 
							(`restaurant_updates`.`all_time` = 0 and `restaurant_updates`.`start_time` <= "'.date('H:i:s').'" and 
							`restaurant_updates`.`end_time` >= "'.date('H:i:s').'")
						) 
						order by `restaurants`.`id` asc' );
						
						$franchiseefavorites	= DB::select( 'select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
						`franchisee_updates`.`id` as `update_id`, `restaurants`.`id`,restaurants.location_id, `restaurants`.`name`,
						`restaurants`.`photo`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`,
						`restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`,
						`franchisee_updates`.`end_date` from `restaurants` 
						left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
						left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
						where `restaurants`.`id` = '.$value->id.' and 
							`franchisee_updates`.`status` = 1 and 
							`franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
							`franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
							((`franchisee_updates`.`all_time` = 1 and 
								time_format(str_to_date(restaurants.start_time, "%h:%i %p"), "%H:%i:%s") <= "'.date('H:i:s').'" and 
								(time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'")) 
							or 
							(`franchisee_updates`.`all_time` = 0 and 
								`franchisee_updates`.`start_time` <= "'.date('H:i:s').'" and 
								`franchisee_updates`.`end_time` >= "'.date('H:i:s').'") 
							)
							group by `franchisee_updates`.`id` 
							order by `restaurants`.`id` asc');
						
						if($favorites || $franchiseefavorites) {
							$areas					= Areas::lists('name', 'id');
							$cities					= Cities::lists('name', 'id');
							$primaryCount			= 0;
							$tempRestaurantId		= 0;
							$favoritesUpdatesArray	= '';
							$recordsCount			= 0;
							
							if($favorites) {
								foreach($favorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									
									if($updateDays == 1) {
										if($tempRestaurantId != $value->id) {
											$primaryCount		= 0;
											$tempRestaurantId	= $value->id;
										}
										if($value->is_primary == 1) {
											$primaryCount++;
										}
										if($primaryCount <= 2) {
											$tempfavoritesUpdatesArray	= '';
											$tempfavoritesUpdatesArray	= $value->updates;
											
											$favoritesUpdatesArray		= $tempfavoritesUpdatesArray;
											$recordsCount++;
										}
									}
								}
							}
							if($franchiseefavorites) {
								foreach($franchiseefavorites as $key => $value) {
									if($value->activation_type == 1) {
										$day		= date('w')+1;
										$updateDays	= FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
									} else {
										$updateDays	= 1;
									}
									if($updateDays == 1) {
										$tempfavoritesUpdatesArray	= '';
										$tempfavoritesUpdatesArray	= $value->updates;
										$favoritesUpdatesArray		= $tempfavoritesUpdatesArray;
										$recordsCount++;
									}
								}
							}
							if($recordsCount > 0) {
								$tempArray	= $favoritesUpdatesArray;
							} else {
								$tempArray	= '';
							}
						} else {
							$tempArray	= '';
						}
						if(!isset($resIdArray[$value->id])) {
							if(trim($tempArray) != '') {
								$resArray[]	= $tempArray;
							}
						}
					}
					$response->updates	= $resArray;
				} else {
					$response->message	= 'no records';
					$response->code		= 0;
				}
			} else {
				$response->message	= 'no records';
				$response->code		= 0;
			}
		} else {
			$response->message	= 'Invalid Latlong';
			$response->code		= 1;
		}
		
		//	Start: Log
		$logData 	= array('IP Address' => Request::getClientIp(),'Lat'=>Input::get('lat'),'Lon'=>Input::get('lon'), 'Name' => 'List Updates By Latlon Response', 'Output' => $response);
		Log::info('List Updates By Latlon Response',  $logData);
		//	End: Log
		
		return Response::json($response);
	}
	

 	
	public function listUpdatesFromLocation() {
        date_default_timezone_set("Asia/Kolkata");
        $conn        = @mysql_connect("localhost", "foodzures_admin", "adminFood") or die('Could not connect!'); //your database connection here
        $db_selected= mysql_select_db('foozup_restaurant', $conn); //select db
        $result        = mysql_query("SET @@session.time_zone='+05:30';", $conn);
        mysql_query("SET time_zone = '".date('P')."'");
        
        $response            = new stdClass();
        $response->code        = '';
        $response->message    = '';
        
        $rules = array(
            'location_id'        => 'required',
        );
        $user_id  = Input::get('user_id');
        
        $messages    = array('alpha_spaces' => 'Firstname & Lastname must be alphanumeric');
        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules, $messages);
        
        if ($validator->fails()) {
            $error    = $validator->errors()->all(':message');
            $error    = implode($error, ' ');
            
            $response->message    = $error;
            $response->code        = 1;
        } 
		else {

        
			$param        = '['. Input::get('location_id') . ']' ;    
			$jsontags    = json_decode($param);
			
			$restaurantArray    = Restaurants::whereIn('location_id',$jsontags)->lists('id');
			$area                 = Locations::whereIn('id',$jsontags)->lists('area_id');        
			$restaurantServedArray    = RestaurantAreas::whereIn('area_id',$area)->lists('restaurant_id');
			shuffle($restaurantArray);
			shuffle($restaurantServedArray);
			$merge    = array_merge($restaurantArray, $restaurantServedArray);
			$restaurantIdArray    = array_unique($merge,SORT_REGULAR);
			
			$updateArray    = array();
			$updateIdArray    = array();
        
            $areas                    = Areas::lists('name', 'id');
            $cities                    = Cities::lists('name', 'id');
            $locations                = Locations::lists('name', 'id');
            
            foreach($restaurantIdArray as $restaurant_id) {
                $restaurantUpdateArray    = array();
                $updatesCount            = 0;
                
                //Franchisee
                $franchiseefavorites    = DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, franchisee_updates.start_time as update_start_time, franchisee_updates.end_time as update_end_time, franchisee_updates.all_time, 
                                                `franchisee_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `franchisee_updates`.`updates`, `franchisee_updates`.`activation_type`, `franchisee_updates`.`start_date`, `franchisee_updates`.`end_date` from `restaurants` 
                                                    left join `franchisee_updates_restaurant` on `franchisee_updates_restaurant`.`restaurant_id` = `restaurants`.`id` 
                                                    left join `franchisee_updates` on `franchisee_updates`.`id` = `franchisee_updates_restaurant`.`update_id` 
                                                    where `restaurants`.`id` = '.$restaurant_id.' and 
                                                        `franchisee_updates`.`status` = 1 and 
                                                        `franchisee_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
                                                        `franchisee_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
                                                    ((`franchisee_updates`.`all_time` = 1 and 
                                                        
                                                        (time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
                                                         or 
                                                    (`franchisee_updates`.`all_time` = 0 and 
                                                        
                                                        `franchisee_updates`.`end_time` >= "'.date('H:i:s').'"))
                                                    order by `franchisee_updates`.`updated_at` desc');
                
                if($franchiseefavorites) {
                    foreach($franchiseefavorites as $key => $value) {
                        if($value->activation_type == 1) {
                            $day        = date('w')+1;
                            $updateDays    = FranchiseeUpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
                        } else {
                            $updateDays    = 1;
                        }
                        if($updateDays == 1) {
                            //if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
                                $updatesCount++;
                            //}
                            $tempfavoritesUpdatesArray                = array();
                            $tempfavoritesUpdatesArray['id']        = $value->id;
                            $tempfavoritesUpdatesArray['name']        = $value->name;
                            if($value->photo != '') {
                                $tempfavoritesUpdatesArray['photo']    = SITE_PATH.'data/restaurant/'.$value->photo;
                            } else {
                                $tempfavoritesUpdatesArray['photo']    = SITE_PATH.'img/res-bg.jpg';
                            }
                            $tempfavoritesUpdatesArray['address']    = $value->address;
                            $tempfavoritesUpdatesArray['city_id']    = (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
                            $tempfavoritesUpdatesArray['area_id']    = (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
                            
                            $tempfavoritesUpdatesArray['location_id']    = $value->location_id;
                            $tempfavoritesUpdatesArray['location']        = (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
                            
                            $tempfavoritesUpdatesArray['cost_for_2']        = $value->cost_for_2;
                            $tempfavoritesUpdatesArray['min_delivery_cost']        = $value->min_delivery_cost;
                            
                            $tempfavoritesUpdatesArray['phone']        = $value->phone;
                            $tempfavoritesUpdatesArray['speciality']= $value->speciality;
                            $tempfavoritesUpdatesArray['updates']    = $value->updates;
                            $starteAt                                = explode('-', $value->start_date);
                            $tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
                            $endedAt                                = explode('-', $value->end_date);
                            $tempfavoritesUpdatesArray['end_date']    = $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
                            
                            if($value->all_time == 1) {
                                $tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
                                if($value->res_end_time_2 != '') {
                                    $tempfavoritesUpdatesArray['end_time']    = $value->res_end_time_2;
                                } else {
                                    $tempfavoritesUpdatesArray['end_time']    = $value->res_end_time;
                                }
                            } else {
                                $tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
                                $tempfavoritesUpdatesArray['end_time']    = $value->update_end_time;
                            }
                            
                            $tempfavoritesUpdatesArray['is_primary']= 0;
                            $tempfavoritesUpdatesArray['is_franchisee']= 1;
                            
                            if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
                                $restaurantUpdateArray[]    = $tempfavoritesUpdatesArray;
                                $resIdArray[$restaurant_id]    = $restaurant_id;
                                $updateIdArray[$value->update_id]        = $value->update_id;
                            }
                        }
                    }
                }
                
                //Primary
                $favorites            = DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
                                        `restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
                                                                    left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
                                                                    where `restaurants`.`id` = '.$restaurant_id.' and 
                                                                            `restaurant_updates`.`status` = 1 and 
                                                                            `restaurant_updates`.`start_date` <= "'.date('Y-m-d'). ' 00:00:00" and 
                                                                            `restaurant_updates`.`end_date` >= "'.date('Y-m-d'). '" and 
                                                                            `restaurant_updates`.`is_primary` = 1 and
                                                                            ((`restaurant_updates`.`all_time` = 1 and 
                                                                                
                                                                                (time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
                                                                                 or 
                                                                            (`restaurant_updates`.`all_time` = 0 and 
                                                                                
                                                                                `restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
                                                                            order by `restaurant_updates`.`updated_at` desc');
                if($favorites) {
                    foreach($favorites as $key => $value) {
                        if($value->activation_type == 1) {
                            $day        = date('w')+1;
                            $updateDays    = UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
                        } else {
                            $updateDays    = 1;
                        }
                        if($updateDays == 1) {
                            //if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
                                $updatesCount++;
                            //}
                            $tempfavoritesUpdatesArray                = array();
                            $tempfavoritesUpdatesArray['id']        = $value->id;
                            $tempfavoritesUpdatesArray['name']        = $value->name;
                            if($value->photo != '') {
                                $tempfavoritesUpdatesArray['photo']    = SITE_PATH.'data/restaurant/'.$value->photo;
                            } else {
                                $tempfavoritesUpdatesArray['photo']    = SITE_PATH.'img/res-bg.jpg';
                            }
                            $tempfavoritesUpdatesArray['address']    = $value->address;
                            $tempfavoritesUpdatesArray['city_id']    = (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
                            $tempfavoritesUpdatesArray['area_id']    = (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
                            
                            $tempfavoritesUpdatesArray['location_id']    = $value->location_id;
                            $tempfavoritesUpdatesArray['location']        = (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
                            
                            $tempfavoritesUpdatesArray['cost_for_2']        = $value->cost_for_2;
                            $tempfavoritesUpdatesArray['min_delivery_cost']        = $value->min_delivery_cost;
                            
                            $tempfavoritesUpdatesArray['phone']        = $value->phone;
                            $tempfavoritesUpdatesArray['speciality']= $value->speciality;
                            $tempfavoritesUpdatesArray['updates']    = $value->updates;
                            $starteAt                                = explode('-', $value->start_date);
                            $tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
                            $endedAt                                = explode('-', $value->end_date);
                            $tempfavoritesUpdatesArray['end_date']    = $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
                            
                            if($value->all_time == 1) {
                                $tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
                                if($value->res_end_time_2 != '') {
                                    $tempfavoritesUpdatesArray['end_time']    = $value->res_end_time_2;
                                } else {
                                    $tempfavoritesUpdatesArray['end_time']    = $value->res_end_time;
                                }
                            } else {
                                $tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
                                $tempfavoritesUpdatesArray['end_time']    = $value->update_end_time;
                            }
                            
                            $tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
                            $tempfavoritesUpdatesArray['is_franchisee']= 0;
                            
                            if(!isset($updateIdArray[$value->update_id]) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
                                $restaurantUpdateArray[]    = $tempfavoritesUpdatesArray;
                                $resIdArray[$restaurant_id]    = $restaurant_id;
                                $updateIdArray[$value->update_id]        = $value->update_id;
                            }
                            
                        }
                    }
                }
                
                //Today only
                $favoritesToday        = DB::select('select restaurants.start_time as res_start_time, restaurants.end_time as res_end_time, restaurants.end_time_2 as res_end_time_2, restaurant_updates.start_time as update_start_time, restaurant_updates.end_time as update_end_time, restaurant_updates.all_time, 
                                        `restaurant_updates`.`id` as `update_id`, `restaurants`.`id`, restaurants.location_id, `restaurants`.`name`, `restaurants`.`photo`, `restaurants`.`min_delivery_cost`, `restaurants`.`cost_for_2`, `restaurants`.`address`, `restaurants`.`city_id`, `restaurants`.`area_id`, `restaurants`.`phone`, `restaurants`.`speciality`, `restaurant_updates`.`updates`, `restaurant_updates`.`activation_type`, `restaurant_updates`.`start_date`, `restaurant_updates`.`end_date`, `restaurant_updates`.`is_primary` from `restaurants` 
                                                                    left join `restaurant_updates` on `restaurant_updates`.`restaurant_id` = `restaurants`.`id` 
                                                                    where `restaurants`.`id` = '.$restaurant_id.' and 
                                                                            `restaurant_updates`.`status` = 1 and 
                                                                            `restaurant_updates`.`start_date` = "'.date('Y-m-d'). ' 00:00:00" and 
                                                                            `restaurant_updates`.`end_date` = "'.date('Y-m-d'). '" and 
                                                                            `restaurant_updates`.`is_primary` = 0 and
                                                                            ((`restaurant_updates`.`all_time` = 1 and 
                                                                                
                                                                                (time_format(str_to_date(restaurants.end_time, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'" or time_format(str_to_date(restaurants.end_time_2, "%h:%i %p"), "%H:%i:%s") >= "'.date('H:i:s').'"))
                                                                                 or 
                                                                            (`restaurant_updates`.`all_time` = 0 and 
                                                                                
                                                                                `restaurant_updates`.`end_time` >= "'.date('H:i:s').'"))
                                                                            order by `restaurant_updates`.`updated_at` desc');
                if($favoritesToday) {
                    foreach($favoritesToday as $key => $value) {
                        if($value->activation_type == 1) {
                            $day        = date('w')+1;
                            $updateDays    = UpdateDays::where('update_id', '=', $value->update_id)->where('days', '=', $day)->count();
                        } else {
                            $updateDays    = 1;
                        }
                        if($updateDays == 1) {
                            //if(isset($restaurantUpdateArray[$restaurant_id]) && count($restaurantUpdateArray[$restaurant_id]) >= 1) {
                                $updatesCount++;
                            //}
                            $tempfavoritesUpdatesArray                = array();
                            $tempfavoritesUpdatesArray['id']        = $value->id;
                            $tempfavoritesUpdatesArray['name']        = $value->name;
                            if($value->photo != '') {
                                $tempfavoritesUpdatesArray['photo']    = SITE_PATH.'data/restaurant/'.$value->photo;
                            } else {
                                $tempfavoritesUpdatesArray['photo']    = SITE_PATH.'img/res-bg.jpg';
                            }
                            $tempfavoritesUpdatesArray['address']    = $value->address;
                            $tempfavoritesUpdatesArray['city_id']    = (isset($cities[$value->city_id])) ? $cities[$value->city_id] : '';
                            $tempfavoritesUpdatesArray['area_id']    = (isset($areas[$value->area_id])) ? $areas[$value->area_id] : '';
                            
                            $tempfavoritesUpdatesArray['location_id']    = $value->location_id;
                            $tempfavoritesUpdatesArray['location']        = (isset($locations[$value->location_id])) ? $locations[$value->location_id] : '';
                            
                            $tempfavoritesUpdatesArray['cost_for_2']        = $value->cost_for_2;
                            $tempfavoritesUpdatesArray['min_delivery_cost']        = $value->min_delivery_cost;
                            
                            $tempfavoritesUpdatesArray['phone']        = $value->phone;
                            $tempfavoritesUpdatesArray['speciality']= $value->speciality;
                            $tempfavoritesUpdatesArray['updates']    = $value->updates;
                            $starteAt                                = explode('-', $value->start_date);
                            $tempfavoritesUpdatesArray['start_date']= $starteAt[0].'/'.$starteAt[1].'/'.$starteAt[2];
                            $endedAt                                = explode('-', $value->end_date);
                            $tempfavoritesUpdatesArray['end_date']    = $endedAt[0].'/'.$endedAt[1].'/'.$endedAt[2];
                            
                            if($value->all_time == 1) {
                                $tempfavoritesUpdatesArray['start_time']= $value->res_start_time;
                                if($value->res_end_time_2 != '') {
                                    $tempfavoritesUpdatesArray['end_time']    = $value->res_end_time_2;
                                } else {
                                    $tempfavoritesUpdatesArray['end_time']    = $value->res_end_time;
                                }
                            } else {
                                $tempfavoritesUpdatesArray['start_time']= $value->update_start_time;
                                $tempfavoritesUpdatesArray['end_time']    = $value->update_end_time;
                            }
                            
                            $tempfavoritesUpdatesArray['is_primary']= $value->is_primary;
                            $tempfavoritesUpdatesArray['is_franchisee']= 0;
                            
                            if(!isset($updateIdArray) && (isset($resIdArray[$restaurant_id]) && count($restaurantUpdateArray) <= 1) || (!isset($resIdArray[$restaurant_id]))) {
                                $restaurantUpdateArray[]    = $tempfavoritesUpdatesArray;
                                $resIdArray[$restaurant_id]    = $restaurant_id;
                                $updateIdArray[$value->update_id]        = $value->update_id;
                            }
                        
                        }
                    }
                }
                if(count($restaurantUpdateArray) > 0) {
                    $updateArray[]    = array('updates' => $restaurantUpdateArray, 'count' => ($updatesCount-1));
                }
            }
            if($updateArray > 0) {
                $response->message    = 'Success';
                $response->code        = 0;                
                $response->results    = $updateArray;
            } else {
                $response->message    = 'No Records';
                $response->code        = 0;
            }
        
        } else {
            $response->message    = 'No Records';
            $response->code        = 0;
         }
        }
        //echo "<pre>"; print_r($response); echo "</pre>";
        return Response::json($response);
    }

}