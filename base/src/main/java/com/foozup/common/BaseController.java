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
		response.setCode(response.getData() !=null ? 1 : 0);
		response.setApiVersion(env.getProperty("apiVersion"));
		return response;
		
	}

}

