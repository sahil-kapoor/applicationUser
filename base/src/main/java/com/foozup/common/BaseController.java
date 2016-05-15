package com.foozup.common;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.core.env.Environment;

import com.foozup.model.BaseResponse;

@Configuration
@PropertySource("classpath:applicationConstants.properties")
public class BaseController {

	
	@Autowired
	protected Environment env;

	public Object initializeResponse(BaseResponse response){
		response.setSuccess(response.getData() !=null ? true : false);
		response.setApiVersion(env.getProperty("apiVersion"));
		return response;
		
	}

}

