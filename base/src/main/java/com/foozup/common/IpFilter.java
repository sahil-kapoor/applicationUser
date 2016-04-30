package com.foozup.common;

import java.io.IOException;

import javax.servlet.Filter;
import javax.servlet.FilterChain;
import javax.servlet.FilterConfig;
import javax.servlet.ServletException;
import javax.servlet.ServletRequest;
import javax.servlet.ServletResponse;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.foozup.admin.controller.AdminController;

public class IpFilter implements Filter {

	
	private static final Logger logger = LoggerFactory.getLogger(AdminController.class);
	
	@Override
	public void destroy() {
		// ...
	}

	@Override
	public void init(FilterConfig filterConfig) throws ServletException {
		//
	}

	@Override
	public void doFilter(ServletRequest request, 
               ServletResponse response, FilterChain chain)
		throws IOException, ServletException {

		
		try {
			logger.info("ip:"+((HttpServletRequest)request).getHeader("X-FORWARDED-FOR"));
			chain.doFilter(request, response);
		} catch (Exception ex) {
			
		}

	}
}
