package com.foozup.configuration;

import javax.sql.DataSource;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.Import;
import org.springframework.web.servlet.config.annotation.EnableWebMvc;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurerAdapter;

import com.foozup.dao.AbstractDao;
import com.foozup.restaurant.dao.IRestaurantDao;
import com.foozup.restaurant.dao.RestaurantDaoImpl;
import com.foozup.staticData.dao.IStaticDataDao;
import com.foozup.staticData.dao.StaticDataDaoImpl;
import com.foozup.update.dao.IUpdatesDao;
import com.foozup.update.dao.UpdatesDaoImpl;

@Configuration
@EnableWebMvc
@ComponentScan(basePackages = "com.foozup")
@Import({SwaggerConfiguration.class , EhChacheConfiguration.class})
public class ProjectConfiguration extends WebMvcConfigurerAdapter {

	@Autowired
	private DataSource dataSource;

	@Bean
	public AbstractDao getAbstractDao() {
		return new AbstractDao(dataSource);
	}

	@Bean
	public IStaticDataDao dataServiceStaticData() {
		return new StaticDataDaoImpl(getAbstractDao());
	}
	
	@Bean
	public IRestaurantDao dataServiceRestaurant(){
		return new RestaurantDaoImpl(getAbstractDao());
	}
	
	@Bean
	public IUpdatesDao dataServiceUpdate(){
		return new UpdatesDaoImpl(getAbstractDao());
	}
	
	
	@Override
	public void addResourceHandlers(ResourceHandlerRegistry registry) {
		registry.addResourceHandler("swagger-ui.html").addResourceLocations("classpath:/META-INF/resources/");
		registry.addResourceHandler("/webjars/**").addResourceLocations("classpath:/META-INF/resources/webjars/");
		
	}
}
